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
$banner = new Banner();
if ($mode == "doadd" || $mode == "doupdate") {
    $success = $banner->saveData();
    if($success){
        $mode = "admin";
    }
}
if ($mode == "dodelete") {
    $success = $banner->deleteRow();
    if($success){
        $mode = "admin";
    }
}
$data = "";
$show = "";
if($mode == "admin"){
    $data = $banner->retrieveSiteData($category, $subcategory);
}elseif($mode == "update" || $mode == "delete"){
    $data = $banner->retrieveAdminData($id);
}elseif($mode == "add"){
    $data = 0;
}
$page = new displayBanner($mode, $data, $category, $subcategory);
$show .= $page->displayContent();

echo $show;