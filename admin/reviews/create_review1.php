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

if ($_POST["action"]){
$action = $_POST["action"];
}else{
$action = $_GET["action"];
}

//perform article summary and heading update
if ($action =="insert"){
	
	//insert new article
	$sqlsel="INSERT INTO $main ".REVIEW_MAIN_FIELDS." VALUES (";
	$sqlsel.="'".htmlspecialchars($_POST['title'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['summary'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['desc_1'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['desc_2'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['desc_3'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['rating'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['created'], ENT_QUOTES)."')";
	$sqlquery = $sqlsel;
	//echo $sqlquery;
	
	$result = mysql_query($sqlquery);
	$new_article_id = mysql_insert_id();
	//get new article id to maintain data integrity
	$sqlsel="UPDATE $main SET article=$new_article_id WHERE id=$new_article_id";
	$result = mysql_query($sqlsel);
	//echo $sqlsel;
	
	//insert new article status
	$sqlsel="INSERT INTO $status ".REVIEW_STATUS_FIELDS." VALUES (";
	$sqlsel.="'".$new_article_id."', ";
	$sqlsel.="'".htmlspecialchars($_POST['state'], ENT_QUOTES)."', ";
	$sqlsel.="'".htmlspecialchars($_POST['section'], ENT_QUOTES)."')";
	$sqlquery = $sqlsel;
	//echo $sqlquery;
	$result = mysql_query($sqlquery);
	$new_article_id = mysql_insert_id();
	if ($result){
	header("Location: pick_reviews.php?article=$new_article_id&mode=EDIT"); /* Redirect browser */
	}
}

?>
<html>
<head>
<title>Create review</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
<script language="JavaScript">
		//this script simply sets a flag when new variation text is added
		function validateText(form)
		{ 
		   	var today = "<?php echo date("dS M Y"); ?>";	
			form.created.value=today;
		}
</script>
</head>

<body>
<p class="major_title">Creating new review....</p>
<form name="review" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?action=insert">
<table border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td align="right" class="title" width="100">Title:</td>
    <td align="right">&nbsp;</td>
    <td><input type="text" style="font-weight: bold" name="title" size="80" maxlength="80" value=""></td>
  </tr>
  <tr>
    <td valign="top" align="right" class="title">Summary:<br><small>(for homepage)</small></td>
    <td valign="top" align="right">&nbsp;</td>
    <td><textarea name="summary" cols="80" rows="5" wrap="VIRTUAL"></textarea></td>
  </tr>
  <tr>
    <td align="right" class="title">Heading 1:</td>
    <td align="right">&nbsp;</td>
    <td><input type="text" name="desc_1" size="80" value="" maxlength="80"></td>
  </tr>
  <tr>
    <td align="right" class="title">Heading 2:</td>
    <td align="right">&nbsp;</td>
    <td><input type="text" name="desc_2" style="font-style: italic" size="80" value="" maxlength="80"></td>
  </tr>
  <tr>
    <td align="right" class="title">Heading 3:</td>
    <td align="right">&nbsp;</td>
    <td><input type="text" name="desc_3" size="80" value="" maxlength="80"></td>
  </tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="title">Rating:</td>
    <td align="right">&nbsp;</td>
    <td><select name="rating">
        <option value="0.5">0.5</option>
        <option value="1">1</option>
        <option value="1.5">1.5</option>
        <option value="2">2</option>
        <option value="2.5">2.5</option>
        <option value="3" selected>3</option>
        <option value="3.5">3.5</option>
        <option value="4">4</option>
        <option value="4.5">4.5</option>
        <option value="5">5</option>
        </select></td>
  </tr>
  <tr>
    <td align="right" class="title">Created:</td>
    <td align="right">&nbsp;</td>
    <td><input type="text" name="created" size="30" maxlength="30" value=""> Set to today's date <input type="checkbox" name="todays_date" value="yes" style="border:none" onClick="validateText(review)"></td>
  </tr>
  <tr>
    <td align="right" class="title">State:</td>
    <td align="right">&nbsp;</td>
    <td><font size="2" >hidden</font><input type="hidden" name="state" value="hidden"></td>
  </tr>
  <tr>
    <td align="right" class="title">Section:</td>
    <td align="right">&nbsp;</td>
    <td><select name="section">
        <option value="Pick a section">Pick a section</option>
        <option value="books">Books</option>
		<option value="audio">Audio CD</option>
		<option value="cd_rom">CD-ROM</option>
		<option value="dvd">DVD</option>
		<option value="software">Software</option>
		<option value="binoculars">Binoculars</option>
		<option value="telescopes">Telescopes</option>
		<option value="cameras">Cameras</option>
		<option value="other">Outdoor Wear and Other Equipment</option>
		//other potential sections
		//Books, Audio CD, CD-ROM, DVD, Software, Binoculars, Telescopes, Cameras, Outdoor Wear and Other Equipment
        </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><br><input type="submit" style="border-color: #FFFFFF #808080 #808080 #FFFFFF; border-width: thin thin thin thin" value="Save title/summary"></td>
  </tr>
</table>
</form>

</body>
</html>