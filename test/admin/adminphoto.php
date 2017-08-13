<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 24/02/2015
 * Time: 20:06
 */
include("../includes/config.inc.php");
if ($_POST['category'] != "") {
    $category = $_POST['category'];
}
if ($_POST['subcategory'] != "") {
    $pageId = $_POST['subcategory'];
}
if ($_POST['pageId'] != "") {
    $pageId = $_POST['pageId'];
}
if ($_POST['id'] != "") {
    $id = $_POST['id'];
}
$mode = "";
if ($_POST['mode'] != "") {
    $mode = $_POST['mode'];
}
$photo = new Photo();
if ($mode == "doadd" || $mode == "doupdate") {
    $success = $photo->saveData();
    if($success){
        $mode = "admin";
    }
}
if ($mode == "dodelete") {
    $success = $photo->deleteRow();
    if($success){
        $mode = "admin";
    }
}
$data = "";
$show = "";
if($mode == "admin"){
    $data = $photo->retrieveSiteData($pageId);
}elseif($mode == "update" || $mode == "delete"){
    $data = $photo->retrieveAdminData($id);
}elseif($mode == "add"){
    $data = 0;
}
$page = new displayPhoto($mode, $data, $pageId);
$show .= $page->displayContent();
$page = new DisplayPhoto($data);

echo $show;