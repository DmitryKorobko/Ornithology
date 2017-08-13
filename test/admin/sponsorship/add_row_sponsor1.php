<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
if ($_POST["info_type"]){
$info_type = $_POST["info_type"];
}else{
$info_type = $_GET["info_type"];
}

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

if ($HTTP_GET_VARS ['mode'] <> ""){
$mode = $HTTP_GET_VARS ['mode'];
}

if ($_POST["category"]){
$category = $_POST["category"];
}else{
$category = $_GET["category"];
}

if ($_POST["subcategory"]){
$subcategory = $_POST["subcategory"];
}else{
$subcategory = $_GET["subcategory"];
}


//perform article update/insert/delete
if ($action){
	switch ($action) {
		case "sponsor":
		//do a check to see if we're adding or overwriting an image
		$select = "SELECT * FROM $info_type WHERE `section` = '$subcategory'";
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		//only allow for 1 photo to exist
		if ($count<1){
		// set max image dimensions
					$max_nuwidth = 550;
					$max_nuheight = 550;
					$userfile = "file1";
					$filename = "";
					$dir = dir_const."photos";
					//set variable to generate thumbnail
					$thumbnail = 0;

					// cmn - for php running as an apache module, must set parent dir as writable
					$chmodf = chmod_file."photos";
					ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

					$upload_message = upload_image ($HTTP_POST_FILES[$userfile]['name'], $HTTP_POST_FILES[$userfile]['tmp_name'], $HTTP_POST_FILES[$userfile]['type'], $HTTP_POST_FILES[$userfile]['size'], $max_nuwidth, $max_nuheight, $filename, $thumbnail, $dir);

					// cmn - for php running as an apache module, must reset parent dir back to non writable
					$chmodf = chmod_file."photos";
					ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

					//$upload_message = "complete";

					if ($upload_message == "no_image")
					{
						$success = 0;
					}
					else if ($upload_message != "")
					{
						$success = 0;
					}

					//do sql insert
					$sqlsel="INSERT INTO $info_type ".LINKS_SPONSOR_FIELDS." VALUES (";
					$sqlsel.="'".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
					$sqlsel.="'".htmlspecialchars($_POST['url'], ENT_QUOTES)."', ";
					$sqlsel.="'".htmlspecialchars($_POST['link_desc'], ENT_QUOTES)."', ";
					$sqlsel.="'".htmlspecialchars($filename, ENT_QUOTES)."', ";
					$sqlsel.="'".$subcategory."')";
					$sqlquery = $sqlsel;
		}
		break;
	}

//echo "INSERT".$sqlquery;
$result = mysql_query($sqlquery);
if ($result){
$success = 1;
$message = "Sponsor added";
}else{
$success = 0;
$message = "Sponsor could not be added";
}

//get a hanlde for the recoed taht has just been added
$new_article_id = mysql_insert_id();
$mode = "SUCCESS";
}

//use the table names to display the relavent insert forms as long as the mode variable is blank
if (!$mode){
	switch ($info_type){
		case "sponsors":
			$mode = "SPONSORS";
		break;
	}
}
?>

<?php
switch ($mode) {
	case "SUCCESS":
?>
<html>

<head>
<title>Add new row</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_category,js_subcategory,js_random) {
	opener.location.href = 'pick_sponsor.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_category,js_subcategory,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_sponsor.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
		refreshed ='yes';
	}
}


//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" onUnload="checkrefreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $info_type WHERE id=".$new_article_id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{

	switch ($info_type){
		case "links_introduction":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Paragraph: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo $row["paragraph"]; ?></font></td>
			</tr><tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
		<?php
		break;
	}
}
?>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a>&nbsp;&nbsp;</center></p>
</body>
</html>
<?php
break;
case "SPONSORS":

//get the latest paragraph number
$sqlsel="SELECT * FROM $info_type WHERE `section`='".$subcategory."'";
//echo $sqlsel;
$result = mysql_query($sqlsel);
$count = mysql_num_rows($result);
?>
<html>

<head>
<title>Add new sponsor</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400">Add new sponsor</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=sponsor" method="post" enctype="multipart/form-data">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">

<table cellpadding="3">
<?php
if ($count > 0){
?>
<tr>
        <td colspan="2"><font face="ms sans serif" size="3">Sponsor already exists</font></td>
    </tr>
<?php
}else{
?>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Url: </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url"></font></td>
    </tr>
	 <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea name="link_desc" cols="50" rows="10"></textarea></font></td>
    </tr>
	 <tr>
        <td><font face="Verdana,Arial" size="2"><b>File : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="file" name="file1" size="53"><input type="hidden" name="MAX_FILE_SIZE" value="50000"></font></td>
    </tr>
    <tr>
        <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new record to the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
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
}
?>
