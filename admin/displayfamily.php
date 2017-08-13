<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 25/09/2014
 * Time: 12:33
 */

class DisplayFamily extends PrepareInput
{

    protected $id;
    protected $familyname;
    protected $order;
    protected $data;
    protected $count;
    protected $code;
    protected $params;

    public function __construct($id = "")
    {
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
		<title>' . ucwords($this->sectiontitle) . '</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>' . ucwords($this->sectiontitle) . '</h1>';
        return $code;
    }

    public function getPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    public function getDisplayAdminEmpty()
    {
        $this->createDisplayAdminEmpty();
        return $this->code;
    }

    public function getDisplayAdminFull()
    {
        $this->getDataById();
        $this->createDisplayAdminFull();
        return $this->code;
    }

    public function getAdminOptions($mode = "", $id = "")
    {
        $this->createAdminOptions($mode, $id);
        return $this->code;
    }

    public function getProcessResults($mode)
    {
        $result = $this->processForm($mode);
        return $result;
    }

    protected function createDisplayAdmin()
    {
        $content = "";
        $num = 0;
        if ($this->count > 0) {
            $resultsection = new SectionContent();
            $content .= $resultsection->sectionStart($this->sectiontitle, $this->count);
            if ($this->data) {
                foreach ($this->data as $dataItem) {
                    $num++;
                    $content .= $resultsection->itemStart();
                    $content .= $resultsection->itemTitle(decode($dataItem['title']));
                    $content .= $resultsection->itemUrl(decode($dataItem['url1']), decode($dataItem['url1title']));
                    $content .= $resultsection->itemText(decode($dataItem['url1desc']));
                    $this->getAdminOptions("up", $dataItem['id']);
                    $content .= $this->code;
                    $content .= $resultsection->itemEnd();
                }
            }
            $content .= $resultsection->sectionEnd();
        }
        $this->getAdminOptions("add");
        $content .= $this->code;
        $this->code = $content;
    }

    protected function createDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->placeid) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->placeid . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<label>Title:<input name="title" type="text" /></label>';
        $form .= '<label>URL:<input name="url1" type="url" value="http://" /></label>';
        $form .= '<label>URL replacement text:<input name="url1title" type="text" /></label>';
        $form .= '<label>Description of URL:<textarea name="url1desc" class="" cols="50" rows="20"></textarea></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    protected function createDisplayAdminFull()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->id) {
            $form .= '<input type = "hidden" name="id" value = "' . $this->id . '" />';
        }
        if ($this->placeid) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->placeid . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<input name="title" type="text" value="' . $this->title . '" />';
        $form .= '<input name="url1" type="url" value="' . $this->url1 . '" />';
        $form .= '<input name="url1title" type="text" value="' . $this->url1title . '" />';
        $form .= '<textarea name="url1desc" class="" cols="50" rows="20">' . $this->url1desc . '</textarea>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        $this->code = $form;
    }

    protected function createDisplayAdminDelete()
    {
        $resultsection = new SectionContent();
        $content = $resultsection->itemTitle(decode($this->title));
        $content .= $resultsection->itemUrl(decode($this->url1), decode($this->url1title));
        $content .= $resultsection->itemText(decode($this->url1desc));
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        $form .= '<input type = "hidden" name="id" value = "' . $this->id . '" />';
        $form .= '<input type="hidden" name="mode" value="dodelete" />';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Delete" />';
        $form .= '</form>';
        $content .= $form;
        $this->code = $content;
    }

    protected function createAdminOptions($mode, $id = 0)
    {
        $opts = "";
        if ($mode == "add") {
            $opts = '<form action="' . $this->adminfile . '" method="POST" id="adminadd' . $this->dbtable . '">
                <input type="hidden" name="mode" value="add" />
                <input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="submit" value ="Add" />
                </form>';
        } elseif ($mode == "up") {
            $opts = '<form action="' . $this->adminfile . '" method="POST" id="adminupdate' . $this->dbtable . $id . '">
                <input type="hidden" name="mode" value="update" />
                <input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" ?>
                <input type="submit" value ="Update" />
                </form>';
            $opts .= '<form action="' . $this->adminfile . '" method="POST" id="admindelete' . $this->dbtable . $id . '">
                <input type="hidden" name="mode" value="delete" />
                <input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" ?>
                <input type="submit" value ="Delete" />
                </form>';
        }
        $this->code = $opts;
    }

    protected function processForm($mode)
    {
        $obj = new TextOneUrl($this->dbtable);
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
        $title = $this->encode($this->convertSentence($_POST['title']));
        $url1 = $this->encode($this->convertUrl($_POST['url1']));
        $url1title = $this->encode($this->convertSentence($_POST['url1title']));
        $url1desc = $this->encode($this->convertParagraph($_POST['url1desc']));
        if ($mode == "doadd") {
            $this->params = array(
                $title,
                $url1,
                $url1title,
                $url1desc,
                $this->category,
                $this->subcategory,
                $this->placeid
            );
        }
        if ($mode == "doupdate") {
            $this->params = array($title, $url1, $url1title, $url1desc, $this->id);
        }
    }

    protected function getData()
    {
        $dataObject = new Family($this->id);
        $this->data = $dataObject->returnData();
        $this->count = count($this->data);
    }

    protected function getDataById()
    {
        $dataObject = new TextOneUrl($this->dbtable, $this->placeid, $this->category, $this->subcategory, $this->id);
        $data = $dataObject->returnData();
        $count = count($data);
        if ($count == 1) {
            $this->title = $this->decode($data[0]['title']);
            $this->url1 = $this->decode($data[0]['url1']);
            $this->url1title = $this->decode($data[0]['url1title']);
            $this->url1desc = $this->decode($data[0]['url1desc']);
        }
    }

}