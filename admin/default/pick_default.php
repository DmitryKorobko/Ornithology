<?php
include("../../includes/config.inc.php");
connect_db();

//set table name variables
$photo = "photos";
$intro = "links_introduction";
$contributor = "links_contributor";
$county = "links_county_recorder";
$species = "links_number_species";
$endemics = "links_endemics";
$festivals = "links_festivals";
$mailing = "links_mailing_lists";
$museums = "links_museums";
$observatories = "links_observatories";
$organisations = "links_organisations";
$places = "links_places_to_stay";
$links = "links";
$artists = "links_artists_photographers";
//table names still to be confirmed
$topsites = "links_top_sites";
$useful = "links_useful_reading";
$useful_info = "links_useful_information";
$reserves = "links_reserves";
$trips = "links_trip_reports";
$holiday = "links_holiday_companies";

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
?>
<html>
<head>
<title>Pick Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">
<!--

function popUpdateRow(js_info_type,js_id,js_category,js_subcategory,js_random) {
	popupdate = window.open('update_row_commerce1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteRow(js_info_type,js_id,js_category,js_subcategory,js_random) {
	popupdate = window.open('delete_row_commerce1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddRow(js_info_type,js_category,js_subcategory,js_random) {
	popupdate = window.open('add_row_commerce1.php?category=' + js_category + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popupMap(GridRef) {
	window.open('http://www.multimap.com/os/places.cgi?db=grid&place=' + GridRef + '&scale=50000','popupMap','width=826,height=552,screenX=50,screenY=50,top=50,left=50,location=no,menubar=no,resizable=yes,scrollbars=no,status=yes,toolbar=no');

}

function popAddBanner(js_category,js_subcategory,js_random) {
	popupdate = window.open('add_banner1.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteBanner(js_id,js_category,js_subcategory,js_random) {
	popupdate = window.open('delete_banner1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&random=' + js_random,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddPhoto(js_category,js_info_type,js_subcategory,js_random) {
	popupdate = window.open('add_image_commerce1.php?category=' + js_category + '&info_type=' + js_info_type + '&subcategory=' + js_subcategory + '&random=' + js_random,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeletePhoto(js_id,js_info_type,js_category,js_subcategory,js_random) {
	popupdate = window.open('delete_image_commerce1.php?info_type=' + js_info_type + '&category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&random=' + js_random,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

//-->
</script>

<style type="text/css">
  <!--
     p { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; }
    a:hover { color: #FF0000; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    a:link { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    a:visited { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
    .title { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14pt; color: #000000; }
    .major_title { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16pt; font-weight: bold; color: #000000; }
    .footer { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #000000; text-decoration: none }
  -->
</style>


</head>

<body>
<p align="center" class="footer"><a href="#introduction">Introduction</a> | <a href="#top_sites">Top Sites</a> | <a href="#useful_reading">Useful Reading</a> | <a href="#useful_information">Useful Information</a> | <a href="#county_recorder">County Recorder</a> | <a href="#contributor">Contributor</a> | <a href="#links" style="text-decoration:none">Links</a> | <a href="#places_to_stay" style="text-decoration:none">Places to stay</a></p>
<?php
$select = "SELECT `title` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
$result = mysql_query($select);
$pageTitle = "";
while ($row = mysql_fetch_assoc($result)) { $pageTitle .= $row['title']; }
?>
<p class="major_title"><?PHP echo $pageTitle; ?></p>
<p class="title"><a name="introduction">Introduction</a></p>
<?php
	$introd = new Introdisplay("", $category, $subcategory);
	echo $introd->getDisplaySite();
	echo $introd->getAdminOptions();
	/*$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $intro WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY paragraph";
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b>Paragraph:</b> <?php echo $row["paragraph"]; ?><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $intro; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $intro; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p><b>Paragraph:</b><br>
	  <a href="javascript:popAddRow('<?php echo $intro; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
<?php
	}*/
?>

</body>
</html>



