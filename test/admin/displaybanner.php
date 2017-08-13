<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:58
 */
class DisplayBanner
{
    private $mode;
    private $data = array();
    private $adminFile;
    private $table;
    private $category;
    private $subcategory;

    public function __construct($mode, $data = "", $category = "", $subcategory = ""){
        $this->table = "banner";
        $this->adminFile = "../adminbanner.php";
        $this->mode = $mode;
        if($data) {
            $this->data = $data;
        }
        if($category){
            $this->category = $category;
            $this->subcategory = $subcategory;
        }
    }

    public function displayContent(){
        $content = "";
        switch($this->mode){
            case "site":
                $content .= $this->displaySite();
                break;
            case "admin":
                $content .= $this->displayAdmin();
                break;
            case "add":
                $content .= $this->displayPageStart();
                $content .= $this->displayEmptyForm();
                $content .= $this->displayPageEnd();
                break;
            case "update":
                $content .= $this->displayPageStart();
                $content .= $this->displayFilledForm();
                $content .= $this->displayPageEnd();
                break;
            case "delete":
                $content .= $this->displayPageStart();
                $content .= $this->displayDeleteForm();
                $content .= $this->displayPageEnd();
                break;
        }
        return $content;
    }

    private function displayPageStart()
    {
        $code = '<!DOCTYPE html>
<html>';
        $code .= '
	<head>
		<title>Banner</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>Banner</h1>';
        return $code;
    }

    private function displayPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    private function displaySite()
    {
        $code = "";
        if($this->data) {
            foreach ($this->data as $dat) {
                $code .= $this->createDisplay($dat);
            }
        }
        return $code;
    }

    private function displayAdmin()
    {
        $code = $this->displayAdminOptions("add");
        if($this->data) {
            foreach ($this->data as $dat) {
                $code .= $this->createDisplay($dat);
                $code .= $this->displayAdminOptions("up", $dat['id']);
            }
        }
        return $code;
    }

    /**
     * @param $dat
     * @return string
     */
    private function createDisplay($dat)
    {
        $code = '<div class = "bannerAdBox">';
        $code .= '<p class="sponsor_title">This page is sponsored by...<span class="sponsor_name">' . $dat['sponsor'] . '</span></p>';
        $code .= '<figure class="banner">';
        if ($dat['url1']) {
            $code .= '<a href="' . $dat['url1'] . '">';
        }
        $code .= '<img src="' . SITE_ROOT . BANNERIMGDIR . $dat['filename'] . '" height="' . $dat['height'] . '" width = "' . $dat['width'] . '" alt="' . $dat['alt'] . '" />';
        if ($dat['url1']) {
            $code .= '</a>';
        }
        /*if ($dat['url1']) {
            $code .= '<figcaption style="width:' . $dat['width'] . 'px;">';
            $code .= '<a href="' . $dat['url1'] . '">';
            if ($dat['url1title']) {
                $code .= $dat['url1title'];
            } else {
                $code .= $dat['url1'];
            }
            $code .= '</a>';
            $code .= '</figcaption>';
        }*/
        $code .= "</figure>";
        if ($dat['bannertext']) {
            $code .= $dat['bannertext'];
        }
        $code .= '</div>';
        return $code;
    }

