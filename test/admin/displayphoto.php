<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 24/02/2015
 * Time: 20:03
 */
class displayPhoto
{
    private $mode;
    private $data = array();
    private $adminFile;
    private $table;
    private $pageId;

    public function __construct($mode, $data = "", $pageId = ""){
        $this->table = "photo";
        $this->adminFile = "../adminphoto.php";
        $this->mode = $mode;
        if($data) {
            $this->data = $data;
        }
        if($pageId){
            $this->pageId = $pageId;
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
		<title>Photo</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        $code .= '<body>
		<h1>Photo</h1>';
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
        $code = "";
        if($this->data) {
            foreach ($this->data as $dat) {
                $code .= $this->createDisplay($dat);
                $code .= $this->displayAdminOptions("up", $dat['id']);
            }
        }else {
            $code = $this->displayAdminOptions("add");
        }
        return $code;
    }

    /**
     * @param $dat
     * @return string
     */
    private function createDisplay($dat)
    {
        $code = '<figure class="photo">';
        $code .= '<img src="' . SITE_ROOT . PHOTO . $dat['filename'] . '" height="' . $dat['height'] . '" width = "' . $dat['width'] . '" alt="' . $dat['alt'] . '" />';
        $code .= '<figcaption style="width:' . $dat['width'] . 'px;">';
        if ($dat['common_name']) {
            $code .= '<span class="bold">' . $dat['common_name'] . '</span>';
        }
        if ($dat['sci_name']) {
            $code .= ' <span class="scientificname">' . $dat['sci_name'] . '</span>';
        }
        if ($dat['credit']) {
            $code .= " &#169" . $dat['credit'];
        }
        if ($dat['url']) {
            $code .= ' <a href="' . $dat['url'] . '">Website</a>';
        }
        $code .= '</figcaption>';
        $code .= "</figure>";
        return $code;
    }

    private function displayEmptyForm()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform" enctype="multipart/form-data">';
        $form .= '<input name="pageId" type = "hidden" value = "' . $this->pageId . '" />';
        $form .= '<input name="country" type = "hidden" value = "' . $this->pageId . '" />';
        $form .= '<input type="hidden" name="mode" value="doadd" />';
        $form .= '<input type="file" name="photo" accept="image/*" />';
        $form .= '<label>Width<input type="number" name="width" value="" required /></label>';
        $form .= '<label>Height<input type="number" name="height" value="" required /></label>';
        $form .= '<p>Alt = a brief description of the picture</p>';
        $form .= '<label>Alt:<input name="alt" type="text" /></label>';
        $form .= '<label class="scientificname">Scientific Name:<input name="sci_name" type="text" class="scientificname" /></label>';
        $form .= '<label class="bold">Common Name:<input name="common_name" type="text" class="bold" /></label>';
        $form .= '<label>Credit:<input name="credit" type="text" /></label>';
        $form .= '<label>URL:<input name="url" type="url" /> MUST start with http:// or https://</label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value="Add new order" />';
        $form .= '</form>';
        return $form;
    }

    private function displayFilledForm()
    {
        $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" id="adminform' . $this->data['id'] . '" enctype="multipart/form-data">';
        $form .= '<input type = "hidden" name="id" value = "' . $this->data['id'] . '" />';
        $form .= '<input type="hidden" name="mode" value="doupdate" />';
        $form .= '<img src="' . SITE_ROOT . PHOTO . $this->data['filename'] . '" height="' . $this->data['height'] . '" width = "' . $this->data['width'] . '" alt="' . $this->data['alt'] . '" />';
        $form .= '<label>Current filename: <input type = "text" name="filename" value="' . $this->data['filename'] . '" readonly /></label>';
        $form .= '<label>Photo<input type="file" name="photo" accept="image/*" /></label>';
        $form .= '<p>To change height or width, you MUST re-upload the photo above</p>';
        $form .= '<label>Width<input type="number" name="width" value="' . $this->data['width'] . '" required /></label>';
        $form .= '<label>Height<input type="number" name="height" value="' . $this->data['height'] . '" required /></label>';
        $form .= '<label>Alt - a brief description of the picture:<input name="alt" type="text" value="' . $this->data['alt'] . '" /></label>';
        $form .= '<label class="scientificname">Scientific Name:<input name="sci_name" type="text" value="' . $this->data['sci_name'] . '" class="scientificname" /></label>';
        $form .= '<label class="bold">Common Name:<input name="common_name" type="text" value="' . $this->data['common_name'] . '" class="bold" /></label>';
        $form .= '<label>Credit:<input name="credit" type="text" value = "' . $this->data['credit'] . '" /></label>';
        $form .= '<label>URL - MUST start with http:// or https://<input name="url" type="url" value="' . $this->data['url'] . '" /></label>';
        $form .= '<label>Page:<input type="text" name = "country" value = "' . $this->data['country'] . '" /></label>';
        //TODO: Add cancel button that returns to original page
        $form .= '<input type="Submit" value = "Update" />';
        $form .= '</form>';
        return $form;
    }

    private function displayDeleteForm()
    {
        $content = '<p><span class="scientificname">' . $this->data['sci_name'] . '</span><br /><span class="bold">' . $this->data['common_name'] . '</span></p>';
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
                <input type="hidden" name="subcategory" value = "' . $this->pageId . '" />
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

}