<?php

//echo shell_exec("pwd");
//echo shell_exec("chmod -R 755 /home/firecast/public_html/fatbirder/");

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

if ($_POST["ref_url"]){
$ref_url = $_POST["ref_url"];
}else{
$ref_url = $_GET["ref_url"];
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
		$nu_order_id = htmlspecialchars($_POST['paragraph'], ENT_QUOTES);
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
			echo "<BR>$update_sql";
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
		echo $message;
		//do sql insert
		$sqlsel="UPDATE $info_type SET ";
		//$sqlsel.="paragraph = '".htmlspecialchars($_POST['paragraph'], ENT_QUOTES)."', ";
		$sqlsel.="text = '".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
		break;

		case "sublinks":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="sublinks ='".htmlspecialchars($_POST['sublinks'], ENT_QUOTES)."'";
		break;

		case "contributor":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="name ='".htmlspecialchars($_POST['name'], ENT_QUOTES)."', ";
		$sqlsel.="title ='".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="location ='".htmlspecialchars($_POST['location'], ENT_QUOTES)."', ";
		$sqlsel.="email ='".htmlspecialchars($_POST['email'], ENT_QUOTES)."', ";
		$sqlsel.="url ='".htmlspecialchars($_POST['url'], ENT_QUOTES)."'";
		break;

		case "recorder":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="name ='".htmlspecialchars($_POST['name'], ENT_QUOTES)."', ";
		$sqlsel.="address ='".htmlspecialchars($_POST['address'], ENT_QUOTES)."', ";
		$sqlsel.="telephone ='".htmlspecialchars($_POST['telephone'], ENT_QUOTES)."', ";
		$sqlsel.="fax ='".htmlspecialchars($_POST['fax'], ENT_QUOTES)."', ";
		$sqlsel.="email ='".htmlspecialchars($_POST['email'], ENT_QUOTES)."'";
		break;

		case "species":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title ='".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="text ='".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
		break;

		case "links":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title ='".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="url ='".htmlspecialchars($_POST['url'], ENT_QUOTES)."', ";
		$sqlsel.="link_desc ='".htmlspecialchars($_POST['link_desc'], ENT_QUOTES)."'";
		break;

		case "links2":
			$sqlsel="UPDATE $info_type SET ";
			$sqlsel.="title = '".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
			$sqlsel.="url1 = '".htmlspecialchars($_POST['url1'], ENT_QUOTES)."', ";
			$sqlsel.="url1_title = '".htmlspecialchars($_POST['url1_title'], ENT_QUOTES)."', ";
			$sqlsel.="url2 = '".htmlspecialchars($_POST['url2'], ENT_QUOTES)."', ";
			$sqlsel.="url2_title = '".htmlspecialchars($_POST['url2_title'], ENT_QUOTES)."', ";
			$sqlsel.="text = '".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
			break;

		case "mailing":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title ='".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="url ='".htmlspecialchars($_POST['url'], ENT_QUOTES)."', ";
		$sqlsel.="post_email ='".htmlspecialchars($_POST['post_email'], ENT_QUOTES)."', ";
		$sqlsel.="contact ='".htmlspecialchars($_POST['contact'], ENT_QUOTES)."', ";
		$sqlsel.="sub_email ='".htmlspecialchars($_POST['sub_email'], ENT_QUOTES)."', ";
		$sqlsel.="unsub_email ='".htmlspecialchars($_POST['unsub_email'], ENT_QUOTES)."', ";
		$sqlsel.="sub_message ='".htmlspecialchars($_POST['sub_message'], ENT_QUOTES)."', ";
		$sqlsel.="body_message ='".htmlspecialchars($_POST['body_message'], ENT_QUOTES)."', ";
		$sqlsel.="text ='".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
		break;

		case "topsites":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title = '".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="grid_reference = '".htmlspecialchars($_POST['grid_reference'], ENT_QUOTES)."', ";
		$sqlsel.="text = '".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
		break;

		case "useful":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title = '".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="text = '".htmlspecialchars($_POST['text'], ENT_QUOTES)."', ";
		$sqlsel.="isbn = '".htmlspecialchars($_POST['isbn'], ENT_QUOTES)."'";
		break;

		case "useful_info":
		$sqlsel="UPDATE $info_type SET ";
		$sqlsel.="title = '".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
		$sqlsel.="text = '".htmlspecialchars($_POST['text'], ENT_QUOTES)."'";
		break;
	}


$sqlsel.=" WHERE id =".$id;
echo $sqlsel;
$sqlquery = $sqlsel;
//echo $sqlquery;


$result = mysql_query($sqlquery);
//echo "<br>".mysql_error()."<br>";
if ($result){
$success = 1;
$message = "Article updated";
}else{
$success = 0;
$message = "Article could not be updated";
}

