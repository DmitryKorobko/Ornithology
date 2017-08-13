<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:58
 */
class DisplayBirdFamily extends PrepareInput
{

    protected $id;
    protected $category = "ornithology";
    protected $subcategory = "";
    protected $scientificName;
    protected $commonName;
    protected $displayOrder;
    protected $birdOrderId;
    protected $pageId = "";
    protected $data;
    protected $count;
    protected $code;
    protected $params;
    protected $adminFile = "adminbirdfamily.php";
    protected $table = "birdfamily";

    /**
     * @param string $pageId
     * @param string $cat
     * @param string $subcat
     * @param string $id
     */
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

    /**
     * @return string
     */
    public function getPageStart()
    {
        $code = '<!DOCTYPE html>
<html>';
        $code .= '
	<head>
		<title>bird family</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>Bird Families</h1>';
        return $code;
    }

    /**
     * @return string
     */
    public function getPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    public function getDisplaySite()
    {
        $birdOrder = new BirdOrder();
        $sqlLinkTable = "SELECT `page_id` FROM `pagename` WHERE `sub-category` = '" . $this->subcategory . "'";
        $result = mysql_query($sqlLinkTable);
        while ($row = mysql_fetch_assoc($result)) {
            $page_id = $row['page_id'];
        }
        $where =array("page_id" => $page_id);

        $data = $birdOrder->getData(array("id"), $where, "displayorder", "ASC");
        if(!empty($data)) {
            $content = "";
            $content .= '<div class="orderlist">';
            foreach ($data as $orderList) {
                $content .= $this->getFamilyListByOrder($orderList['id']);
            }
            $content .= '</div>';
            return $content;
        }else{
            $error = "";//TODO make error a real thing
            return $error;
        }
    }

    public function getFamilyListByOrder($birdOrderId)
    {
        $birdFamily = new BirdFamily();
        $familyData = $birdFamily->getData(
            array("scientificname", "commonname"),
            array("birdorderid" => $birdOrderId),
            "displayorder",
            "ASC"
        );
        if ($familyData != "") {
            $content = '<ul class="birdfamily">';
            foreach ($familyData as $data) {
                $content .= '<li><a href="' . decode(
                        $data['scientificname']
                    ) . '"><span class="scientificname">' . decode(
                        $data['scientificname']
                    ) . '</span> <span class="commonname">' . decode($data['commonname']) . '</span></a></li>';
            }
            $content .= '</ul>';
            return $content;
        }else{
            $error = "";//TODO make error a real thing
            return $error;
        }
    }

    public function getDisplayAdmin()
    {
        $select = "SELECT `page_id` FROM `pagename` WHERE `sub-category` = '" . $this->subcategory . "'";
        $result = mysql_query($select);
        $pageId = "";
        while ($row = mysql_fetch_assoc($result)) { $pageId .= $row['page_id']; }
        $content = "";
        $birdOrder = new BirdOrder();
        $birdFamily = new BirdFamily();
        $fields = array("id", "scientificname");
        $where =array("page_id" => $pageId);
        $orderList = $birdOrder->getData($fields ,$where, "displayorder", "ASC");
        if(!empty($orderList)) {
            $content .= $this->getAdminOptions("add");
            foreach ($orderList as $orderData) {
                $familyList = $birdFamily->getData(
                    array("id", "scientificname", "commonname", "birdorderid"),
                    array("birdorderid" => $orderData['id'])
                );
                $content .= '<h3>' . decode($orderData['scientificname']) . '</h3>';
                $content .= '<table class="admintable"><thead><tr><th>Scientific Name</th><th class="commonname">Common Name</th></tr></thead><tbody>';
                foreach ($familyList as $familyData) {
                    $content .= '<tr><td class="scientificname">' . decode(
                            $familyData['scientificname']
                        ) . '</td><td class="commonname">' . decode($familyData['commonname']) . '</td>';
                    $content .= '<td>' . $this->getAdminOptions("up", $familyData['id']) . '</td></tr>';
                }
                $content .= '</tbody></table><hr />';

            }
            return $content;
        }else{
            $error = "";//TODO make error a real thing
            return $error;
        }
    }

