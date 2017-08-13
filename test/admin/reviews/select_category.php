<?php 
include("../common/funct_lib.php");
connect_db();

//set table name variables
$main = "reviews_article_main";
$text = "reviews_article_text";
$status = "reviews_article_status";
?>
<html>                                             
<head>

<style type="text/css">
<!--
	body {
		background-color:#317B84;
	}
	td {  
		font-family:ms sans serif;
		font-size:14pt;
		color:white;
	}
// -->
</style> 

<script language="JavaScript">
<!--

rnd.today=new Date();
rnd.seed=rnd.today.getTime();

function rnd() {
	rnd.seed = (rnd.seed*9301+49297) % 233280;
	return rnd.seed/(233280.0);
}

function rand(number) {
	return Math.ceil(rnd()*number);
}

function reshow(object) {
	category = object.options[object.selectedIndex].text;
	if (category == 'Select a category') {
		document.categories.category.focus();
	}
	else {
		if (msie) window.document.frames[0].location.href = 'what.htm';
	else {
		for (var i = document.sub_category.names.length;i > 0;i--)
			document.sub_category.names.options[0] = null;
			reloading = true;
			showlinks();
			document.sub_category.names.options[0].selected = true;
		}
	return false;
	}
}

function load(object) {
	if (object.options[object.selectedIndex].value == 'Select a review') {
		document.sub_category.names.focus();
	}
	else {
		parent.home.location.href = 'pick_reviews.php?article=' + object.options[object.selectedIndex].value + '&random=' + rand(255);
    		return false;
	}
}

function quickload(article) {
	parent.home.location.href = 'pick_reviews.php?article=' + article + '&random=' + rand(255);
}

function opt(href,text) {
	if (reloading)  {
		var optionName = new Option(text, href, false, false)
		var length = document.sub_category.names.length;
		document.sub_category.names.options[length] = optionName;
	}
	else
		document.write('<option value="',href,'">',text,'</option>');
	
}

function showlinks() {
	if (category == 'Books') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='books'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Audio CD') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='audio'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'CD-ROM') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='cdrom'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'DVD') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='dvd'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Software') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='software'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Binoculars') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='binoculars'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Telescopes') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='telescopes'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Cameras') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='cameras'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
	if (category == 'Outdoor Wear and Other Equipment') {
		opt('Select a Review','Select a review');
		<?php
		$select = "SELECT $main.*, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section ='other'"; 
		$result = mysql_query($select);
		$count = mysql_num_rows($result);
		if ($count>0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
		?>
			  opt('<?php echo $row["article"]; ?>','<?php echo $row["title"]; ?>');
		 <?php 
			 }
		 }
		 ?>
	}
}

//-->
</script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
     <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Reviews></font></td>
      <td>
	<form name="categories">
	<select name="category" onChange="return reshow(document.categories.category)">
	<option selected>Select a category</option>
	<option>Books</option>
	<option>Audio CD</option>
	<option>CD-ROM</option>
	<option>DVD</option>
	<option>Software</option>
	<option>Binoculars</option>
	<option>Telescopes</option>
	<option>Cameras</option>
	<option>Outdoor Wear and Other Equipment</option>
	</select>
	</form>
      </td>
      <td valign="top">&gt;</td>
      <td>
<script language="JavaScript">
<!--
var reloading = false;
var category = document.categories.category.options[0].text;

if (navigator.appVersion.indexOf('MSIE 3') != -1) var msie = true; else var msie = false;

if (msie) {
	document.write('<IFRAME FRAMEBORDER=0 SCROLLING=NO SRC="what.htm" WIDTH="100%" HEIGHT="100">');
	document.write('</IFRAME>');
}
else {
	document.write('<form name="sub_category">');
	document.write('<select name="names" onChange="return load(document.sub_category.names)">');
	showlinks();
	document.write('</select>');
	document.write('</form>');
}
//-->
</script>
      </td>
   </tr>
</table>

</body>
</html>