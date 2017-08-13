<?php
include("../includes/config.inc.php");
$page = new Offers();
$show = $page->getPageStart();
$show .= $page->getPageHead();
$show .= $page->getPageBodyStart();
$mode = "";
$id = "";
if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
}
if ($mode == "") {
    $show .= $page->getChooseForm();
}
if ($mode == "add" || $mode == "update" || $mode == "delete") {
    $show .= $page->getMainForm($mode);
}
if ($mode == "doadd" || $mode == "doupdate" || $mode == "dodelete") {
    $show .= $page->getProcessResults($mode);
}
$show .= $page->getPageBodyEnd();
echo $show;