    public function getDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<label>Scientific Name:<input name="scientificname" type="text" /></label>';
        $form .= '<label>Common name:<input name="commonname" type="text" /></label>';
        $form .= '<label>Display order:<input name="displayorder" type="number" /></label>';
        $form .= '<label>Order: ' . $this->getParentOrder() . '</label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value="Add new record" />';
        $form .= '</form>';
        return $form;
    }

    private function getParentOrder($selected = 0){
        $select = "SELECT `page_id` FROM `pagename` WHERE `sub-category` = '" . $this->subcategory . "'";
        $result = mysql_query($select);
        $pageId = "";
        while ($row = mysql_fetch_assoc($result)) { $pageId .= $row['page_id']; }
        $birdOrder = new BirdOrder();
        $birdFamily = new BirdFamily();
        $where = array("page_id" => $pageId);
        $orderList = $birdOrder->getData(array("id", "scientificname"), $where, "displayorder", "ASC");
        if(count($orderList) > 0) {
            $list = '<select name="birdorderid">';
            foreach ($orderList as $orderData) {
                $list .= '<option value = "' . $orderData['id'] . '"';
                if($selected > 0 && $selected == $orderData['id']){
                    $list .= 'selected="selected"';
                }
                $list .= '>' . $orderData['scientificname'] . '</option>';
            }
            $list .= '</select>';
        }
        return $list;
    }

    public function getDisplayAdminFull()
    {
        $field = array("scientificname", "commonname", "displayorder", "birdorderid");
        $where = array("id"=>$this->id);
        $birdFamily = new BirdFamily();
        $familyList = $birdFamily->getData($field, $where);
        foreach ($familyList as $familyItem) {
            $sciName = decode($familyItem['scientificname']);
            $commonName = decode($familyItem['commonname']);
            $disOrder = $familyItem['displayorder'];
            $birdOrderId = $familyItem['birdorderid'];
        }
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->id) {
            $form .= '<input type = "hidden" name="id" value = "' . $this->id . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<input name="scientificname" type="text" value="' . $sciName . '" />';
        $form .= '<input name="commonname" type="text" value="' . $commonName . '" />';
        $form .= '<input name="displayorder" type="number" value="' . $disOrder . '" />';
        $form .= '<label>' . $this->getParentOrder($birdOrderId) . '</label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        return $form;
    }

    public function getDisplayAdminDelete()
    {
        $birdFamily = new BirdFamily();
        $familyList = $birdFamily->getData(array("scientificname"), array("id"=>$this->id));
        $content = '<p class="scientificname">' . decode($familyList[0]['scientificname']) . '</p>';
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
                <input type = "hidden" name = "pageId" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="submit" value ="Add a new family" />
                </form>';
        } elseif ($mode == "up") {
            $opts = '<form action="' . $this->adminFile . '" method="POST" id="adminupdate' . $this->table . $id . '">
                <input type="hidden" name="mode" value="update" />
                <input type = "hidden" name = "pageId" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" />
                <input type="submit" value ="Update" />
                </form>';
            $opts .= '<form action="' . $this->adminFile . '" method="POST" id="admindelete' . $this->table . $id . '">
                <input type="hidden" name="mode" value="delete" />
                <input type = "hidden" name = "pageId" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" />
                <input type="submit" value ="Delete" />
                </form>';
        }
        return $opts;
    }

    public function getGotoAdmin(){
        $form = '<form action="/admin/adminbirdfamily.php" method="POST" id="updatepage">';
        $form .= '<input type = "hidden" name = "pageId" value = "' . $this->pageId . '" />';
        $form .= '<input type = "hidden" name = "category" value = "' . $this->category . '" />';
        $form .= '<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />';
        $form .= '<input type="submit" value="Update" /></form>';
        return $form;
    }

    public function processForm($mode)
    {
        $obj = new BirdFamily();
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
        $birdId = $this->checkInt($_POST['birdorderid']);
        $displayOrder = $this->checkInt($_POST['displayorder']);
        if($birdId < 0 || $displayOrder < 0){
            $error = "";//TODO make error a real thing
            return $error;        }
        if ($mode == "doadd") {
            $this->params = array(
                $sName,
                $cName,
                $displayOrder,
                $birdId
            );
        }
        if ($mode == "doupdate") {
            $this->params = array($sName, $cName, $displayOrder, $birdId, $this->id);
        }
    }

    /**
     *
     */
    protected function getData()
    {
        $dataObject = new BirdFamily($this->pageId, $this->category, $this->subcategory, $this->id);
        $this->data = $dataObject->returnData();
        $this->count = count($this->data);
    }

    /*protected function getDataById()
    {
        $dataObject = new BirdFamily($this->pageId, $this->category, $this->subcategory, $this->id);
        $data = $dataObject->returnData();
        $count = count($data);
        if ($count == 1) {
            $this->scientificName = $this->decode($data[0]['scientificname']);
            $this->commonName = $this->decode($data[0]['commonname']);
            $this->birdOrderId = $this->decode($data[0]['birdorderid']);
        }
    }*/

}