<?php
include("../includes/config.inc.php");
if ($_POST['placeid'] != "") {
    $placeid = $_POST['placeid'];
}
if ($_POST['category'] != "") {
    $category = $_POST['category'];
}
if ($_POST['subcategory'] != "") {
    $subcategory = $_POST['subcategory'];
}
if ($_POST['id'] != "") {
    $id = $_POST['id'];
}
$mode = "";
if ($_POST['mode'] != "") {
    $mode = $_POST['mode'];
}
$page = new IntroDisplay($placeid, $category, $subcategory, $id);
$show = $page->getPageStart();
if ($mode == "add") {
    $show .= $page->getDisplayAdminEmpty();
}
if ($mode == "update") {
    $show .= $page->getDisplayAdmin();
}
if ($mode == "delete") {
    $show .= $page->getDisplayDelete();
}
if ($mode == "doadd" || $mode == "doupdate" || $mode == "dodelete") {
    $show .= $page->getProcessResults($mode);
}
$show .= $page->getPageEnd();
echo $show;