<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 17/10/2014
 * Time: 12:41
 */
class Banner extends prepareInput
{
    private $id;
    private $filename;
    private $animated;
    private $width;
    private $height;
    private $alt;
    private $url1;
    private $url1title;
    private $sponsor;
    private $bannertext;
    private $category;
    private $subcategory;
    private $pageId;
    private $table;
    private $params;
    private $data;

    public function __construct()
    {
        $this->table = "banner";
        $this->animated = 0;
    }

    public function retrieveSiteData($cat, $subcat){
        $result = $this->getDataByPageId($cat, $subcat);
        if($result) {
            $this->decodeForSite();
            return $this->data;
        }else{
            return 0;
        }
    }

    public function retrieveAdminData($id){
        $this->getDataById($id);
        $this->decodeForAdmin();
        return $this->data;
    }

    public function saveData(){
        //if id is present use update else use insert
        $this->validateInput();//Take $_POST data, validate it and put it into relevant vars
        if($_FILES['photo']){
            if($this->animated) {
                $imageSuccess = $this->saveAnimatedImage();
            }else{
                $imageSuccess = $this->saveImage();
            }
        }
        if ($this->id) {
            //update - return 1 if successful, 0 if not
            $this->createParams();
            $dataSuccess = $this->update();
        } else {
            //insert - return 1 if successful, 0 if not
            $this->createParams();
            $dataSuccess = $this->insert();
        }
        if($dataSuccess && $imageSuccess){
            return 1;
        }else{
            return 0;
        }
    }

    public function deleteRow(){
        //delete 1 row by id - return success / fail
        $id = $_POST['id'];
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = "DELETE FROM $this->table WHERE `id`=?";
        $result = $db->prepare($sql);
        $result->execute(array($id));
        $no = $result->rowCount();
        if ($no > 0){
            return 1;
        }
        else {
            echo $result->errorinfo();
            return 0;
        }
    }

    private function validateInput(){
        if($_POST['id'] != ""){
            $this->id = $_POST['id'];
        }
        if($_POST['filename'] != ""){
            $this->filename = $_POST['filename'];
        }
        if($_POST['animated'] == "1"){
            $this->animated = 1;
        }
        $this->width = $this->checkInt($_POST['width']);
        $this->height = $this->checkInt($_POST['height']);
        if ($_POST['alt'] != ""){
            $this->alt = $this->encode($this->convertSentence($_POST['alt']));
        }else{
            $this->alt = null;
        }
        if ($_POST['url1'] != ""){
            $this->url1 = $this->encode($this->convertUrl($_POST['url1']));
        }else{
            $this->url1 = null;
        }
        if ($_POST['url1title'] != ""){
            $this->url1title = $this->convertSentence($_POST['url1title']);
        }else{
            $this->url1title = null;
        }
        if ($_POST['sponsor'] != ""){
            $this->sponsor = $this->encode($this->convertSentence($_POST['sponsor']));
        }else{
            $this->sponsor = null;
        }
        if ($_POST['bannertext'] != ""){
            $this->bannertext = $this->encode($this->convertParagraph($_POST['bannertext']));
        }else{
            $this->bannertext = null;
        }
        $this->category = $_POST['category'];
        $this->subcategory = $_POST['subcategory'];
        if ($_POST['pageId'] != ""){
            $this->pageId = $this->checkInt($_POST['pageId']);
        }else{
            $this->pageId = null;
        }
        return;
    }

    private function decodeForAdmin(){
        $this->data['filename'] = $this->decode($this->data['filename']);
        if(!is_null($this->data['alt'])){
            $this->data['alt'] = $this->decode($this->data['alt']);
        }
        if(!is_null($this->data['url1'])) {
            $this->data['url1'] = $this->decode($this->data['url1']);
        }
        if(!is_null($this->data['url1title'])) {
            $this->data['url1title'] = $this->decode($this->data['url1title']);
        }
        if(!is_null($this->data['sponsor'])) {
            $this->data['sponsor'] = $this->decode($this->data['sponsor']);
        }
        if(!is_null($this->data['bannertext'])) {
            $this->data['bannertext'] = $this->decode($this->data['bannertext']);
            $this->data['bannertext'] = $this->p2nl($this->data['bannertext']);
        }
        $this->data['category'] = $this->decode($this->data['category']);
        $this->data['subcategory'] = $this->decode($this->data['subcategory']);
    }

