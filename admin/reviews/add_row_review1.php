<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
$main = "reviews_article_main";
$text = "reviews_article_text";
$status = "reviews_article_status";

if ($_POST["article"]){
$article_id = $_POST["article"];
}else{
$article_id = $_GET["article"];
}

if ($_POST["id"]){
$id = $_POST["id"];
}else{
$id = $_GET["id"];
}

if ($_POST["paragraph"]){
$paragraph = $_POST["paragraph"];
}else{
$paragraph = $_GET["paragraph"];
}

if ($_POST["action"]){
$action = $_POST["action"];
}else{
$action = $_GET["action"];
}
echo "<!-- cmn this is my action : $action -->";
if ($HTTP_GET_VARS ['mode'] <> ""){
$mode = $HTTP_GET_VARS ['mode'];
}


//perform article summary and heading update
switch ($action) {
    case "insert":
	//get the latest paragraph number
	$sqlsel="SELECT * FROM $text WHERE article=".$article_id;
	//echo $sqlsel;
	$result = mysql_query($sqlsel);
	$paragraph = mysql_num_rows($result) + 1;
	//get the new paragraph number
	$sqlsel="INSERT INTO $text ".REVIEW_TEXT_FIELDS." VALUES (";
	$sqlsel.="'".$article_id."', ";
	$sqlsel.="'".htmlspecialchars($_POST['pagraph_num'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['text'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['type'], ENT_QUOTES)."')";
	$sqlquery = $sqlsel;
	//echo $sqlquery;
	$result = mysql_query($sqlquery);
	if ($result){
	$message = "Article added";
	}else{
	$message = "Article could not be added";
	}
	//get a hanlde for the recoed taht has just been added
	$new_article_id = mysql_insert_id();
	$mode = "ADDSUCCESS";
	break;


	case "update":
	$SQLupdate="UPDATE $text SET ";
	$SQLupdate.= "text = '" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
	$SQLupdate.= "type = '" . htmlspecialchars($_POST['type'], ENT_QUOTES) . "' ";
	$SQLupdate.= "WHERE id = '" . $id . "'";
	$result = mysql_query($SQLupdate);
	if ($result){
	$update = "success";
	}
	//echo $SQLupdate;
	//next thing is to re-order the paragraphs so that they are still in order
	$order_sql = "SELECT * FROM $text WHERE article = $article_id AND id = $id";
	$order_result = mysql_query($order_sql);
	$order_row = mysql_fetch_assoc($order_result);

	$order_id = $order_row["paragraph"];
//	$nu_order_id = htmlspecialchars($_POST['paragraph'], ENT_QUOTES);
	$nu_order_id = htmlentities($_POST['paragraph'], ENT_QUOTES);
	if ($order_id > $nu_order_id ) // order higher
	{
		$update_sql = "UPDATE $text SET paragraph = (paragraph + 1) WHERE paragraph >= $nu_order_id AND paragraph < $order_id AND article = $article_id";
		//echo "<BR>$update_sql";
		$update_result = mysql_query($update_sql);

		if ($update_result)
		{
			$update_order = "UPDATE $text SET paragraph = $nu_order_id WHERE article = $article_id AND id = $id";
			//echo "<BR>$update_order";
			$order_result = mysql_query($update_order);
			if ($order_result){
				$update = "success";
				}else{
				$update = "failed";
				}
		}
		//echo "" . $update_order . "<br>";
	}
	else if ($order_id < $nu_order_id ) // order lower
	{
		$update_sql = "UPDATE $text SET paragraph = (paragraph - 1) WHERE paragraph > $order_id AND paragraph <= $nu_order_id AND article = $article_id";
		//echo "<BR>$update_sql";
		$update_result = mysql_query($update_sql);

		if ($update_result)
		{
			$update_order = "UPDATE $text SET paragraph = $nu_order_id WHERE article = $article_id AND id = $id";
			//echo "<BR>$update_order";
			$order_result = mysql_query($update_order);
			if ($order_result){
				$update = "success";
				}else{
				$update = "failed";
				}
		}
	}
	if ($update == "success")
	{
	$message = "Article updated";
	}else{
	$message = "Article not updated";
	}
	$mode = "EDITSUCCESS";
	break;


	case "delete":
			//check that variation exists with both id columns and delete

			$sqlsel="DELETE FROM ".$text." WHERE id = ".$id;
			//echo $sqlsel."<br>";
			$result = mysql_query($sqlsel);
			if($result){
			$message = "Article was deleted";
			$deleted = 1;
			}else{
			$message = "Article was not deleted";
			}
			//only run the category update if the previous article has been deleted
			if ($deleted == 1){
				//update the paragraph order for each article so that correct numbering is maintained
				$update_sql = "UPDATE ".$text." SET paragraph = (paragraph - 1) where paragraph > ".$paragraph." and article = " . $article_id;
				//echo $update_sql."<br>";
				$update_result = mysql_query($update_sql);
				if($update_result){
				$message .= "<br>Article order was updated";
				}else{
				$message .= "<br>Article order was not updated";
				}
			}

			$paragraph_id="";
			$mode = "DELETESUCCESS";
        break;
}
if ($mode == ""){
	$mode = "ADD";
}
?>

<?php
echo "<!-- cmn my mode is $mode-->";
switch ($mode) {
    case "ADD":

//get the latest paragraph number
$sqlsel="SELECT * FROM $text WHERE article=".$article_id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
$paragraph = mysql_num_rows($result) + 1;
?>
<html>

<head>
<title>Add new row</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=insert" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<table cellpadding="3">
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Paragraph : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="5" name="paragraph" value="<?php echo $paragraph; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="53" rows="9" name="text"></textarea></font></td>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Type: </b></font></td>
        <td><font face="ms sans serif" size="3"><select name="type">
        <option value="text">text</option>
        <option value="byline">byline</option>
        <option value="other">other</option>
        </select></font></td>
    </tr>
    </tr>
    <tr>
        <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new record to the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a><input type="hidden" name="article" value="<?php echo $article_id; ?>"><input type="hidden" name="dbtable" value="<?php echo $text; ?>"></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
	case "ADDSUCCESS":
?>
<html>

<head>
<title>Add new row</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_article,js_random) {
	opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_article,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
		refreshed ='yes';
	}
}

//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $article_id; ?>','1114440741');" onUnload="checkrefreshPickRecord('<?php echo $article_id; ?>','1114440741');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F"><p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $text WHERE id=".$new_article_id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
?>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Paragraph: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo $row["paragraph"]; ?></font></td>
</tr><tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
</tr>
<?php
}
?>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a>&nbsp;&nbsp;<a href="add_row_review1.php?article=<?php echo $article_id; ?>&dbtable=<?php echo $text; ?>&random=1114440742" onMouseOver="window.status='Add another record';return true" onMouseOut="window.status='';return true"><img src="../images/add_another.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>


