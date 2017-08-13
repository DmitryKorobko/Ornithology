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

if ($_POST["update"]){
$action = $_POST["update"];
}else{
$action = $_GET["update"];
}

//perform article summary and heading update
if ($action =="yes"){
$SQLupdate="UPDATE $main SET ";
$SQLupdate.= "title = '" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
$SQLupdate.= "summary = '" . htmlspecialchars($_POST['summary'], ENT_QUOTES) . "', ";
$SQLupdate.= "desc_1 = '" . htmlspecialchars($_POST['desc_1'], ENT_QUOTES) . "', ";
$SQLupdate.= "desc_2 = '" . htmlspecialchars($_POST['desc_2'], ENT_QUOTES) . "', ";
$SQLupdate.= "desc_3 = '" . htmlspecialchars($_POST['desc_3'], ENT_QUOTES) . "', ";
$SQLupdate.= "rating = '" . htmlspecialchars($_POST['rating'], ENT_QUOTES) . "', ";
$SQLupdate.= "created = '" . htmlspecialchars($_POST['created'], ENT_QUOTES) . "' ";
$SQLupdate.= "WHERE article = '" . $article_id . "'";
//echo $SQLupdate;
	$result = mysql_query($SQLupdate);
	if (!$result)
	{
		$success = 1;
		$message = " - Article details could not be updated";
	}
	if ($success <> 1){
	$SQLupdate="UPDATE $status SET ";
	$SQLupdate.= "section = '" . htmlspecialchars($_POST['section'], ENT_QUOTES) . "' ";
	$SQLupdate.= "WHERE article = '" . $article_id . "'";
	//echo $SQLupdate;
	$result = mysql_query($SQLupdate);
		if (!$result)
		{
			$message = " - Article details could not be updated";
		}
	}
}

?>
<html>
<head>
<title>Pick News</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">
<!--

