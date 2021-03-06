<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 29/09/2014
 * Time: 17:11
 */
class DisplayIntroduction extends ConvertInput
{

    protected $id;
    protected $category = "";
    protected $subcategory = "";
    protected $heading;
    protected $text;
    protected $placeid = "";
    private $code;

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
		<title>' . ucwords("introduction") . '</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>' . ucwords("introduction") . '</h1>';
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
        $intro = $resultsection->introHeading($this->heading);
        $intro .= $resultsection->intro($this->text, $this->category);
        $this->code = $intro;
    }

    private function createDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="introform">';
        $form .= '<input type = "hidden" name = "id" value = "add" />';
        if ($this->placeid) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->placeid . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<input name="heading" type="text" />';
        $form .= '<textarea name="text" class="" cols="50" rows="20"></textarea>';
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    private function createDisplayAdmin()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="introform">';
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
        $form .= '<input name="heading" type="text" value="' . $this->heading . '" />';
        $form .= '<textarea name="text" class="" cols="50" rows="20">' . $this->text . '</textarea>';
        $form .= '<input type="Submit" />';
        $form .= '</form>';
        $this->code = $form;
    }

    private function createAdminOptions($mode = "")
    {
        if ($mode == "") {
            if (!$this->id) {
                $opts = '<form action="../introduction.php" method="POST" id="introadd">
					<input type="hidden" name="mode" value="add" />
					<input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="submit" value ="Add" />
					</form>';
            } else {
                $opts = '<form action="../introduction.php" method="POST" id="introupdate">
					<input type="hidden" name="mode" value="update" />
					<input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="hidden" name="id" value="' . $this->id . '" ?>
					<input type="submit" value ="Update" />
					</form>';
                $opts .= '<form action="../introduction.php" method="POST" id="introdelete">
					<input type="hidden" name="mode" value="delete" />
					<input type = "hidden" name = "placeid" value = "' . $this->placeid . '" />
					<input type = "hidden" name = "category" value = "' . $this->category . '" />
					<input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
					<input type="hidden" name="id" value="' . $this->id . '" ?>
					<input type="submit" value ="Delete" />
					</form>';
            }
        }
        $this->code = $opts;
    }

    private function processForm($mode)
    {
        $intro = new Introduction();
        $this->createParams($mode);
        switch ($mode) {
            case 'doadd':
                $result = $intro->addRecord($this->params);
                break;
            case 'doupdate':
                $result = $intro->updateRecord($this->params);
                break;
            case 'dodelete':
                $result = $intro->deleteRecord($this->params);
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
            $this->params = array($this->id);
            return;
        }
        $heading = $this->encode($this->convertForInput($_POST['heading']));
        $text = $_POST['text'];
        if (strpos($text, "<p>") !== 0) {
            $text = '<p>' . $text . "</p>";
        }
        $text = $this->encode($this->convertForInput($text));
        if ($mode == "doadd") {
            $this->params = array($heading, $text, $this->category, $this->subcategory, $this->placeid);
        }
        if ($mode == "doupdate") {
            $this->params = array($heading, $text, $this->id);
        }
    }

    private function getData()
    {
        //decode all $data and insert into relevant variables
        $intro = new Introduction($this->placeid, $this->category, $this->subcategory);
        $this->data = $intro->returnData();
        if ($this->data) {
            foreach ($this->data as $data) {
                $this->id = $data['id'];
                $this->heading = decode($data['heading']);
                $this->text = decode($data['text']);
            }
        }
    }
}