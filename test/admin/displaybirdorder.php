<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:58
 */
class DisplayBirdOrder extends PrepareInput
{

    protected $id;
    protected $category = "ornithology";
    protected $subcategory = "";
    protected $scientificName;
    protected $commonName;
    protected $displayOrder;
    protected $pageId = "";
    protected $data;
    protected $count;
    protected $code;
    protected $params;
    protected $adminFile = "adminbirdorder.php";
    protected $table = "birdorder";

    public function __construct($pageId = "", $cat = "", $subcat = "", $id = "")
    {
        if ($pageId <> "") {
            $this->pageId = $pageId;
        } elseif ($cat <> "" && $subcat <> "") {
            $this->category = $cat;
            $this->subcategory = $subcat;
        }
        if ($id != "") {
            $this->id = $id;
        }
    }

    public function getPageStart()
    {
        $code = '<!DOCTYPE html>
<html>';
        $code .= '
	<head>
		<title>Bird Orders</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>Bird Orders</h1>';
        return $code;
    }

    public function getPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    public function getDisplayAdmin()
    {
        $dataObject = new BirdOrder();
        $field = array("id", "scientificname", "commonname", "sub-category", "pageid");
        $data = $dataObject->getData($field);
        $count = count($data);
        $content = "";
        if ($count > 0) {
            if ($data) {
                foreach ($data as $dataItem) {
                    $content .= decode($dataItem['scientificname']) . ' ' . decode($dataItem['commonname']) .  ' ' . decode($dataItem['sub-category']);
                    $content .= $this->getAdminOptions("up", $dataItem['id']);
                    $content .= '<br />';
                }
            }
        }
        $content .= $this->getAdminOptions("add");
        return $content;
    }

    public function getDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->pageId) {
            $form .= '<input name="pageId" type = "hidden" value = "' . $this->pageId . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<label>Display Order:<input name="displayorder" type="number" /></label>';
        $form .= '<label>Scientific Name:<input name="scientificname" type="text" /></label>';
        $form .= '<label>Common Name:<input name="commonname" type="text" /></label>';
        $form .= '<label>Page:<input type="text" name = "subcategory" /></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value="Add new order" />';
        $form .= '</form>';
        return $form;
    }

    public function getDisplayAdminFull()
    {
        $this->getDataById($this->id);
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->id) {
            $form .= '<input type = "hidden" name="id" value = "' . $this->id . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<label>Display Order:<input name="displayorder" type="number" value="' . $this->displayOrder . '" /></label>';
        $form .= '<label>Scientific Name:<input name="scientificname" type="text" value="' . $this->scientificName . '" /></label>';
        $form .= '<label>Common Name:<input name="commonname" type="text" value="' . $this->commonName . '" /></label>';
        $form .= '<label>Page:<input type="text" name = "subcategory" value = "' . $this->subcategory . '" /></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        return $form;
    }

    public function getDisplayAdminDelete()
    {
        $this->getDataById($this->id);
        $content = decode($this->scientificName) . ' ' . decode($this->commonName);
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        $form .= '<input type = "hidden" name="id" value = "' . $this->id . '" />';
        $form .= '<input type="hidden" name="mode" value="dodelete" />';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Delete" />';
        $form .= '</form>';
        $content .= $form;
        return $content;
    }

    public function getAdminOptions($mode = "", $id = 0)
    {
        $opts = "";
        if ($mode == "add") {
            $opts = '<form action="' . $this->adminFile . '" method="POST" id="adminadd' . $this->table . '">
                <input type="hidden" name="mode" value="add" />
                <input type="submit" value ="Add" />
                </form>';
        } elseif ($mode == "up") {
            $opts = '<form action="' . $this->adminFile . '" method="POST" id="adminupdate' . $this->table . $id . '">
                <input type="hidden" name="mode" value="update" />
                <input type="hidden" name="id" value="' . $id . '" />
                <input type="submit" value ="Update" />
                </form>';
            $opts .= '<form action="' . $this->adminFile . '" method="POST" id="admindelete' . $this->table . $id . '">
                <input type="hidden" name="mode" value="delete" />
                <input type="hidden" name="id" value="' . $id . '" />
                <input type="submit" value ="Delete" />
                </form>';
        }
        return $opts;
    }

    public function processForm($mode)
    {
        $obj = new BirdOrder();
        $this->createParams($mode);
        $result = "";
        switch ($mode) {
            case 'doadd':
                $result = $obj->addRecord($this->params);
                break;
            case 'doupdate':
                $result = $obj->updateRecord($this->params);
                break;
            case 'dodelete':
                $result = $obj->deleteRecord($this->params);
                break;
        }
        if ($result === true) {
            $_POST['mode'] = "";
        }
        return $result;
    }

    protected function createParams($mode)
    {
        if ($mode == "dodelete") {
            $this->params = array($this->id);
            return;
        }
        $sName = $this->encode($this->convertSentence($_POST['scientificname']));
        $cName = $this->encode($this->convertSentence($_POST['commonname']));
        $subcat = $this->encode($this->convertSentence(($_POST['subcategory'])));
        if($this->checkInt($_POST['displayorder'])){
            $dOrder = $_POST['displayorder'];
        }else{
            $dOrder = "0";
        }
        if ($mode == "doadd") {
            $this->params = array(
                $sName,
                $cName,
                $dOrder,
                $this->category,
                $subcat,
                $this->pageId
            );
        }
        if ($mode == "doupdate") {
            $this->params = array($sName, $cName, $dOrder, $subcat, $this->id);
        }
    }

    protected function getData()
    {
        $dataObject = new BirdOrder();
        if($this->pageId != ""){
            $where = array("paged" => $this->pageId);
        }else{
            $where = array("category" => $this->category, "sub-category" => $this->subcategory);
        }
        $this->data = $dataObject->getData();
        $this->count = count($this->data);
    }

    protected function getDataById($id)
    {
        $birdOrder = new BirdOrder();
        $data = $birdOrder->getData(null, array("id"=>$id));
        $count = count($data);
        if ($count == 1) {
            $this->scientificName = $this->decode($data[0]['scientificname']);
            $this->commonName = $this->decode($data[0]['commonname']);
            $this->displayOrder = $this->decode($data[0]['displayorder']);
            $this->subcategory = $this->decode($data[0]['sub-category']);
        }
    }

}