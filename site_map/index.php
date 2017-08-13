<?php 
include("../admin/common/funct_lib.php");
connect_db();

if ($_POST["article"]){
$article_id = $_POST["article"];
}else{
$article_id = $_GET["article"];
}

$tmpl_dir = "";
$tmpl_name = "template_sitemap.htm";

//create the correct output dir and html file name based on subcategory and category
list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
$html_dir = "";
$html_name = $alt_file;
//echo $html_dir;
//echo $html_name;
$sql_cat = $category;
$sql_subcat = $subcategory;
$table = $info_type;
global $alt_dir;
global $alt_file;
//set vaiable to steam html output
$stream = 1;
//echo getcontent($table, $category, $subcategory);
echo createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat, $stream, $article_id);
?>
