<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 20:16
 */
include("../includes/config.inc.php");
$pageId = "";
$category = "";
$subcategory = "";
$id = "";
$mode = "";
if ($_POST['pageid'] != "") {
    $pageId = $_POST['pageid'];
}else{
    if($_GET['pageid'] != ""){
        $pageId = $_GET['pageid'];
    }
}
if ($_POST['category'] != "") {
    $category = $_POST['category'];
}else{
    if($_GET['category'] != ""){
        $category = $_GET['category'];
    }
}
if ($_POST['subcategory'] != "") {
    $subcategory = $_POST['subcategory'];
}else{
    if($_GET['subcategory'] != ""){
        $subcategory = $_GET['subcategory'];
    }
}
if ($_POST['id'] != "") {
    $id = $_POST['id'];
}
if ($_POST['mode'] != "") {
    $mode = $_POST['mode'];
}
$page = new DisplayBirdFamily($pageId, $category, $subcategory, $id);
$show = $page->getPageStart();
if($mode == ""){
    $show .= $page->getDisplayAdmin();
}
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
    $show .= $page->ProcessForm($mode);
}
$show .= $page->getPageEnd();
echo $show;