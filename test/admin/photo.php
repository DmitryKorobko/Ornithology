<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 24/02/2015
 * Time: 20:05
 *
 * public saveData() returns 1 if successful, 0 if unsuccessful
 * public deleteRow(id) returns 1 if successful, 0 if unsuccessful
 * public retrieveSiteData(pageId) return array of array of site decoded data or 0 if none
 * public retrieveAdminData(pageId) return array of array of admin decoded data
 */

class Photo extends prepareInput {

    private $id;
    private $filename;
    private $width;
    private $height;
    private $alt;
    private $common_name;
    private $sci_name;
    private $credit;
    private $url;
    private $country;
    private $pageId;
    private $table;
    private $params;
    private $data;

    public function __construct(){
        $this->table = "photos";
    }

    public function retrieveSiteData($pageId){
        $result = $this->getDataByPageId($pageId);
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
        $success = 0;
        $this->validateInput();//Take $_POST data, validate it and put it into relevant vars
        if($_FILES['photo']){
            $success = $this->savePhoto();
        }
        if ($this->id) {
            //update - return 1 if successful, 0 if not
            $this->createParams();
            return $this->update();
        } else {
            //insert - return 1 if successful, 0 if not
            $this->createParams();
            return $this->insert();
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
        $this->width = $this->checkInt($_POST['width']);
        $this->height = $this->checkInt($_POST['height']);
        if ($_POST['alt'] != ""){
            $this->alt = $this->encode($this->convertSentence($_POST['alt']));
        }else{
            $this->alt = null;
        }
        if ($_POST['common_name'] != ""){
            $this->common_name = $this->encode($this->convertSentence($_POST['common_name']));
        }else{
            $this->common_name = null;
        }
        if ($_POST['sci_name'] != ""){
            $this->sci_name = $this->encode($this->convertSentence($_POST['sci_name']));
        }else{
            $this->sci_name = null;
        }
        if ($_POST['credit'] != ""){
            $this->credit = $this->encode($this->convertSentence($_POST['credit']));
        }else{
            $this->credit = null;
        }
        if ($_POST['url'] != ""){
            $this->url = $this->encode($this->convertUrl($_POST['url']));
        }else{
            $this->url = null;
        }
        if ($_POST['country'] != ""){
            $this->country = $this->convertSentence($_POST['country']);
        }else{
            $this->country = null;
        }
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
        if(!is_null($this->data['common_name'])) {
            $this->data['common_name'] = $this->decode($this->data['common_name']);
        }
        if(!is_null($this->data['sci_name'])) {
            $this->data['sci_name'] = $this->decode($this->data['sci_name']);
        }
        if(!is_null($this->data['credit'])) {
            $this->data['credit'] = $this->decode($this->data['credit']);
        }
        if(!is_null($this->data['url'])) {
            $this->data['url'] = $this->decode($this->data['url']);
        }
        $this->data['country'] = $this->decode($this->data['country']);
    }

    private function decodeForSite(){
        $i = 0;
        $decoded = array();
        foreach($this->data as $dat){
            $dat['filename'] = $this->decode($dat['filename']);
            if(!is_null($dat['alt'])){
                $dat['alt'] = $this->decode($dat['alt']);
            }
            if(!is_null($dat['common_name'])) {
                $dat['common_name'] = $this->decode($dat['common_name']);
            }
            if(!is_null($dat['sci_name'])) {
                $dat['sci_name'] = $this->decode($dat['sci_name']);
            }
            if(!is_null($dat['credit'])) {
                $dat['credit'] = $this->decode($dat['credit']);
            }
            if(!is_null($dat['url'])) {
                $dat['url'] = $this->decode($dat['url']);
            }
            $dat['country'] = $this->decode($dat['country']);
            $decoded[$i] = $dat;
            $i++;
        }
        $this->data = $decoded;
    }

    private function savePhoto(){
        $handle = new upload($_FILES['photo']);
        if ($handle->uploaded) {
            $handle->image_resize = true;
            $handle->image_x = $this->width;
            $handle->image_y = $this->height;
            $handle->image_ratio = true;
            $handle->file_name_body_add = time();
            $handle->process(ROOT_DIR . PHOTO);
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

    private function getDataById($id){//update - where id is already known
        $sql = "SELECT * FROM $this->table WHERE `id` = $id";
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
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

    private function getDataByPageId($pageId){//retrieve - where pageId is already known
        $sql = "SELECT * FROM $this->table WHERE `country` = '$pageId'";//TODO: Change country to pageId
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
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
        //create field list and matching params from vars
        $this->params = array(":filename" => $this->filename, ":width" => $this->width, ":height" => $this->height, ":alt" => $this->alt, ":common_name" => $this->common_name, ":sci_name" => $this->sci_name, ":credit" => $this->credit, ":url" => $this->url, ":country" => $this->country);//TODO replace country with pageId
        if($this->id){
            $this->params[':id'] = $this->id;
        }
    }

    /**
     * @return int
     */
    private function update()
    {
        $sql = "UPDATE $this->table SET `filename`=:filename, `width`=:width, `height`=:height, `alt`=:alt, `common_name`=:common_name, `sci_name`=:sci_name, `credit`=:credit, `url`=:url, `country`=:country WHERE `id`=:id";//TODO replace country with pageId
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
        $sql = "INSERT INTO `$this->table` (`filename`, `width`, `height`, `alt`, `common_name`, `sci_name`, `credit`, `url`, `country`)
VALUES (:filename, :width, :height, :alt, :common_name, :sci_name, :credit, :url, :country)";//TODO replace country with pageId
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