<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 04/01/2015
 * Time: 11:29
 */

class Page {

    private $pageId;
    private $pageTitle;
    private $directory;
    private $filename;
    private $template;

    public function createPage($tmpl_name, $sql_cat, $sql_subcat, $article="", $message=""){
        $code = createHTML($tmpl_name, $sql_cat, $sql_subcat, $article, $message);
        return $code;
    }

    private function retrievePageDetails(){
        $pageName = new pageName();
    }

}