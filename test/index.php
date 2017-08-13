<?php
//var_dump($_SERVER['REQUEST_URI']); exit();
include("includes/config.inc.php");
$message = '';
if(isset($_POST['feedback_email'])){
	$mailer = new email($_POST);
	$message = $mailer->send();
}
$url = $_SERVER['REQUEST_URI'];
if($url == "/index.php" || $url == "/"){
	$sql_subcat = "default_home";
	$sql_cat = "default_home";
	$tmpl_name = "template_home.html";
}else{
	$urlnofileext = explode(".", $url);
	$urlparts = explode("/", $urlnofileext[0]);
	$sql_subcat = array_pop($urlparts);
	$sql_cat = array_pop($urlparts);
	$tmpl_name = "template.html";
}
$article = "";
if(($sql_cat == "news" || $sql_cat == "reviews" || $sql_cat == "announcements" || $sql_cat == "offers") && ctype_digit($_GET['article'])){
	$article = $_GET['article'];
}
$alias = new Urlalias($sql_cat, $sql_subcat);
$sql_cat = $alias->getNewCat();
$sql_subcat = $alias->getnewSubCat();
$page = new Page();
echo $page->createPage($tmpl_name, $sql_cat, $sql_subcat, $article, $message);
