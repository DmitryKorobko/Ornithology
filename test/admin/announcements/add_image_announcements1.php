<?php 
include("../common/funct_lib.php");
connect_db();

//set table name variables
$main = "announcements_article_main";
$text = "announcements_article_text";
$status = "announcements_article_status";

if ($_POST["article"]){
$article_id = $_POST["article"];
}else{
$article_id = $_GET["article"];
}

if ($_POST["image"]){
$image = $_POST["image"];
}else{
$image = $_GET["image"];
}

if ($_POST["action"]){
$action = $_POST["action"];
}else{
$action = $_GET["action"];
}

if ($HTTP_GET_VARS ['mode'] <> ""){
$mode = $HTTP_GET_VARS ['mode'];
}

//perform action based on action variable
switch ($action) {
    case "update":
			$sql_update =  "UPDATE $main SET ";
			$sql_update .= "image = $image";
			$sql_update .= " WHERE id = $article_id";
			//echo $sql_update;
			$update_result = mysql_query($sql_update);	
	$mode = "UPDATESUCCESS";	
	break;
}
//onload set page default mode
if ($mode == ""){
	$mode = "UPDATE";
}
?>
<?php
switch ($mode) {
    case "UPDATE":
?>
<html>

<head>
<title>Associate image</title>
<script language="JavaScript">
<!--
window.focus();

function AssociateImage(js_image) {
  document.location='<?php $_SERVER['PHP_SELF']; ?>?action=update&dbtable=<?php echo $main; ?>&article=<?php echo $article_id; ?>&image=' + js_image + '&random=1114511120';}

//-->
</script>
</head>

<body text="#FFFFFF" onLoad="window.focus()" bgcolor="#13474F">

<p><font face="Verdana,Arial" size="5" color="#FAD400">Select an image</font></p>
<table cellpadding="0" cellspacing="0">
  <tr>
    <td width="15">&nbsp;</td>
    <td><a href="javascript:AssociateImage(1)"><img src="./images/news1.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(2)"><img src="./images/news2.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(3)"><img src="./images/news3.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(4)"><img src="./images/news4.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(5)"><img src="./images/news5.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(6)"><img src="./images/news6.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(7)"><img src="./images/news7.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(8)"><img src="./images/news8.gif" width="65" height="85" border="0"></a></td>
  </tr>
  <tr>
    <td width="15">&nbsp;</td>
    <td><a href="javascript:AssociateImage(9)"><img src="./images/news9.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(10)"><img src="./images/news10.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(11)"><img src="./images/news11.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(12)"><img src="./images/news12.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(13)"><img src="./images/news13.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(14)"><img src="./images/news14.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(15)"><img src="./images/news15.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(16)"><img src="./images/news16.gif" width="65" height="85" border="0"></a></td>
  </tr>
  <tr>
    <td width="15">&nbsp;</td>
    <td><a href="javascript:AssociateImage(17)"><img src="./images/news17.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(18)"><img src="./images/news18.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(19)"><img src="./images/news19.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(20)"><img src="./images/news20.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(21)"><img src="./images/news21.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(22)"><img src="./images/news22.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(23)"><img src="./images/news23.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(24)"><img src="./images/news24.gif" width="65" height="85" border="0"></a></td>
  </tr>
  <tr>
    <td width="15">&nbsp;</td>
    <td><a href="javascript:AssociateImage(25)"><img src="./images/news25.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(26)"><img src="./images/news26.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(27)"><img src="./images/news27.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(28)"><img src="./images/news28.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(29)"><img src="./images/news29.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(30)"><img src="./images/news30.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(31)"><img src="./images/news31.gif" width="65" height="85" border="0"></a><a
           href="javascript:AssociateImage(32)"><img src="./images/news32.gif" width="65" height="85" border="0"></a></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
break;

 case "UPDATESUCCESS";
?>

<html>

<head>
<title>Associate image</title>

<script language="JavaScript">
<!--

refreshed = 'no';

function refreshPickRecord(js_article,js_random) {
	opener.location.href = 'pick_announcements.php?article=' + js_article + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_article,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_announcements.php?article=' + js_article + '&random=' + js_random;
		refreshed ='yes';
	}
}

//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $article_id; ?>','1114514773');" onUnload="checkrefreshPickRecord('<?php echo $article_id; ?>','1114514773');" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F"><p><font face="Verdana,Arial" size="5" color="#FAD400">Image associated</font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Image: </b></font></td>
<td><img src="./images/news<?php echo $image; ?>.gif" width="65" height="85"></td>
</tr>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>
<?php
break;
}
?>