//create new HTML file based on database for the given section
if ($success==1){
$tmpl_dir = "";
$tmpl_name = "template.htm";


//create the correct output dir and html file name based on subcategory and category
list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
$html_dir = dir_const.$alt_dir;
$html_name = $alt_file;

//set the root folder to chmod 777
$chmodf = chmod_file.$alt_dir;
ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");
//echo $html_dir;
//echo $html_name;
$sql_cat = $category;
$sql_subcat = $subcategory;
$table = $info_type;
global $alt_dir;
global $alt_file;
//echo getcontent($table, $category, $subcategory);
createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat);

//set the root folder to chmod 755
$chmodf = chmod_file.$alt_dir;
ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");
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
		case "links_organisations":
		case "links_places_to_stay":
		case "links_trip_reports":
		case "links_holiday_companies":
		case "links_family_links":
		case "links_species_links":
			$mode = "LINKS";
		break;
		case "links_top_sites":
		case "links_observatories":
		case "links_reserves":
			$mode = "LINKS2";
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
		case "links_useful_reading":
			$mode = "USEFUL";
		break;
		case "links_useful_information":
			$mode = "USEFUL_INFO";
		break;
		case "sublinks":
			$mode = "SUBLINKS";
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
<title>Update Record</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_category,js_subcategory,js_random) {
	opener.location.href = '<?php echo $ref_url; ?>?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_category,js_subcategory,js_random) {
	if (refreshed == 'no') {
		opener.location.href = '<?php echo $ref_url; ?>?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
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
$sqlsel="SELECT * FROM $info_type WHERE id=".$id;
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{

	switch ($info_type){
		case "links":
		case "links_artists_photographers":
		case "links_festivals":
		case "links_museums":
		case "links_organisations":
		case "links_places_to_stay":
		case "links_trip_reports":
		case "links_holiday_companies":
		case "links_family_links":
		case "links_species_links":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["link_desc"]); ?></font></td>
			</tr>
		<?php
		break;
		case "links_endemics":
		case "links_number_species":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>No. species : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
			</tr><tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
		<?php
		break;
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
		case "links_contributor":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Name: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["name"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Title: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Location: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["location"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Email: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["email"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Url: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url"]); ?></font></td>
			</tr>
		<?php
		break;
		case "links_county_recorder":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Name: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["name"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Address: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["address"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Telephone: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["telephone"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Fax: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["fax"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Email: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["email"]); ?></font></td>
			</tr>
		<?php
		break;
		case "links_mailing_lists":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Post: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["post_email"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Contact: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["contact"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Subscribe : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["sub_email"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Unsubscribe : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["unsub_email"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Subject : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["sub_message"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Body : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["body_message"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
		<?php
		break;
		case "links_top_sites":
		case "links_observatories":
		case "links_reserves":
		?>
			
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
			</tr>
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>URL 1 Title: </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1"]); ?></font></td>
			</tr>
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>URL 1 Title: </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1_title"]); ?></font></td>
			</tr>
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>URL 2 : </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url2"]); ?></font></td>
			</tr>
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>URL 2 Title: </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url2_title"]); ?></font></td>
			</tr>
			<tr>
				<td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
				<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
			
		<?php
		break;
		case "links_useful_reading":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>ISBN: </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["isbn"]); ?></font></td>
			</tr>
		<?php
		break;
		case "links_useful_information":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font></td>
			</tr>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font></td>
			</tr>
		<?php
		break;
		case "sublinks":
		?>
			<tr>
			<td valign="top"><font face="Verdana,Arial" size="1"><b>Sub Links : </b></font></td>
			<td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["sublinks"]); ?></font></td>
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
case "LINKS":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=links" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url" value="<?php echo $row["url"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="58" rows="9" name="link_desc"><?php echo $row["link_desc"]; ?></textarea></font></td>
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
	case "LINKS2":
?>
		<html>
		<head>
		<title>Update row</title>
		</head>
		<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
		<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
		<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=links2" method="post">
			<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
			<input type="hidden" name="category" value="<?php echo $category; ?>">
			<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
			<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
					<td><font face="ms sans serif" size="3">
						<input type="text" size="67" name="title" value="<?php echo $row["title"]; ?>">
						</font></td>
				</tr>
				<tr>
					<td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
					<td><font face="ms sans serif" size="3">
						<input type="text" size="67" name="url1" value="<?php echo $row["url1"]; ?>">
						</font></td>
				</tr>
				<tr>
					<td><font face="Verdana,Arial" size="2"><b>URL Title: </b></font></td>
					<td><font face="ms sans serif" size="3">
						<input type="text" size="67" name="url1_title" value="<?php echo $row["url1_title"]; ?>">
						</font></td>
				</tr>
				<tr>
					<td><font face="Verdana,Arial" size="2"><b>URL 2 : </b></font></td>
					<td><font face="ms sans serif" size="3">
						<input type="text" size="67" name="url2" value="<?php echo $row["url2"]; ?>">
						</font></td>
				</tr>
				<tr>
					<td><font face="Verdana,Arial" size="2"><b>URL 2 Title: </b></font></td>
					<td><font face="ms sans serif" size="3">
						<input type="text" size="67" name="url2_title" value="<?php echo $row["url2_title"]; ?>">
						</font></td>
				</tr>
				<tr>
					<td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
					<td><font face="ms sans serif" size="3">
						<textarea cols="58" rows="9" name="text"><?php echo $row["text"]; ?></textarea>
						</font></td>
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
case "SPECIES":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=species" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="60" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="52" rows="10" name="text"><?php echo $row["text"]; ?></textarea></font></td>
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
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
case "CONTRIBUTOR":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F" onLoad="document.add_row.name.focus();">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=contributor" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="63" name="name" value="<?php echo $row["name"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Location : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="location" value="<?php echo $row["location"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="email" value="<?php echo $row["email"]; ?>"></font></td>
    </tr>
	<tr>
        <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url" value="<?php echo $row["url"]; ?>"></font></td>
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
case "RECORDER":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=recorder" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="64" name="name" value="<?php echo $row["name"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Address : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="64" name="address" value="<?php echo $row["address"]; ?>"></font></td>
    </tr>
    <tr>
        <td nowrap><font face="Verdana,Arial" size="2"><b>Telephone: </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="64" name="telephone" value="<?php echo $row["telephone"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Fax : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="64" name="fax" value="<?php echo $row["fax"]; ?>"></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="64" name="email" value="<?php echo $row["email"]; ?>"></font></td>
    </tr>
<?php
}
?>
<tr>
        <td colspan="2"align="right">
		<a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new record to the current page';return true" onMouseOut="window.status='';return true">
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
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=mailing" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="title" value="<?php echo $row["title"]; ?>"></font></td>
      <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="url" value="<?php echo $row["url"]; ?>"></font></td>
    </tr>
    <tr>
      <td><font face="Verdana,Arial" size="2"><b>Post: </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="post_email" value="<?php echo $row["post_email"]; ?>"></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Contact: </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="contact" value="<?php echo $row["contact"]; ?>"></font></td>
    </tr>
    <tr>
      <td><font face="Verdana,Arial" size="2"><b>Subscribe : </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="sub_email" value="<?php echo $row["sub_email"]; ?>"></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Unsubscribe : </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="unsub_email" value="<?php echo $row["unsub_email"]; ?>"></font></td>
    </tr>
    <tr>
      <td nowrap><font face="Verdana,Arial" size="2"><b>Subject : </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="sub_message" value="<?php echo $row["sub_message"]; ?>"></font></td>
      <td><font face="Verdana,Arial" size="2"><b>Body : </b></font></td>
      <td><font face="ms sans serif" size="3"><input type="text" size="24" name="body_message" value="<?php echo $row["body_message"]; ?>"></font></td>
    </tr>
    <tr>
      <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
      <td colspan="3"><font face="ms sans serif" size="3"><textarea cols="54" rows="5" name="text"><?php echo $row["text"]; ?></textarea></font></td>
    </tr>
<?php
}
?>
    <tr>
<td colspan="4"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new record to the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
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
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=topsites" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td nowrap><font face="Verdana,Arial" size="2"><b>Grid Ref : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="grid_reference" value="<?php echo $row["grid_reference"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="55" rows="8" name="text"><?php echo $row["text"]; ?></textarea></font></td>
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
case "USEFUL":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=useful" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="57" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text : </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="45" rows="8" name="text"><?php echo $row["text"]; ?></textarea></font></td>
    </tr>
    <tr>
        <td><font face="Verdana,Arial" size="2"><b>ISBN : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="text" size="57" name="isbn" value="<?php echo $row["isbn"]; ?>"></font></td>
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
case "USEFUL_INFO":
?>
<html>
<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Update record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=useful_info" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
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
        <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title" value="<?php echo $row["title"]; ?>"></font></td>
    </tr>
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="58" rows="10" name="text"><?php echo $row["text"]; ?></textarea></font></td>
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
case "SUBLINKS":
?>
<html>

<head>
<title>Update Record</title>
</head>

<body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
<p><font face="Verdana,Arial" size="5" color="#FAD400">Update Record</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php echo $info_type; ?>" method="post">
<input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type;?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">

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
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="58" rows="10" name="sublinks"><?php echo $row["sublinks"]; ?></textarea></font></td>
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
