<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 20:16
 */
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
$page = new ChecklistDisplay($placeid, $category, $subcategory, $id);
$show = $page->getPageStart();
if ($mode == "add") {
    $show .= $page->getDisplayAdminEmpty();
}
if ($mode == "update") {
    $show .= $page->getDisplayAdminFull();
    $show .= $page->getAdminOptions();
}
if ($mode == "delete") {
    $show .= $page->getDisplayAdminDelete();
}
if ($mode == "doadd" || $mode == "doupdate" || $mode == "dodelete") {
    $show .= $page->getProcessResults($mode);
}
$show .= $page->getPageEnd();
echo $show;