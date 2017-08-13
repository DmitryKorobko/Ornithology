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
	subcategory = object.options[object.selectedIndex].text;
	if (subcategory == 'Select a category') {
		document.category.subcategory.focus();
	}
	else {
		if (msie) window.document.frames[0].location.href = 'what.htm';
	else {
		for (var i = document.region.names.length;i > 0;i--)
			document.region.names.options[0] = null;
			reloading = true;
			showlinks();
			document.region.names.options[0].selected = true;
		}
	return false;
	}
}

function load(object) {
	if (object.options[object.selectedIndex].value == 'Select a sub-category') {
		document.region.names.focus();
	}
	else {
		parent.home.location.href = 'pick_default.php?category=commerce&subcategory=' + object.options[object.selectedIndex].value + '&random=1114525662' + rand(255);
    		return false;
	}
}

function quickload(place) {
	parent.home.location.href = 'pick_default.php?category=' + place + '&subcategory=' + place + '&random=1114525662' + rand(255);
}

function showlinks() {
	if (subcategory == 'Introduction') {
		opt('','No further response required');
		quickload('default_intro');
	}
	if (subcategory == 'Home Page') {
		opt('','No further response required');
		quickload('default_home');
	}
	if (subcategory == 'About Us') {
		opt('','No further response required');
		quickload('default_about');
	}
}

function opt(href,text) {
	if (reloading)  {
		var optionName = new Option(text, href, false, false)
		var length = document.region.names.length;
		document.region.names.options[length] = optionName;
	}
	else
		document.write('<option value="',href,'">',text,'</option>');
	
}
//-->
</script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
     <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Default Text></font></td>
      <td>
	<form name="category">
	<select name="subcategory" onChange="return reshow(document.category.subcategory)">
	<option selected>Select a category</option>
	<option>Introduction</option>
	<option>Home Page</option>
	<option>About Us</option>
	</select>
	</form>
      </td>
      <td valign="top">&gt;</td>
      <td>
<script language="JavaScript">
<!--
var reloading = false;
var subcategory = document.category.subcategory.options[0].text;

if (navigator.appVersion.indexOf('MSIE 3') != -1) var msie = true; else var msie = false;

if (msie) {
	document.write('<IFRAME FRAMEBORDER=0 SCROLLING=NO SRC="what.htm" WIDTH="100%" HEIGHT="100">');
	document.write('</IFRAME>');
}
else {
	document.write('<form name="region">');
	document.write('<select name="names" onChange="return load(document.region.names)">');
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