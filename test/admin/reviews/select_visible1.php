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
if ($HTTP_GET_VARS ['mode'] <> ""){
$mode = $HTTP_GET_VARS ['mode'];
}

//perform action based on action variable
switch ($action) {
    case "update":

	while(list($key, $value) = each($_POST))
	{
		if ($value == ""){
			$value = "blank";
		}
		else{
			$sql_update =  "UPDATE $status SET ";
			$sql_update .= "state = '$value'";
			$sql_update .= " WHERE id = $key";

			$update_result = mysql_query($sql_update);
		}
	}

	$mode = "UPDATE";
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
<HTML>
<HEAD>
<TITLE></TITLE>

<STYLE TYPE="text/css">
<!--
  p            { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; }
  td           { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #000000; }
  th           { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000; text-align:left; font-weight:normal}
  .title       { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #999999; }
  a:hover      { color: #FF0000; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
  a:link       { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
  a:visited    { color: #0000FF; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
  .major_title { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16pt; font-weight: bold; color: #000000; }
-->
</STYLE>

</HEAD>

<body>
<FORM NAME="radioForm" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=update" method="post">
<?php
//get a list of all the review sections
$selectsection = "SELECT DISTINCT $status.section FROM $status";
$resultsection = mysql_query($selectsection);
while ($rowsection = mysql_fetch_assoc($resultsection))
{

	//get a list of all news records
	$select = "SELECT $main.*, $main.id AS article_id, $status.* FROM $main, $status WHERE $main.article = $status.article AND $status.section='".$rowsection["section"]."' ORDER BY $main.id DESC";

	//generate the correct section headings
	switch ($rowsection["section"]){
	case "books":
		echo "<p class=\"major_title\">Books</p>";
	break;
	case "optics":
		echo "<p class=\"major_title\">Optics</p>";
	break;
	case "other":
		echo "<p class=\"major_title\">Other</p>";
	break;
	case "dvd_cdrom_and_video":
		echo "<p class=\"major_title\">DVD/CDROM/Video</p>";;
	break;
	}

	$result = mysql_query($select);
	while ($row = mysql_fetch_assoc($result))
	{
	$selected_1 = "";
	$selected_2 = "";
	$selected_3 = "";
	$highlight_start_1 = "";
	$highlight_start_2 = "";
	$highlight_start_3 = "";
	$highlight_end_1 = "";
	$highlight_end_2 = "";
	$highlight_end_3 = "";
	switch ($row["state"]){
	case "visible":
		$selected_1 = "checked";
		$highlight_start_1 = "<font color=\"#FF0000\">";
		$highlight_end_1 = "</font>";
	break;
	case "archived":
		$selected_2 = "checked";
		$highlight_start_2 = "<font color=\"#FF0000\">";
		$highlight_end_2 = "</font>";
	break;
	case "hidden":
		$selected_3 = "checked";
		$highlight_start_3 = "<font color=\"#FF0000\">";
		$highlight_end_3 = "</font>";
	break;
	}
	?>
	<p><b><?php echo $row["title"]." - ". $row["state"]; ?></b><br>
	<INPUT TYPE="RADIO" VALUE="visible" NAME="<?php echo $row["article_id"]; ?>" <?php echo $selected_1; ?>><?php echo $highlight_start_1; ?>Visible<?php echo $highlight_end_1; ?>
	<INPUT TYPE="RADIO" VALUE="archived" NAME="<?php echo $row["article_id"]; ?>" <?php echo $selected_2; ?>><?php echo $highlight_start_2; ?>Archive<?php echo $highlight_end_2; ?>
	<INPUT TYPE="RADIO" VALUE="hidden" NAME="<?php echo $row["article_id"]; ?>" <?php echo $selected_3; ?>><?php echo $highlight_start_3; ?>Hidden<?php echo $highlight_end_3; ?>
	</p>

	<?php
	}
//end section while
}
?>
<INPUT TYPE="SUBMIT">
</FORM>

</BODY>
</HTML>
<?php
break;
}
?>
