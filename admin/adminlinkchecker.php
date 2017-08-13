<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/01/2015
 * Time: 11:47
 */

include("../includes/config.inc.php");
$linktarget = "";
$mode = "";
if (ctype_digit($_POST['linktarget'])) {
    $linktarget = $_POST['linktarget'];
}
if ($_POST['mode'] != "") {
    $mode = $_POST['mode'];
}
$output = new LinkChecker();
$show = $output->getPageStart();
if($mode == ""){
    $show .= $output->getDisplayAdmin();
}
if ($mode == "check") {
    $show .= $output->getResults($linktarget);
}
$show .= $output->getPageEnd();
echo $show;