function popAddRow(js_dbtable,js_article,js_random) {
	popupdate = window.open('add_row_review1.php?dbtable=' + js_dbtable + '&article=' + js_article + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popUpdateRow(js_dbtable,js_article,js_id,js_random) {
	popupdate = window.open('add_row_review1.php?mode=EDIT&id=' + js_id + '&dbtable=' + js_dbtable + '&article=' + js_article + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteRow(js_dbtable,js_article,js_id,js_random) {
	popupdate = window.open('add_row_review1.php?mode=DELETE&id=' + js_id + '&dbtable=' + js_dbtable + '&article=' + js_article + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddImage(js_dbtable,js_article,js_random) {
	popupdate = window.open('add_image_review1.php?dbtable=' + js_dbtable + '&article=' + js_article + '&random=' + js_random,'popupdate','width=590,height=425,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteImage(js_dbtable,js_article,js_random) {
	popupdate = window.open('add_image_review1.php?dbtable=' + js_dbtable + '&article=' + js_article + '&random=' + js_random + '&action=delete','popupdate','width=590,height=425,left=50,top=50,status=yes,scrollbars=yes');
}

//-->
</script>

<style type="text/css">
  <!--
    p            { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; }
    td           { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #000000; }
    th           { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; text-align:left; font-weight:normal}
    .title       { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #999999; }
    a:hover      { color: #FF0000; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    a:link       { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    a:visited    { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    .major_title { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16pt; font-weight: bold; color: #000000; }
    textarea     {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE; border-width: thin thin thin thin; border: thin #EEEEEE solid}
    input        { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE; border-width: thin thin thin thin; border: thin #EEEEEE solid}
    select       { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE; border-width: thin thin thin thin; border: thin #EEEEEE solid}
  -->
</style>

</head>

<body>
<?php
	$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article ";
	$select .= "AND $main.article = '" . $article_id . "'";

	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
<p class="major_title"><?php echo $row["title"] . $message; ?></p>
<form name="news" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?article=<?php echo $article_id; ?>&update=yes">
<table border="0" cellspacing="1" cellpadding="0">
    <tr>
      <td align="right" class="title" width="100">Title:</td>
      <td align="right">&nbsp;</td>
      <td><input type="text" style="font-weight: bold" name="title" size="80" maxlength="80" value="<?php echo $row["title"]; ?>">
      </td>
    </tr>
    <tr>
      <td valign="top" align="right" class="title">Summary:<br><small>(for homepage)</small></td>
      <td valign="top" align="right">&nbsp;</td>
      <td><textarea name="summary" cols="80" rows="5" wrap="VIRTUAL"><?php echo $row["summary"]; ?></textarea>
      </td>
    </tr>
    <tr>
      <td align="right" class="title">Heading 1:</td>
      <td align="right">&nbsp;</td>
      <td><input type="text" name="desc_1" size="80" value="<?php echo $row["desc_1"]; ?>" maxlength="80">
      </td>
    </tr>
    <tr>
      <td align="right" class="title">Heading 2:</td>
      <td align="right">&nbsp;</td>
      <td><input type="text" name="desc_2" style="font-style: italic" size="80" value="<?php echo $row["desc_2"]; ?>" maxlength="80"></td>
    </tr>
    <tr>
      <td align="right" class="title">Heading 3:</td>
      <td align="right">&nbsp;</td>
      <td><input type="text" name="desc_3" size="80" value="<?php echo $row["desc_3"]; ?>" maxlength="80"></td>
    </tr>
	<?php
	switch ($row["rating"]){
	case 0.5:
		$selected_1 = "selected";
	break;
	case 1:
		$selected_2 = "selected";
	break;
	case 1.5:
		$selected_3 = "selected";
	break;
	case 2:
		$selected_4 = "selected";
	break;
	case 2.5:
		$selected_5 = "selected";
	break;
	case 3:
		$selected_6 = "selected";
	break;
	case 3.5:
		$selected_7 = "selected";
	break;
	case 4:
		$selected_8 = "selected";
	break;
	case 4.5:
		$selected_9 = "selected";
	break;
	case 5:
		$selected_10 = "selected";
	break;
	}
	?>
	<tr>
    <td align="right" class="title">Rating:</td>
    <td align="right">&nbsp;</td>
    <td><select name="rating">
        <option value="0.5" <?php echo $selected_1; ?>>0.5</option>
        <option value="1" <?php echo $selected_2; ?>>1</option>
        <option value="1.5" <?php echo $selected_3; ?>>1.5</option>
        <option value="2" <?php echo $selected_4; ?>>2</option>
        <option value="2.5" <?php echo $selected_5; ?>>2.5</option>
        <option value="3" <?php echo $selected_6; ?>>3</option>
        <option value="3.5" <?php echo $selected_7; ?>>3.5</option>
        <option value="4" <?php echo $selected_8; ?>>4</option>
        <option value="4.5" <?php echo $selected_9; ?>>4.5</option>
        <option value="5" <?php echo $selected_10; ?>>5</option>
        </select></td>
    </tr>
		<?php
	switch ($row["section"]){
	case "books":
		$section_1 = "selected";
	break;
	case "audio":
		$section_2 = "selected";
	break;
	case "cd_rom":
		$section_3 = "selected";
	break;
	case "dvd":
		$section_4 = "selected";
	break;
	case "software":
		$section_5 = "selected";
	break;
	case "binoculars":
		$section_6 = "selected";
	break;
	case "telescopes":
		$section_7 = "selected";
	break;
	case "cameras":
		$section_8 = "selected";
	break;
	case "other":
		$section_9 = "selected";
	break;
	}
	?>
	<tr>
    <td align="right" class="title">Section:</td>
    <td align="right">&nbsp;</td>
    <td>
	<select name="section">
	  <option value="Pick a section">Pick a section</option>
        <option value="books" <?php echo $section_1; ?>>Books</option>
		<option value="audio" <?php echo $section_2; ?>>Audio CD</option>
		<option value="cd_rom" <?php echo $section_3; ?>>CD-ROM</option>
		<option value="dvd" <?php echo $section_4; ?>>DVD</option>
		<option value="software" <?php echo $section_5; ?>>Software</option>
		<option value="binoculars" <?php echo $section_6; ?>>Binoculars</option>
		<option value="telescopes" <?php echo $section_7; ?>>Telescopes</option>
		<option value="cameras" <?php echo $section_8; ?>>Cameras</option>
		<option value="other" <?php echo $section_9; ?>>Outdoor Wear and Other Equipment</option
    ></select>
	</td>
    </tr>
    <tr>
      <td align="right" class="title">Created:</td>
      <td align="right">&nbsp;</td>
      <td><input type="text" name="created" size="30" maxlength="30" value="<?php echo $row["created"]; ?>"> Set to today's date <input type="checkbox" name="todays_date" value="yes" style="border:none"></td>
    </tr>
    <tr>
      <td align="right" class="title">State:</td>
      <td align="right">&nbsp;</td>
      <td><font size="2" ><?php echo $row["state"]; ?></font><input type="hidden" name="state" value="<?php echo $row["state"]; ?>"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><br><input type="submit" style="border-color: #FFFFFF #808080 #808080 #FFFFFF; border-width: thin thin thin thin" value="Save title/summary modifications"></td>
  </tr>
 <tr>
    <td colspan="3">&nbsp;</td>
 </tr>
<?php if ($row["image"] != '0'){ ?>
<tr>
    <td align="right" valign="top" class="title" width="100">Image:</td>
	<td>&nbsp;</td>
    <td><br>&nbsp;<a href="javascript:popAddImage('<?php echo $main; ?>','<?php echo $article_id; ?>','1114460409');" onMouseOver="window.status='Change the image that is associated with this article';return true" onMouseOut="window.status='';return true">
	<img src="<?php echo "../../images_reviews/".$row["image"]; ?>" border="0" alt="Click here to replace this image with another"></a><br>
	<a href="javascript:popDeleteImage('<?php echo $main; ?>','<?php echo $article_id; ?>','1114460409');" onMouseOver="window.status='Remove the image that is associated with this article';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[remove above image]</font></a>

	</td>  </tr>
<?php }else{ ?>
	<tr>
    <td align="right" valign="top" class="title" width="100">Image:</td>
	<td>&nbsp;</td>
    <td><img src="../images/warning.gif" width="32" height="32" align="left">&nbsp;There is currently no image assocated with this article<br>&nbsp;<a href="javascript:popAddImage('<?php echo $main; ?>','<?php echo $article_id; ?>','1114460409');" onMouseOver="window.status='Associate an image with this article';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[click here pick one]</font></a></td>  </tr>
<?php }?>
</table>
</form>

<?php
		 }
	 }
?>


<form name="news_text" method="post" action="">
<table border="0" cellspacing="1" cellpadding="0" width="580">
<?php
	$select = "SELECT * FROM $text WHERE article = '" . $article_id . "' ORDER BY paragraph";

	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<tr>
      <td align="right" valign="top" class="title" width="100">Paragraph <?php echo $row["paragraph"]; ?>:</td>
      <th><?php echo html_entity_decode($row["text"]); ?>
	  <br><small><a href="javascript:popAddRow('<?php echo $text; ?>','<?php echo $article_id; ?>','1114434837');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;<a href="javascript:popUpdateRow('<?php echo $text; ?>','<?php echo $article_id; ?>','<?php echo $row["id"]; ?>','1114434837');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;<a href="javascript:popDeleteRow('<?php echo $text; ?>','<?php echo $article_id; ?>','<?php echo $row["id"]; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a><br>&nbsp;</small></th>
   </tr>
<?php
		 }

	}else{
?>
      <tr>
      <td align="right" valign="top" class="title" width="100">Paragraph:</td>
      <td>&nbsp;<a href="javascript:popAddRow('<?php echo $text; ?>','<?php echo $article_id; ?>','1114460409');" onMouseOver="window.status='Add a new paragraph to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a></td>
   </tr>
<?php
	}
?>
</table>
</form>
</body>
</html>

