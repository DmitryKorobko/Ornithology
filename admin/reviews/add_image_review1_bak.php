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

if ($_GET['mode'] <> ""){
$mode = $_GET['mode'];
}

//perform action based on action variable
switch ($action) {
    case "update":
			$sql_update =  "UPDATE $main SET ";
			$sql_update .= "image = '$image'";
			$sql_update .= " WHERE id = $article_id";
			//echo $sql_update;
			$update_result = mysql_query($sql_update);
	$mode = "UPDATESUCCESS";
	break;
	//add code to insert new image into the reviews image dir
	case "newimage":

				//get a list of currect images in the dir
				if ($handle = opendir(dir_const.'images_reviews')) {
					/* This is the correct way to loop over the directory. */
					$file_cnt = 1;
					while (false !== ($file = readdir($handle))) {

						if($file !="." && $file != ".."){
							$file_cnt ++;
						}
					}

					closedir($handle);
				}

				$image = $file_cnt;

				$max_nuwidth = 550;
				$max_nuheight = 550;
				$userfile = "file1";
				$dir = dir_const."images_reviews";
				//create new file name by appending file count number
				$filename = "reviews".$image;



				$type = $HTTP_POST_FILES[$userfile]['type'];

				if ($type == "image/gif"){
				$filename .= ".gif";
				}else if(type == "image/pjpeg"){
				$filename .= ".jpg";
				}


				//set variable to generate thumbnail
				$thumbnail = 0;

				// cmn - for php running as an apache module, must set parent dir as writable
				$chmodf = chmod_file."images_reviews";
				ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

				$upload_message = upload_image ($filename, $HTTP_POST_FILES[$userfile]['tmp_name'], $HTTP_POST_FILES[$userfile]['type'], $HTTP_POST_FILES[$userfile]['size'], $max_nuwidth, $max_nuheight, $filename, $thumbnail, $dir, 0);

				// cmn - for php running as an apache module, must reset parent dir back to non writable
				$chmodf = chmod_file."images_reviews";
				ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

				//$upload_message = "complete";
				$success = 1;

				$image = $filename;

				if($success == 1){
					//as the image number is taken from the file count var use this to update database
					$sql_update =  "UPDATE $main SET ";
					$sql_update .= "image = '$filename'";
					$sql_update .= " WHERE id = $article_id";
					//echo $sql_update;
					$result = mysql_query($sql_update);
					if ($result){
					$success = 1;
					$message = "Image added";
					}else{
					$success = 0;
					$message = "Image could not be added";
					}
				}
	$mode = "UPDATESUCCESS";
	break;
	case "delete":
			$sql_update =  "UPDATE $main SET ";
			$sql_update .= "image = '0'";
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
<p><font face="Verdana,Arial" size="5" color="#FAD400">Add new image</font></p>
<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=newimage" method="post" enctype="multipart/form-data">
<input type="hidden" name="dbtable" value="<?php echo $main; ?>">
<input type="hidden" name="article" value="<?php echo $article_id; ?>">
<table cellpadding="3">
	<tr>
        <td><font face="Verdana,Arial" size="2"><b>File : </b></font></td>
        <td><font face="ms sans serif" size="3"><input type="file" name="file1" size="53"><input type="hidden" name="MAX_FILE_SIZE" value="50000"></font></td>
    </tr>
    <tr>
        <td colspan="2"align="right"><a href="javascript:document.add_row.submit()" onMouseOver="window.status='Add this new image to the current page';return true" onMouseOut="window.status='';return true"><img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close()" onMouseOver="window.status='Abandon your changes and close this window';return true" onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a></td>
    </tr>
</table>
</form>

<p><font face="Verdana,Arial" size="5" color="#FAD400">Select an image</font></p>
<table cellpadding="0" cellspacing="0">
<?php
if ($handle = opendir('../../images_reviews')) {

					/* This is the correct way to loop over the directory. */
					$row_cnt = 0;
					while (false !== ($file = readdir($handle))) {

						if($file !="." && $file != ".."){

							if (($row_cnt % 8) == 0){
							echo "</td></tr><tr><td>";
							}
							echo "<a href=\"javascript:AssociateImage('$file')\"><img src=\"../../images_reviews/$file\" width=\"65\" height=\"85\" border=\"0\"></a>\n";
							$row_cnt ++;

						}

					}

					closedir($handle);
				}
?>
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
	opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
	refreshed ='yes';
}

function checkrefreshPickRecord(js_article,js_random) {
	if (refreshed == 'no') {
		opener.location.href = 'pick_reviews.php?article=' + js_article + '&random=' + js_random;
		refreshed ='yes';
	}
}

//-->
</script>

</head>

<body onBlur="self.focus()" onLoad="refreshPickRecord('<?php echo $article_id; ?>','1114514773');" onUnload="checkrefreshPickRecord('<?php echo $article_id; ?>','1114514773');" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF" bgcolor="#13474F"><p><font face="Verdana,Arial" size="5" color="#FAD400">Image associated</font></p>
<p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>
<table>
<?php if ($action != "delete"){ ?>
<tr>
<td valign="top"><font face="Verdana,Arial" size="1"><b>Image: </b></font></td>
<td><img src="../../images_reviews/<?php echo $image; ?>" width="65" height="85">
</td>
</tr>
<?php } ?>
</table>
<p><center><a href="javascript:window.close();" onMouseOver="window.status='Close this window and continue';return true" onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163" height="24" border="0"></a></center></p>
</body>
</html>
<?php
break;
}
?>