<?php
break;
	case "EDIT":
?>
<html>

<head>
<title>Edit Row</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="edit_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=update" method="post">
<table cellpadding="3">
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $text WHERE id=".$id;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
?>
<tr>
<td valign="top"><font face="Verdana,Arial" size="2"><b>Paragraph: </b></font></td>
<td><font face="ms sans serif" size="3"><input type="text" name="paragraph" value="<?php echo $row["paragraph"]; ?>"></td>
</tr>
<tr>
<td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
<td><font face="ms sans serif" size="3">
<textarea cols="53" rows="9" name="text"><?php echo $row["text"]; ?></textarea></font>
<input type="hidden" name="dbtable" value="<?php echo $text; ?>">
<input type="hidden" name="random" value="1114440742">
<input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
<input type="hidden" name="article" value="<?php echo $row["article"]; ?>"></td>
</tr>
<tr>
<td valign="top"><font face="Verdana,Arial" size="2"><b>Type: </b></font></td>
<td>
<?php
switch ($row["type"]){
case "text":
	$selected_1 = "selected";
break;
case "byline":
	$selected_2 = "selected";
break;
case "other":
	$selected_3 = "selected";
break;
}
?>
<select name="type">
<option value="text" <?php echo $selected_1; ?>>text</option>
<option value="byline" <?php echo $selected_2; ?>>byline</option>
<option value="other" <?php echo $selected_3; ?>>other</option>
</select></td>
</tr>
<?php
//end select edit while
}
?>
<tr><td></td>
<td align="right"><a href="javascript:document.edit_row.submit()" onMouseOver="window.status='Update the current record';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
</tr>
</table>
</form>
</body>
</html>
<?php
break;
	case "EDITSUCCESS":
?>
<html>

<head>
<title>Edit row</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_article,js_random) {
	opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_article,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
		refreshed ='yes';
	}
}

//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $article_id; ?>','1114440741');" onUnload="checkrefreshPickRecord('<?php echo $article_id; ?>','1114440741');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F"><p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $text WHERE id=".$id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
?>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Paragraph: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo $row["paragraph"]; ?></font></td>
</tr><tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
</tr>
<?php
}
?>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>


<?php
break;
 case "DELETE":
?>
<html>

<head>
<title>Confirm Delete</title>
</head>

<body onBlur="self.focus()" text="#FFFFFF" background="../images/popup_edit_row_background.jpg"  bgproperties="fixed" bgcolor="#13474F">

<form name="delete_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<table cellpadding="3">
<tr>
<td colspan="2"><font face="Verdana,Arial" size="5" color="#FAD400">Confirm Delete</font></td>
</tr>
<tr>
<td colspan="2"><font face="Verdana,Arial" size="4">Please confirm that you want to delete this record:</font></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $text WHERE id=".$id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
?>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Paragraph: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo $row["paragraph"]; ?></font></td>
</tr>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
</tr>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Type: </b></font></td>
<td><font face="Verdana,Arial" size="1"><?php echo $row["type"]; ?></font></td>
</tr>

<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td></td>
<td align="center"><a href="javascript:document.delete_row.submit()" onMouseOver="window.status='Delete this record';return true" onMouseOut="window.status='';return true"><img src="../images/delete_record_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Do not delete this record and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/keep_record_button.gif" width="126" height="24" border="0"></a>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="dbtable" value="<?php echo $text; ?>">
<input type="hidden" name="article" value="<?php echo $article_id; ?>">
<input type="hidden" name="paragraph" value="<?php echo $row["paragraph"]; ?>">
<input type="hidden" name="random" value="1114443602">
</td>
</tr>
<?php
}
?>
</table>
</form>
</body>
</html>
<?php
break;
	case "DELETESUCCESS":
?>
<html>

<head>
<title>Deleting Row</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_article,js_random) {
	opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_article,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
		refreshed ='yes';
	}
}


//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $article_id; ?>','1114443602');" onUnload="checkrefreshPickRecord('<?php echo $article_id; ?>','1114443602');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F"><p><font face="Verdana,Arial" size="5" color="#FAD400">Record deleted</font></p>
<p>&nbsp;</p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></p>
</body>
</html>
<?php
break;
}
?>
