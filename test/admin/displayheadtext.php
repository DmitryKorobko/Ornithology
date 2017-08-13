<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:58
 */
abstract class DisplayHeadText extends PrepareInput
{

    protected $id;
    protected $category = "";
    protected $subcategory = "";
    protected $heading;
    protected $paratext;
    protected $placeid = "";
    protected $data;
    protected $count;
    protected $code;
    protected $params;

    public function __construct($placeid = "", $cat = "", $subcat = "", $id = "")
    {
        if ($placeid <> "") {
            $this->placeid = $placeid;
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

    public function getDisplayAdminDelete()
    {
        $this->getDataById();
        $this->createDisplayAdminDelete();
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

    protected function createDisplaySite()
    {
        $content = "";
        if ($this->count > 0) {
            $resultsection = new SectionContent();
            if ($this->data) {
                foreach ($this->data as $dataItem) {
                    $content .= $resultsection->itemTitle(decode($dataItem['heading']));
                    $content .= $resultsection->itemText(decode($dataItem['text']));
                }
            }
            $this->code = $content;
        }
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
                    $content .= $resultsection->itemTitle(decode($dataItem['heading']));
                    $content .= $resultsection->itemText(decode($dataItem['text']));
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
        if (is_numeric($this->placeid)) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->placeid . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<label>Heading:<input name="heading" type="text" /></label>';
        $form .= '<label>Text:<textarea name="text" class="" cols="50" rows="20"></textarea></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    protected function createDisplayAdminFull()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if (is_numeric($this->id)) {
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
        $form .= '<input name="heading" type="text" value="' . $this->heading . '" />';
        $form .= '<textarea name="text" class="" cols="50" rows="20">' . $this->paratext . '</textarea>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        $this->code = $form;
    }

    protected function createDisplayAdminDelete()
    {
        $resultsection = new SectionContent();
        $content = $resultsection->itemTitle(decode($this->heading));
        $content .= $resultsection->itemText(decode($this->paratext));
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
        $obj = new HeadText($this->dbtable);
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
        $head = $this->encode($this->convertSentence($_POST['heading']));
        $text = $this->encode($this->convertParagraph($_POST['text']));
        if ($mode == "doadd") {
            $this->params = array(
                $head,
                $text,
                $this->category,
                $this->subcategory,
                $this->placeid
            );
        }
        if ($mode == "doupdate") {
            $this->params = array($head, $text, $this->id);
        }
    }

    protected function getData()
    {
        $dataObject = new HeadText($this->dbtable, $this->placeid, $this->category, $this->subcategory, $this->id);
        $this->data = $dataObject->returnData();
        $this->count = count($this->data);
    }

    protected function getDataById()
    {
        $dataObject = new HeadText($this->dbtable, $this->placeid, $this->category, $this->subcategory, $this->id);
        $data = $dataObject->returnData();
        $count = count($data);
        if ($count == 1) {
            $this->heading = $this->decode($data[0]['heading']);
            $this->paratext = $this->decode($data[0]['text']);
        }
    }

}