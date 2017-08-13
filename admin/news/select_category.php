<?php 
include("../common/funct_lib.php");
connect_db();
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

function quickload(article) {
	if (article == "") {
	}
	else {
		parent.home.location.href = 'pick_news.php?article=' + article + '&random=' + rand(255);
	}
}

//-->
</script>

</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="5">
  <tr>
    <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;News > </font></td>
    <td>
	<form name="articles">
        <select name="article" onChange="quickload(this.value)">
		<option selected>Select a news article</option>
	<?php
	$select = "select * from news_article_main ORDER BY timestamp DESC";
	$result = mysql_query($select);
	$count = mysql_num_rows($result);
	
	if ($count>0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
	?>
	
          
          <option value="<?php echo $row["article"]; ?>"><?php echo $row["title"]; ?></option>
     <?php 
		 }
	 }
	 ?>
        </select>
        </form>
    </td>
  </tr>
</table>

</body>
</html>