    private function decodeForSite(){
        $i = 0;
        $decoded = array();
        foreach($this->data as $dat){
            $dat['filename'] = $this->decode($dat['filename']);
            if(!is_null($dat['alt'])){
                $dat['alt'] = $this->decode($dat['alt']);
            }
            if(!is_null($dat['url1'])) {
                $dat['url1'] = $this->decode($dat['url1']);
            }
            if(!is_null($dat['url1title'])) {
                $dat['url1title'] = $this->decode($dat['url1title']);
            }
            if(!is_null($dat['sponsor'])) {
                $dat['sponsor'] = $this->decode($dat['sponsor']);
            }
            if(!is_null($dat['bannertext'])) {
                $dat['bannertext'] = $this->decode($dat['bannertext']);
            }
            $dat['category'] = $this->decode($dat['category']);
            $dat['subcategory'] = $this->decode($dat['sub-category']);
            $decoded[$i] = $dat;
            $i++;
        }
        $this->data = $decoded;
    }

    private function saveImage(){
        $handle = new upload($_FILES['photo']);
        if ($handle->uploaded) {
            $handle->image_resize = true;
            $handle->image_x = $this->width;
            $handle->image_y = $this->height;
            $handle->image_ratio = true;
            $handle->file_name_body_add = time();
            $handle->process(ROOT_DIR . BANNERIMGDIR);
            if ($handle->processed) {
                //get width and height after process to save to db
                $this->width = $handle->image_dst_x;
                $this->height = $handle->image_dst_y;
                $this->filename = $this->encode($handle->file_dst_name);
                $handle->clean();
                return true;
            } else {
                echo 'error : ' . $handle->error;
                return false;
            }
        }
        return false;
    }

    private function saveAnimatedImage(){
        //
        list($filename, $extension) = explode(".", basename($_FILES["photo"]["name"]));
        $this->filename = $filename . time() . "." . $extension;
        $newFilename = ROOT_DIR . BANNERIMGDIR . $this->filename;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $newFilename)) {
            return true;
        } else {
            return false;
        }
    }

    private function getDataById($id){//update - where id is already known
        $sql = "SELECT * FROM $this->table WHERE `id` = '$id'";
        $con = new Dbconnection();
        $db = $con->connect();
        $result = $db->prepare($sql);
        $result->execute();
        $count = $result->rowCount();
        if ($count > 0) {
            $this->data = $result->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "Wrong result";//TODO Make error a real thing
            echo "error - No result<br />id =  $this->id";
            return;
        }
    }

    private function getDataByPageId($cat, $subCat){//retrieve - where pageId is already known
        $sql = "SELECT * FROM $this->table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$cat' AND `sub-category` = '$subCat')";//TODO: Change category an subcategory to pageId
        $con = new Dbconnection();
        $db = $con->connect();
        $result = $db->prepare($sql);
        $result->execute();
        $count = $result->rowCount();
        if ($count > 0) {
            $this->data = $result->fetchAll(PDO::FETCH_ASSOC);
            return 1;
        } else {
            return 0;
        }
    }

    private function createParams(){
        $select = "SELECT `page_id` FROM `pagename` WHERE `category` = '$this->category' AND `sub-category` = '$this->subcategory'";
        $result = mysql_query($select);
        $pageId = "";
        while ($row = mysql_fetch_assoc($result)) { $this->pageId .= $row['page_id']; }
        //create field list and matching params from vars
        $this->params = array(":filename" => $this->filename, ":width" => $this->width, ":height" => $this->height, ":alt" => $this->alt, ":url1" => $this->url1, ":url1title" => $this->url1title, ":sponsor" => $this->sponsor, ":bannertext" => $this->bannertext);
        if(!$this->id){
            $this->params[":category"] = $this->category;
            $this->params[":subcategory"] = $this->subcategory;
            $this->params[":page_id"] = $this->pageId;
        }
        if($this->id){
            $this->params[":id"] = $this->id;
        }
    }

    /**
     * @return int
     */
    private function update()
    {
        $sql = "UPDATE $this->table SET `filename`=:filename, `width`=:width, `height`=:height, `alt`=:alt, `url1`=:url1, `url1title`=:url1title, `sponsor`=:sponsor, `bannertext`=:bannertext WHERE `id`=:id";
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute($this->params);
        $count = $result->rowCount();
        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @return int
     */
    private function insert()
    {
        $sql = "INSERT INTO $this->table (`filename`, `width`, `height`, `alt`, `url1`, `url1title`, `sponsor`, `bannertext`, `page_id`) VALUES (:filename, :width, :height, :alt, :url1, :url1title, :sponsor, :bannertext, :page_id)";
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute($this->params);
        $count = $result->rowCount();
        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}