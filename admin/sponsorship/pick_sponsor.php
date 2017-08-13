<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
$sponsors = "sponsors";
?>
<html>
<head>
<title>Pick Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">
<!--

function popDeleteRow(js_info_type,js_id,js_subcategory,js_random) {
	popupdate = window.open('delete_row_sponsor1.php?id=' + js_id + '&subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
}

function popAddRow(js_info_type,js_subcategory,js_random) {
	popupdate = window.open('add_row_sponsor1.php?subcategory=' + js_subcategory + '&info_type=' + js_info_type + '&random=' + js_random,'popupdate','width=590,height=350,left=50,top=50,status=yes,scrollbars=yes');
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
<p class="major_title"><?PHP echo $subcategory; ?></p>
<p class="title"><a name="introduction">Sponsor</a></p>
<?php	
	$select = "SELECT * FROM $sponsors WHERE `section` = '$subcategory' ORDER BY id";
	//echo $select;
	$result = mysql_query($select);
	$count = mysql_num_rows($result);
	
	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	<p>
     <b> Title : </b><?php echo auto_link($row["title"]); ?><br>
	 <b> Url :  </b><?php echo auto_link($row["url"]); ?><br>
	 <b> Text : </b><?php echo auto_link($row["link_desc"]); ?><br>
	  <?php if($row["filename"] <> ""){ ?>
	  <img src="<?php echo ROOT_URL; ?>/photos/<?php echo $row["filename"]; ?>" border="0"><br>
	  <?php } ?>
	  <a href="javascript:popAddRow('<?php echo $sponsors; ?>','<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add]</font></a>&nbsp;&nbsp;
	   <a href="javascript:popDeleteRow('<?php echo $sponsors; ?>',<?php echo $row["id"]; ?>,'<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Delete this record';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[delete]</font></a>&nbsp;&nbsp;
	  
	  <font color="#646464"><small><?php echo auto_link($row["timestamp"]); ?></small></font>
	  </p>
<?php 
		 }
		 
	}else{
?> 
      <p>
	  <a href="javascript:popAddRow('<?php echo $sponsors; ?>','<?php echo $subcategory; ?>','1114526428');" onMouseOver="window.status='Add a new record to this page';return true" onMouseOut="window.status='';return true"><font color="#FF0000">[add a new record]</font></a><small> - invisible</small>
	  </p>
<?php 
	}
?>
<hr size="1">
</body>
</html>