    private function displayEmptyForm()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform" enctype="multipart/form-data">';
        //$form .= '<input name="pageId" type = "hidden" value = "' . $this->pageId . '" />';
        $form .= '<input type="hidden" name="category" value = "' . $this->category . '" />';
        $form .= '<input type="hidden" name="subcategory" value = "' . $this->subcategory . '" />';
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<input type="file" name="photo" accept="image/*" />';
        $form .= '<label>Check this box if the image is animated<input type="checkbox" name="animated" value="1" /></label>';
        $form .= '<label>Width<input type="number" name="width" value="" required /></label>';
        $form .= '<label>Height<input type="number" name="height" value="" required /></label>';
        $form .= '<label>Alt - a brief description of the picture:<input name="alt" type="text" /></label>';
        $form .= '<label>URL - MUST start with http:// or https://<input name="url1" type="url" /></label>';
        $form .= '<label>URL title:<input name="url1title" type="text" /></label>';
        $form .= '<label>Sponsor:<input name="sponsor" type="text" /></label>';
        $form .= '<label>Text:<textarea name="bannertext"></textarea></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value="Add new order" />';
        $form .= '</form>';
        return $form;
    }

    private function displayFilledForm()
    {
        $form = "";
        $form .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform' . $this->data['id'] . '" enctype="multipart/form-data">';
        $form .= '<input type = "hidden" name="id" value = "' . $this->data['id'] . '" />';
        $form .= '<input type="hidden" name="category" value = "' . $this->data['category'] . '" />';
        $form .= '<input type="hidden" name="subcategory" value = "' . $this->data['sub-category'] . '" />';
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<img src="' . SITE_ROOT . BANNERIMGDIR . $this->data['filename'] . '" height="' . $this->data['height'] . '" width = "' . $this->data['width'] . '" alt="' . $this->data['alt'] . '" />';
        $form .= '<label>Current filename: <input type = "text" name="filename" value="' . $this->data['filename'] . '" readonly /></label>';
        $form .= '<label>Banner<input type="file" name="photo" accept="image/*" /></label>';
        $form .= '<label>Check this box if the NEW image is animated<input type="checkbox" name="animated" value="1" /></label>';
        $form .= '<p>To change height or width, you MUST re-upload the Banner above</p>';
        $form .= '<label>Width<input type="number" name="width" value="' . $this->data['width'] . '" required /></label>';
        $form .= '<label>Height<input type="number" name="height" value="' . $this->data['height'] . '" required /></label>';
        $form .= '<label>Alt - a brief description of the picture:<input name="alt" type="text" value="' . $this->data['alt'] . '" /></label>';
        $form .= '<label>URL - MUST start with http:// or https://<input name="url1" type="url" value="' . $this->data['url1'] . '" /></label>';
        $form .= '<label>URL title:<input name="url1title" type="text" value="' . $this->data['url1title'] . '" /></label>';
        $form .= '<label>Sponsor:<input name="sponsor" type="text" value="' . $this->data['sponsor'] . '" /></label>';
        $form .= '<label>Text:<textarea name="bannertext">' . $this->data['bannertext'] . '</textarea></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        return $form;
    }

    private function displayDeleteForm()
    {
        $content = '<p>' . $this->data['sponsor'] . '</p>';
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform' . $this->data['id'] . '">';
        $form .= '<input type = "hidden" name="id" value = "' . $this->data['id'] . '" />';
        $form .= '<input type="hidden" name="mode" value="dodelete" />';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Delete" />';
        $form .= '</form>';
        $content .= $form;
        return $content;
    }

    private function displayAdminOptions($mode = "", $id = "")
    {
        $opts = "";
        if ($mode == "add") {
            $opts = '<form action="' . $this->adminFile . '" method="POST" id="adminadd' . $this->table . '">
                <input type="hidden" name="mode" value="add" />
                <input type="hidden" name="category" value = "' . $this->category . '" />
                <input type="hidden" name="subcategory" value = "' . $this->subcategory . '" />
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
    /*
        protected $id;
        protected $category = "";
        protected $subcategory = "";
        protected $filename;
        protected $url1;
        protected $url1title;
        protected $sponsor;
        protected $text;
        protected $width;
        protected $height;
        protected $pageId = "";
        protected $data;
        protected $count;
        protected $code;
        protected $params;

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

       /* public function getDisplayAdmin()
        {
            $this->getData();
            $this->createDisplayAdmin();
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
        }*/
/*
    public function getDisplaySite()
    {
        $content = "";
        $banner = new Banner();
        $where = array("category" => $this->category, "sub-category" => $this->subcategory);
        $data = $banner->getData(null, $where);
            if (!empty($data)){
                $content .= '<div class = "banners">';
                foreach ($data as $dataItem) {
                    $content .= '<div class="banneritem">';
                    if ($dataItem["sponsor"]) {
                        $content .= '<p class="sponsor_title">This page is sponsored by...<span class="sponsor_name">';
                        if ($dataItem["url1_title"]) {
                            $content .= decode($dataItem["url1_title"]);
                        } else {
                            $content .= decode($dataItem["sponsor"]);
                        }
                        $content .= '</span></p>';
                    }
                    if ($dataItem["url1"]) {
                        $content .= '<p><a href="' . decode($dataItem["url1"]) . '"><img class="banner_link" src="' . SITE_ROOT . BANNERIMGDIR . decode($dataItem["filename"]) . '" width="' . $dataItem["width"] . '" height="' . $dataItem["height"] . '"></a></p>';
                    }
                    if ($dataItem["text"]) {
                        $content .= '<p class="sponsor_text">' . decode($dataItem["text"]) . '</p>';
                    }
                    $content .= '</div>';
                }
                $content .= '</div>';
            }
            return $content;
    }

    public function getDisplayAdmin()
    {
        $content = $this->getDisplaySite();
        $content .= $this->getAdminOptions();
        return $content;
    }

    public function getDisplayAdminEmpty()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform">';
        if ($this->pageId) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->pageId . '" />';
        }
        if ($this->category) {
            $form .= '<input name="category" type = "hidden" value = "' . $this->category . '" />';
            $form .= '<input name="subcategory" type = "hidden" value = "' . $this->subcategory . '" />';
        }
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<label>Sponsor:<input name="sponsor" type="text" /></label>';
        $form .= '<label>Clickthrough URL:<input name="url1" type="url" value="http://" /></label>';
        $form .= '<label>URL replacement text:<input name="url1title" type="text" /></label>';
        $form .= '<label>Text:<textarea name="text" class="" cols="50" rows="20"></textarea></label>';
        $form .= '<label>Filename:<';
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
        if ($this->pageId) {
            $form .= '<input name="placeid" type = "hidden" value = "' . $this->pageId . '" />';
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
                <input type = "hidden" name = "placeid" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="submit" value ="Add" />
                </form>';
        } elseif ($mode == "up") {
            $opts = '<form action="' . $this->adminfile . '" method="POST" id="adminupdate' . $this->dbtable . $id . '">
                <input type="hidden" name="mode" value="update" />
                <input type = "hidden" name = "placeid" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" ?>
                <input type="submit" value ="Update" />
                </form>';
            $opts .= '<form action="' . $this->adminfile . '" method="POST" id="admindelete' . $this->dbtable . $id . '">
                <input type="hidden" name="mode" value="delete" />
                <input type = "hidden" name = "placeid" value = "' . $this->pageId . '" />
                <input type = "hidden" name = "category" value = "' . $this->category . '" />
                <input type = "hidden" name = "subcategory" value = "' . $this->subcategory . '" />
                <input type="hidden" name="id" value="' . $id . '" ?>
                <input type="submit" value ="Delete" />
                </form>';
        }
        $this->code = $opts;
    }

    public function processForm($mode)
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
                $this->pageId
            );
        }
        if ($mode == "doupdate") {
            $this->params = array($title, $url1, $url1title, $url1desc, $this->id);
        }
    }

    protected function getData()
    {
        $where = array("category" => $this->category, "sub-category" => $this->subcategory);
        $dataObject = new Banner();
        return $dataObject->returnData();
    }

    protected function getDataById()
    {
        $dataObject = new TextOneUrl($this->dbtable, $this->pageId, $this->category, $this->subcategory, $this->id);
        $data = $dataObject->returnData();
        $count = count($data);
        if ($count == 1) {
            $this->title = $this->decode($data[0]['title']);
            $this->url1 = $this->decode($data[0]['url1']);
            $this->url1title = $this->decode($data[0]['url1title']);
            $this->url1desc = $this->decode($data[0]['url1desc']);
        }
    }
*/
}