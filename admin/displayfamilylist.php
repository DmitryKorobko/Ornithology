<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 12:06
 */
class DisplayFamilyList extends DisplayHeadText
{

    protected $dbtable = "familylist";
    protected $sectiontitle = "Bird Families";
    protected $adminfile = "../adminfamilylist.php";

    protected function convertParagraph($string){
        $string = str_ireplace("<i>", "<em>", $string);
        $string = str_ireplace("</i>", "</em>", $string);
        $string = str_ireplace("<b>", "<strong>", $string);
        $string = str_ireplace("</b>", "</strong>", $string);
        $string = str_ireplace("<br>", "<br />", $string);
        $string = strip_tags($string, '<em><strong><span><br /><a><ul><li><div>');
        $string = str_ireplace("...", "…", $string);
        return $string;
    }

}