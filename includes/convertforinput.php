<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 09/09/14
 * Time: 16:18
 */

class PrepareInput
{

    protected function encode($string)
    {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
    }

    protected function decode($string)
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
    }

    protected function adminDecodeParagraph($string){
        $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
        $string = $this->p2nl($string);
        return $string;
    }

    protected function p2nl($string){
        $string = str_ireplace("</p><p>", "\n", $string);
        $string = str_ireplace("<p>", "", $string);
        $string = str_ireplace("</p>", "", $string);
        return $string;
    }

    protected function nl2p($str) {
        $arr=explode("\n",$str);
        $out='';
        for($i=0;$i<count($arr);$i++) {
            if(strlen(trim($arr[$i]))>0)
                $out.='<p>'.trim($arr[$i]).'</p>';
        }
        return $out;
    }

    protected function convertSentence($string){
        $string = str_ireplace("<i>", "<em>", $string);
        $string = str_ireplace("</i>", "</em>", $string);
        $string = str_ireplace("<b>", "<strong>", $string);
        $string = str_ireplace("</b>", "</strong>", $string);
        $string = strip_tags($string, '<em><strong><span><a>');
        $string = str_ireplace("...", "…", $string);
        $string = trim($string);
        return $string;
    }

    protected function convertParagraph($string){
        $string = str_ireplace("<i>", "<em>", $string);
        $string = str_ireplace("</i>", "</em>", $string);
        $string = str_ireplace("<b>", "<strong>", $string);
        $string = str_ireplace("</b>", "</strong>", $string);
        $string = str_ireplace("<br>", "<br />", $string);
        $string = strip_tags($string, '<em><strong><span><br /><a>');
        $string = str_ireplace("...", "…", $string);
        $string = trim($string);
        $string = $this->nl2p($string);
        return $string;
    }

    protected function convertImageParagraph($string){
        $string = str_ireplace("<i>", "<em>", $string);
        $string = str_ireplace("</i>", "</em>", $string);
        $string = str_ireplace("<b>", "<strong>", $string);
        $string = str_ireplace("</b>", "</strong>", $string);
        $string = str_ireplace("<br>", "<br />", $string);
        $string = strip_tags($string, '<em><strong><span><br /><a><img><figure><figcaption>');
        $string = str_ireplace("...", "…", $string);
        $string = trim($string);
        $string = $this->nl2p($string);
        return $string;
    }

    protected function convertUrl($string){
        $string = strip_tags($string);
        return $string;
    }

    public function checkInt($string){
        if(ctype_digit($string)){
            return $string;
        }else{
            return null;
        }
    }

    public function convertDateToTimestamp($newDate)
    {
        $date = DateTime::createFromFormat('j/n/y', $newDate);
        $timeStamp = $date->getTimestamp();
        return $timeStamp;
    }

    public function convertTimestampToDate($format, $newDate)
    {
        $date = "";
        if ($format == "short") {
            $date = date(SHORT_DATE, $newDate);
        }
        if ($format == "long") {
            $date = date(LONG_DATE, $newDate);
        }
        return $date;
    }

}