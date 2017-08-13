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

if ($_GET['mode'] <> ""){
$mode = $_GET['mode'];
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
		case "intro":
		//run a set of queries to update the paragraph order is needed
		$order_sql="SELECT * FROM $info_type WHERE id =".$id;
		$order_result = mysql_query($order_sql);
		$order_row = mysql_fetch_assoc($order_result);

		$order_id = $order_row["paragraph"];
		$nu_order_id = encode($_POST['paragraph']);
		if ($order_id > $nu_order_id ) // order higher
		{
			$update_sql = "UPDATE $info_type SET paragraph = (paragraph + 1) WHERE paragraph >= $nu_order_id AND paragraph < $order_id AND `category`='".$category."' AND `sub-category`='".$subcategory."'";
			//echo "<BR>$update_sql";
			$update_result = mysql_query($update_sql);

			if ($update_result)
			{
				$update_order = "UPDATE $info_type SET paragraph = $nu_order_id WHERE id = $id";
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
			$update_sql = "UPDATE $info_type SET paragraph = (paragraph - 1) WHERE paragraph > $order_id AND paragraph <= $nu_order_id AND id = $id AND `category`='".$category."' AND `sub-category`='".$subcategory."'";
			//echo "<BR>$update_sql";
			$update_result = mysql_query($update_sql);

			if ($update_result)
			{
				$update_order = "UPDATE $info_type SET paragraph = $nu_order_id WHERE id = $id";
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
		//echo $message;
		//do sql insert
		$sqlsel="UPDATE $info_type SET ";
		//$sqlsel.="paragraph = '".encode($_POST['paragraph'])."', ";
		$sqlsel.="text = '".encode($_POST['text'])."'";
		break;
	}


$sqlsel.=" WHERE id =".$id;
$sqlquery = $sqlsel;
//echo $sqlquery;
$result = mysql_query($sqlquery);
if ($result){
$success = 1;
$message = "Article updated";
}else{
$success = 0;
$message = "Article could not be updated";
}

//only perform the page create functions for a relevant categories
switch ($subcategory){
 case "default_intro":
 case "default_home":
 	//take no action for the above categories
	$success=0;
 break;
}
//create new HTML file based on database for the given section
if ($success==1){
$tmpl_dir = "";
$tmpl_name = "template_about.htm";

//create the correct output dir and html file name based on subcategory and category
list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
$html_dir = "../../$alt_dir";
$html_name = $alt_file;

//set the root folder to chmod 777
$chmod_file = "/home/www/fatbirder/".$alt_dir;
ftp_chmod(chmod_ip, chmod_login, chmod_pass, $chmod_file, "0777");

echo $html_dir;
echo $html_name;
$sql_cat = $category;
$sql_subcat = $subcategory;
$table = $info_type;
global $alt_dir;
global $alt_file;
//echo getcontent($table, $category, $subcategory);
echo createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat);

//set the root folder to chmod 755
$chmod_file = "/home/www/fatbirder/".$alt_dir;
ftp_chmod(chmod_ip, chmod_login, chmod_pass, $chmod_file, "0755");

}

//get a hanlde for the recoed taht has just been added
$new_article_id = mysql_insert_id();
$mode = "SUCCESS";
}

//use the table names to display the relavent insert forms as long as the mode variable is blank
if (!$mode){
	switch ($info_type){
		case "links":
		case "links_artists_photographers":
		case "links_festivals":
		case "links_museums":
		case "links_observatories":
		case "links_organisations":
		case "links_places_to_stay":
		case "links_reserves":
		case "links_trip_reports":
		case "links_holiday_companies":
			$mode = "LINKS";
		break;
		case "links_endemics":
		case "links_number_species":
			$mode = "SPECIES";
		break;
		case "links_introduction":
			$mode = "INTRO";
		break;
		case "links_contributor":
			$mode = "CONTRIBUTOR";
		break;
		case "links_county_recorder":
			$mode = "RECORDER";
		break;
		case "links_mailing_lists":
			$mode = "MAILING";
		break;
		case "links_top_sites":
			$mode = "TOPSITES";
		break;
		case "links_useful_reading":
			$mode = "USEFUL";
		break;
		case "links_useful_information":
			$mode = "USEFUL_INFO";
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

<?php
switch ($subcategory){
 case "default_intro":
 case "default_home":
 case "default_about":
?>
function refreshPickRecord(js_category,js_subcategory,js_random) {
	opener.location.href = 'pick_default.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_category,js_subcategory,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_default.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
		refreshed ='yes';
	}
}
<?php
break;
default:
?>
function refreshPickRecord(js_category,js_subcategory,js_random) {
	opener.location.href = 'pick_record.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_category,js_subcategory,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_record.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
		refreshed ='yes';
	}
}
<?php
}
?>

//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" onUnload="checkrefreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $info_type WHERE id=".$id;
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
			<td><font face="Verdana,Arial" size="1"><?php echo decode($row["text"]); ?></font></td>
			</tr>
		<?php
		break;
	}
}
?>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>
<?php
break;
case "INTRO":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=intro" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table cellpadding="3">
<?php
//get a summary of the recoed that has just been added
$sqlsel="SELECT * FROM $info_type WHERE id=".$id;
//echo $sqlsel;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
?>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Paragraph : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="5" name="paragraph" value="<?php echo $row["paragraph"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="53" rows="9" name="text"><?php echo $row["text"]; ?></textarea></font></td>
    </tr>
<?php
}
?>
    <tr>
        <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new record to the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
}
?>
