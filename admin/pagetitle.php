<?php

class Pagetitle
{

    private $page_id;
    private $title;
    private $category;
    private $subcategory;
    private $table = "pagename";
    private $data;

    public function __construct($cat = "", $subcat = "", $page_id = "")
    {
        if ($cat <> "" && $subcat <> "") {
            $this->category = $cat;
            $this->subcategory = $subcat;
        }
        if ($page_id != "") {
            $this->page_id = $page_id;
        }
    }

    public function returnData($orderby = "", $order = "", $limit = "")
    {
        $this->getData($orderby, $order, $limit);
        return $this->data;
    }

    public function addRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'INSERT INTO `' . $this->table . '` (`title`, `category`, `sub-category`) VALUES (?, ?, ?)';
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return $success;
        } else {
            echo $result->errorinfo();
            return $success;
        }
    }

    public function updateRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'UPDATE `' . $this->table . '` SET `title`=? WHERE `page_id`=?';
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return $success;
        } else { echo 'not'; exit;
            echo $result->errorinfo();
            return $success;
        }
    }

    public function deleteRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'DELETE FROM `' . $this->table . '` WHERE `page_id`=?';
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return $success;
        } else {
            echo $result->errorinfo();
            return $success;
        }
    }

    private function getData($orderby = "", $order = "", $limit = "")
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        //where
        if ($this->category <> "" && $this->subcategory <> "") {
            $sql .= ' WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = "' . $this->category . '" AND `sub-category` = "' . $this->subcategory . '")';
        }
        if ($orderby != "") {
            $sql .= ' ORDER BY ' . $orderby . ' ' . $order;
        }
        if ($limit != "") {
            $sql .= ' LIMIT ' . $limit;
        }
        $result = $db->prepare($sql);
        $result->execute();
        if ($result->rowCount() > 0) {
            $this->data = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }

}


class TitleDisplay extends ConvertInput
{

    protected $page_id;
    protected $category = "";
    protected $subcategory = "";
    protected $title;
    private $code;

    public function __construct($cat = "", $subcat = "", $page_id = "")
    {
        if ($cat <> "" && $subcat <> "") {
            $this->category = $cat;
            $this->subcategory = $subcat;
        }
        if ($page_id != "") {
            $this->page_id = $page_id;
        }
    }

    public function getPageStart()
    {
        $code = '<!DOCTYPE html>
<html>';
        $code .= '
	<head>
		<title>' . ucwords("title") . '</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code = '<body>
		<h1>' . ucwords("title") . '</h1>';
        return $code;
    }

    public function getPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    public function getDisplaySite()
    {
        $this->getData();
        $this->createDisplaySite();
        return $this->code;
    }

    public function getDisplayAdmin()
    {
        $this->getData();
        $this->createDisplayAdmin();
        return $this->code;
    }

    public function getDisplayDelete()
    {
        $this->getData();
        $this->createDisplayDelete();
        return $this->code;
    }

    public function getDisplayAdminEmpty()
    {
        $this->createDisplayAdminEmpty();
        return $this->code;
    }

    public function getAdminOptions($mode = "")
    {
        $this->createAdminOptions($mode);
        return $this->code;
    }

    public function getProcessResults($mode)
    {
        $result = $this->processForm($mode);
        return $result;
    }

    private function createDisplaySite()
    {
        $resultsection = new SectionContent;
        $pagetitle = $resultsection->introHeading($this->title);
        $this->code = $pagetitle;
    }

    private function createDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="titleform">';
        $form .= '<input type = "hidden" name = "page_id" value = "add" />';
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<input name="title" type="text" />';
//        $form .= '<textarea name="text" class="" cols="50" rows="20"></textarea>';
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    private function createDisplayAdmin()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="titleform">';
        if ($this->page_id) {
            $form .= '<input type = "hidden" name="page_id" value = "' . $this->page_id . '" />';
        }
        if ($this->category) {
            $form .= '<p>Category: </p><input name="category" type = "text" value = "' . $this->category . '" />';
            $form .= '<p>Sub-Category: </p><input name="subcategory" type = "text" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<p>Page title: </p><input name="title" type="text" value="' . $this->title . '" />';
//        $form .= '<textarea name="text" class="" cols="50" rows="20">' . $this->text . '</textarea>';
        $form .= '<p><input value="Update" type="Submit" /></p>';
        $form .= '</form>';
        $this->code = $form;
    }

    private function createDisplayDelete()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="titleform">';
        if ($this->page_id) {
            $form .= '<input type = "hidden" name="page_id" value = "' . $this->page_id . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="dodelete" />';
        $form .= '<input name="title" type="text" value="' . $this->title . '" />';
//        $form .= '<textarea name="text" class="" cols="50" rows="20">' . $this->text . '</textarea>';
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    private function createAdminOptions($mode = "")
    {
        if ($mode == "") {
            if (!$this->page_id) {
                $opts = '<form action="../title.php" method="POST" id="titleadd">
					<input type="hidden" name="mode" value="add" />					
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="submit" value ="Add" />
					</form>';
            } else {
                $opts = '<form action="../title.php" method="POST" id="titleupdate">
					<input type="hidden" name="mode" value="update" />
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="hidden" name="page_id" value="' . $this->page_id . '" ?>
					<input type="submit" value ="Update" />
					</form>';
                $opts .= '<form action="../title.php" method="POST" id="titledelete">
					<input type="hidden" name="mode" value="delete" />
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="hidden" name="page_id" value="' . $this->page_id . '" ?>
					<input type="submit" value ="Delete" />
					</form>';
            }
        }
        $this->code = $opts;
    }

    private function processForm($mode)
    {
        $pagetitle = new Pagetitle();
        $this->createParams($mode);
        switch ($mode) {
            case 'doadd':
                $result = $pagetitle->addRecord($this->params);
                break;
            case 'doupdate':
                $result = $pagetitle->updateRecord($this->params);
                break;
            case 'dodelete':
                $result = $pagetitle->deleteRecord($this->params);
                break;
        }
        if ($result === true) {
            $_POST['mode'] = "";
        }
        return $result;
    }

    private function createParams($mode)
    {
        if ($mode == "dodelete") {
            $this->params = array($this->page_id);
            return;
        }
        $title = $this->encode($this->convertForInput($_POST['title']));

        if ($mode == "doadd") {
            $this->params = array($title, $this->category, $this->subcategory);
        }
        if ($mode == "doupdate") {
            $this->params = array($title, $this->page_id);
        }
    }

    private function getData()
    {
        //decode all $data and insert into relevant variables
        $pagetitle = new Pagetitle($this->category, $this->subcategory);
        $this->data = $pagetitle->returnData();
        if ($this->data) {
            foreach ($this->data as $data) {
                $this->page_id = $data['page_id'];
                $this->title = decode($data['title']);
            }
        }
    }

}