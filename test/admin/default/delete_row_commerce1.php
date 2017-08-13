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
		case "deleteintro":
		//do sql delete and paragraph reordering
		$sqlsel="DELETE FROM $info_type WHERE id =".$id;
		$sqlquery = $sqlsel;
		//echo $sqlquery;
		$result = mysql_query($sqlquery);
			if($result){
			$message = "Paragraph was deleted";
			$deleted = 1;
			}else{
			$message = "Paragraph was not deleted";
			}
			//only run the category update if the previous article has been deleted
			if ($deleted == 1){
				//update the paragraph order for each article so that correct numbering is maintained
				$update_sql = "UPDATE $info_type SET paragraph = (paragraph - 1) WHERE paragraph > ".$paragraph." AND `category`='".$category."' AND `sub-category`='".$subcategory."'";
				//echo $update_sql."<br>";
				$update_result = mysql_query($update_sql);
				if($update_result){
				$success = 1;
				$message .= "<br>Paragraph order was updated";
				}else{
				$success = 0;
				$message .= "<br>Paragraph order was not updated";
				}
			}
		$mode = "SUCCESS";
		break;
		case "delete":
		//do sql delete
		$sqlsel="DELETE FROM $info_type";
		$sqlsel.=" WHERE id =".$id;
		$sqlquery = $sqlsel;
		//echo $sqlquery;
		$result = mysql_query($sqlquery);
		if ($result){
		$success = 1;
		$message = "Record deleted";
		}else{
		$success = 0;
		$message = "Record could not be deleted";
		}
		$mode = "SUCCESS";
		break;
	}
//create new HTML file based on database for the given section
if ($success==1){
$tmpl_dir = "";
$tmpl_name = "template.htm";

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
<title>Delete record</title>

<script language="JavaScript">
<!--

refreshed = 'no';

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


//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" onUnload="checkrefreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');" background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>
<?php
break;
case "LINKS":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
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
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["title"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["url"]); ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["link_desc"]); ?></font></td>
    </tr>
<?php
}
?>
    <tr>
<td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "SPECIES":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
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
        <td nowrap><font face="Verdana,Arial" size="2"><b>No. species : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["title"]); ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["text"]); ?></font></td>
    </tr>
<?php
}
?>
    <tr>
<td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "INTRO":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=deleteintro" method="post">
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
<input type="hidden" name="paragraph" value="<?php echo $row["paragraph"];  ?>">
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Paragraph : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["paragraph"]; ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["text"]); ?></font></td>
    </tr>
<?php
}
?>
    <tr>
        <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "CONTRIBUTOR":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
        <td><font face="Verdana,Arial" size="2"><b>Name : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["name"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["title"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Location : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["location"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["email"]); ?></font></td>
    </tr>
	<tr>
        <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["url"]); ?></font></td>
    </tr>
<?php
}
?>
    <tr>
       <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "RECORDER":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
        <td><font face="Verdana,Arial" size="2"><b>Name : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["name"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Address : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["address"]); ?></font></td>
    </tr>
    <tr>
        <td nowrap><font face="Verdana,Arial" size="2"><b>Telephone: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["telephone"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Fax : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["fax"]); ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo decode($row["email"]); ?></font></td>
    </tr>
<?php
}
?>
<tr>
        <td colspan="2"align="right">
		<a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true">
		<img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;
		<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true">
		<img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a>
		</td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "MAILING":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
      <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["title"]); ?></font></td>
      <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["url"]); ?></font></td>
    </tr>
    <tr>
      <td><font face="Verdana,Arial" size="2"><b>Post: </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["post_email"]); ?></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Contact: </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["contact"]); ?></font></td>
    </tr>
    <tr>
      <td><font face="Verdana,Arial" size="2"><b>Subscribe : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["sub_email"]); ?></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Unsubscribe : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["unsub_email"]); ?></font></td>
    </tr>
    <tr>
      <td nowrap><font face="Verdana,Arial" size="2"><b>Subject : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["sub_message"]); ?></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Body : </b></font></td>
      <td><font face="ms sans serif" size="3"><?php echo decode($row["body_message"]); ?></font></td>
    </tr>
    <tr>
      <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
      <td colspan="3"><font face="ms sans serif" size="3"><?php echo decode($row["text"]); ?></font></td>
    </tr>
<?php
}
?>
    <tr>
<td colspan="4"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "TOPSITES":
?>
<html>

<head>
<title>Delete Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["title"]; ?></font></td>
    </tr>
    <tr>
        <td nowrap><font face="Verdana,Arial" size="2"><b>Grid Ref : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["grid_reference"]; ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["text"]; ?></font></td>
    </tr>
<?php
}
?>
    <tr>
  <td colspan="4"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "USEFUL":
?>
<html>

<head>
<title>Delete row</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["title"]; ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["text"]; ?></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>ISBN : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["isbn"]; ?></font></td>
    </tr>
<?php
}
?>
    <tr>
    <td colspan="4"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
case "USEFUL_INFO":
?>
<html>
<head>
<title>Delete row</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
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
        <td nowrap><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["title"]; ?></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><?php echo $row["text"]; ?></font></td>
    </tr>
<?php
}
?>
    <tr>
      <td colspan="4"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Delete this record from the current page';return true" onMouseOut="window.status='';return true"><img src="../../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
break;
}
?>
