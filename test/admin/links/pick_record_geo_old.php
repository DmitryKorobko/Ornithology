<?php
include("../common/funct_lib.php");
connect_db();

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
$banners = "links_banners";
?>
<html>
<head>
<title>Pick Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">
<!--

function popUpdateRow(js_info_type,js_id,js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('update_row_commerce1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random +'&ref_url=' + ref_url,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteRow(js_info_type,js_id,js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('delete_row_commerce1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random +'&ref_url=' + ref_url,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddRow(js_info_type,js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('add_row_commerce1.php?category=' + js_category + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random +'&ref_url=' + ref_url,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popupMap(GridRef) {
	window.open('http://www.multimap.com/os/places.cgi?db=grid&place=' + GridRef + '&scale=50000','popupMap','width=826,height=552,screenX=50,screenY=50,top=50,left=50,location=no,menubar=no,resizable=yes,scrollbars=no,status=yes,toolbar=no');

}

function popAddBanner(js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('add_banner1.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random +'&ref_url=' + ref_url,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeleteBanner(js_id,js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('delete_banner1.php?category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&random=' + js_random +'&ref_url=' + ref_url,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddPhoto(js_category,js_info_type,js_subcategory,js_random, ref_url) {
	popupdate = window.open('add_image_commerce1.php?category=' + js_category + '&info_type=' + js_info_type + '&subcategory=' + js_subcategory + '&random=' + js_random +'&ref_url=' + ref_url,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popDeletePhoto(js_id,js_info_type,js_category,js_subcategory,js_random, ref_url) {
	popupdate = window.open('delete_image_commerce1.php?info_type=' + js_info_type + '&category=' + js_category + '&id=' + js_id + '&subcategory=' + js_subcategory + '&random=' + js_random +'&ref_url=' + ref_url,'popupBanner','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
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

<p class="major_title"><?PHP echo $subcategory; ?></p><p class="title"><a name="photo">Photo</a></p>
<p>
<?php
	$select = "SELECT * FROM $photo WHERE `country` = '$subcategory'";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
?>
	  <img src="../../photos/<?php echo $row["filename"]; ?>" width="<?php echo $row["width"]; ?>" height="<?php echo $row["height"]; ?>" border="0"><br>
	  <font face="verdana" size="1" color="#808080"><?php  echo auto_link( encode($row["text"]) ); ?></font><br>
	  <a href="javascript:popDeletePhoto('<?php echo $row["id"]; ?>','<?php echo $photo; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>
<?php
		 }

	}else{
?>
	  <a href="javascript:popAddPhoto('<?php echo $category; ?>','<?php echo $photo; ?>','<?php echo $subcategory; ?>','1115204618','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new photo to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a photo]</font></a><small> - invisible</small>
<?php
	}
?>
</p>
<hr size="1">
<p class="title"><a name="introduction">Introduction</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $intro WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY paragraph";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b>Paragraph:</b> <?php echo $row["paragraph"]; ?><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $intro; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $intro; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $intro; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p><b>Paragraph:</b><br>
	  <a href="javascript:popAddRow('<?php echo $intro; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="map">Map</a></p>
<?php
$target = "map_".$subcategory.".gif";
$directory = "../../images";
//substr_replace($_SERVER["SCRIPT_FILENAME"], '', strrpos($_SERVER["SCRIPT_FILENAME"], "/"));
//echo $target;
//echo $directory;
$map = getmap($target, $directory, 1, $subcategory);
echo $map;
$select = "SELECT * FROM map WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if (($count>0)||($map<>""))
	{
		while ($row = mysql_fetch_assoc($result))
		{
		echo "<p>".decode($row["imagemap"])."</p>";
		echo "<p>".decode($row["regionlist"])."</p>";
		}
?>
<a href="javascript:popDeletePhoto('','map','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>
<?php
	}else{
?>
 <a href="javascript:popAddPhoto('<?php echo $category; ?>','map','<?php echo $subcategory; ?>','1115204618','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new photo to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a map]</font></a>
 <?php
	}
 ?>
</form>
<hr size="1">
<p class="title"><a name="banner">Banner Ad</a></p>
<p>
<?php
	$select = "SELECT * FROM $banners WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<img src="<?php echo ROOT_URL; ?>/adverts/<?php echo $row["filename"]; ?>" width="<?php echo $row["width"]; ?>" height="<?php echo $row["height"]; ?>" border="0"><br>
    <font face="verdana" size="1" color="#808080"><?php  echo auto_link( encode($row["url"]) ); ?></font><br>
	<font face="verdana" size="1" color="#808080"><?php  echo auto_link( encode($row["sponsor"]) ); ?></font><br>
	<font face="verdana" size="1" color="#808080"><?php  echo auto_link( encode($row["text"]) ); ?></font><br>
	<a href="javascript:popDeleteBanner('<?php echo $row["id"]; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a></p>
	<?php
		 }

	}
		//display the add link if their are less than two image present
	if ($count<3){
?>
	<a href="javascript:popAddBanner('<?php echo $category; ?>','<?php echo $subcategory; ?>','1115289169','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a banner]</font></a><small> - invisible</small></p>
<?php
	}

?>
</p>
<hr size="1">
<p class="title"><a name="top_sites">Top sites</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $topsites WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b> <?php echo auto_link($row["title"]); ?> </b>
      <?php echo auto_link($row["url1"]); ?><br>
      <?php echo auto_link($row["url1_title"]); ?><br>
      <?php echo auto_link($row["url2"]); ?><br>
      <?php echo auto_link($row["url2_title"]); ?><br>
	  <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $topsites; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $topsites; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $topsites; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $topsites; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">

<p class="title"><a name="contributor">Contributor</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $contributor WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b> <?php echo auto_link($row["title"]); ?> </b><br>
      <?php echo $row["name"]; ?><br>
	  <?php echo auto_link($row["location"]); ?><br>
	  <?php echo auto_link($row["email"]); ?><br>
	  <?php echo auto_link($row["url"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $contributor; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $contributor; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $contributor; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $contributor; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="county_recorder">County recorder</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $county WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY name";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p>
	  <?php echo $row["name"]; ?><br>
      <?php echo auto_link($row["address"]); ?><br>
	  <?php echo auto_link($row["telephone"]); ?><br>
	  <?php echo auto_link($row["fax"]); ?><br>
	  <a href="mailto:<?php echo auto_link($row["email"]); ?>"><?php echo auto_link($row["email"]); ?></a><br>
	  <a href="javascript:popAddRow('<?php echo $county; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $county; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $county; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $county; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="number_species">Number of species</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $species WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $species; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $species; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $species; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $species; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="endemics">Number of endemics</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $endemics WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $endemics; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $endemics; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $endemics; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $endemics; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="useful_reading">Useful Reading</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $useful WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <?php echo auto_link("ISBN: ".$row["isbn"]) ; ?><br>
	  <a href="javascript:popAddRow('<?php echo $useful; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $useful; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $useful; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo $row["timestamp"]; ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $useful; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="useful_information">Useful Information</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $useful_info WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $useful_info; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $useful_info; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $useful_info; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo $row["timestamp"]; ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $useful_info; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="organisations">Organisations</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $organisations WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $organisations; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $organisations; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $organisations; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $organisations; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="festivals">Festivals and Bird Fairs</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $festivals WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $festivals; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $festivals; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $festivals; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $festivals; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="observatories">Observatories</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $observatories WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url1"]); ?><br>
      <?php echo auto_link($row["url1_title"]); ?><br>
      <?php echo auto_link($row["url2"]); ?><br>
      <?php echo auto_link($row["url2_title"]); ?><br>
	  <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $observatories; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $observatories; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $observatories; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $observatories; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="museums">Museums and Unversities</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $museums WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $museums; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $museums; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $museums; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $museums; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="reserves">Reserves</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $reserves WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url1"]); ?><br>
      <?php echo auto_link($row["url1_title"]); ?><br>
      <?php echo auto_link($row["url2"]); ?><br>
      <?php echo auto_link($row["url2_title"]); ?><br>
	  <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $reserves; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $reserves; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $reserves; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $reserves; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="trip_reports">Trip Reports</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $trips WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $trips; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $trips; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $trips; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $trips; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="guides">Local Guides, Tour Operators and Trips</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $holiday WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $holiday; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $holiday; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $holiday; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $holiday; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="places_to_stay">Places to stay</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $places WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["link_desc"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $places; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $places; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $places; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $places; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="mailing_lists">Mailing Lists</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $mailing WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	  <p><b><?php echo $row["title"]; ?></b><br>
	  <?php echo auto_link($row["url"]); ?><br>
	  <?php echo auto_link($row["post_email"]); ?><br>
	  <?php echo auto_link($row["contact"]); ?><br>
	  <?php echo auto_link($row["sub_email"]); ?><br>
	  <?php echo auto_link($row["unsub_email"]); ?><br>
	  <?php echo auto_link($row["sub_message"]); ?><br>
	  <?php echo auto_link($row["body_message"]); ?><br>
	  <?php echo auto_link($row["text"]); ?><br>
	  <a href="javascript:popAddRow('<?php echo $mailing; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $mailing; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $mailing; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $mailing; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="links">Links</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $links WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php
	  //$text = encode($row["link_desc"]);

	  echo auto_link( encode($row["link_desc"]) );
	  ?><br>
	  <a href="javascript:popAddRow('<?php echo $links; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $links; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $links; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo $row["timestamp"]; ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $links; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p class="title"><a name="artists_photographers">Artists and photographers</a></p>
<?php
	$select = "SELECT *, date_format(timestamp, '%d/%m/%Y') as timestamp FROM $artists WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);

	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p><b><?php echo $row["title"]; ?></b><br>
      <?php echo auto_link($row["url"]); ?><br>
	  <?php echo $row["link_desc"]; ?><br>
	  <a href="javascript:popAddRow('<?php echo $artists; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popUpdateRow('<?php echo $artists; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Update this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[update]</font></a>&nbsp;&nbsp;
	  <a href="javascript:popDeleteRow('<?php echo $artists; ?>',<?php echo $row["id"]; ?>,'<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Remove this record from this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php
		 }

	}else{
?>
      <p>
	  <a href="javascript:popAddRow('<?php echo $artists; ?>','<?php echo $category; ?>','<?php echo $subcategory; ?>','1114526428','<?php echo $_SERVER['PHP_SELF']; ?>');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
   </tr>
<?php
	}
?>
<hr size="1">
<p align="center" class="footer"><a href="#introduction">Introduction</a> | <a href="#top_sites">Top Sites</a> | <a href="#useful_reading">Useful Reading</a> | <a href="#useful_information">Useful Information</a> | <a href="#county_recorder">County Recorder</a> | <a href="#contributor">Contributor</a> | <a href="#links" style="text-decoration:none">Links</a> | <a href="#places_to_stay" style="text-decoration:none">Places to stay</a></p>
</body>
</html>



