<?php
include("../includes/config.inc.php");
if ($_POST['title'] != "") {
    $title = $_POST['title'];
}
if ($_POST['category'] != "") {
    $category = $_POST['category'];
}
if ($_POST['subcategory'] != "") {
    $subcategory = $_POST['subcategory'];
}
if ($_POST['page_id'] != "") {
    $page_id = $_POST['page_id'];
}
$mode = "";
if ($_POST['mode'] != "") {
    $mode = $_POST['mode'];
}
$page = new TitleDisplay($category, $subcategory, $page_id);
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