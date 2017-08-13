<?php

/**
 *
 */
class Adverts extends PrepareInput
{


    private $data;
    private $num;
    private $side;
    private $table;
    private $stmt;
    private $maxheight = 500;
    private $minheight = 90;
    private $maxwidth = 130;
    private $minwidth = 125;
    private $filename;
    private $animated;
    private $imagewidth;
    private $imageheight;
    private $targeturl;
    private $adorder;
    private $cssid;
    private $cssclass;

    public function __construct($side)
    {
        if ($side == "left") {
            $this->side = "left";
            $this->table = "adverts_left";
        } else {
            if ($side == "right") {
                $this->side = "right";
                $this->table = "adverts_right";
            }
        }
    }

    public function addButton()
    {
        $code = '<form id="newad" action="" method="POST">';
        $code .= '<input type="hidden" name="action" value="add" />';
        $code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
        $code .= '<input type="submit" value = "Add new Ad" />';
        $code .= '</form>';
        return $code;
    }

    public function displayAds()
    {
        $this->getMaxOrder();
        $code = "";
        $numrow = $this->stmt->rowCount();
        if ($numrow > 0) {
            $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
            $max = $row['ad_order'];
            for ($x = 1; $x <= $max; $x++) {
                $this->getDataByOrder($x);
                $this->num = $this->stmt->rowCount();
                if ($this->num > 0) {
                    if ($this->num > 1) {
                        $rndmax = $this->num - 1;
                        $rnd = rand(0, $rndmax);
                        $row = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
                        $code .= '<a href="' . $this->decode(
                                $row[$rnd]["target_url"]
                            ) . '"><img class="adimage" src="' . SITE_ROOT . ADIMGDIR . $this->decode(
                                $row[$rnd]['image']
                            ) . '" width = "' . $row[$rnd]['width'] . '" height = "' . $row[$rnd]['height'] . '" alt="" /></a>';
                    } else {
                        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
                        $code .= '<a href="' . $this->decode(
                                $row["target_url"]
                            ) . '"><img class="adimage" src="' . SITE_ROOT . ADIMGDIR . $this->decode(
                                $row['image']
                            ) . '" width = "' . $row['width'] . '" height = "' . $row['height'] . '" alt="" /></a>';
                    }
                }

            }
        }
        return $code;
    }

    public function displayAdmin($maxwidth)
    {
        $this->getData();
        $this->num = $this->stmt->rowCount();
        $code = "";
        if ($this->num > 0) {
            while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
                $code .= '<div class="aditem">';
                $code .= '<image class="adimage" src="' . SITE_ROOT . ADIMGDIR . $this->decode(
                        $row['image']
                    ) . '" width = "' . $row['width'] . '" height = "' . $row['height'] . '" alt="" />';
                $code .= '<form id="ad' . $row['id'] . '" action="" method="POST" enctype="multipart/form-data">';
                $code .= '<input type="hidden" name="action" value="update" />';
                $code .= '<input type="hidden" name = "adid" value = "' . $row['id'] . '" />';
                $code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
                $code .= '<input type="file" name="adpic" accept="image/*" />';
                $code .= '<p><label>Filename<input type="text" name="filename" value="' . $this->decode(
                        $row["image"]
                    ) . '" readonly /></label></p>';
                $code .= '<p><label>Check this box if the image is animated<input type="checkbox" name="animated" value="1" /></label></p>';
                $code .= '<p><label>Advert Width<input type="number" min="' . $this->minwidth . '" max="' . $this->maxwidth . '" name="adwidth" value="' . $row["width"] . '" /></label></p>';
                $code .= '<p><label>Advert Height<input type="number" min="' . $this->minheight . '" max="' . $this->maxheight . '" name="adheight" value="' . $row["height"] . '" /></label></p>';
                $code .= '<p><label>Link url<input type="url" name="target_url" value="' . $this->decode(
                        $row["target_url"]
                    ) . '" /></label></p>';
                $code .= '<p><label>Advert order<input type="number" min="1" name="ad_order" value="' . $row["ad_order"] . '" /></label></p>';
                $code .= '<p><label>#id<input type="text" name="css_id" value="' . $this->decode(
                        $row["css_id"]
                    ) . '" /></label></p>';
                $code .= '<p><label>.class<input type="text" name="css_class" value="' . $this->decode(
                        $row["css_class"]
                    ) . '" /></label></p>';
                $code .= '<p><input type="submit" value="Update" /></p>';
                $code .= '</form>';
                $code .= '<form id="delad" action="" method ="POST">';
                $code .= '<input type="hidden" name="action" value="del" />';
                $code .= '<input type="hidden" name = "adid" value = "' . $row['id'] . '" />';
                $code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
                $code .= '<input type="submit" value = "Delete this Ad" />';
                $code .= '</form></div>';
            }
        }
        return $code;
    }

    public function newAd()
    {
        $code = '<div class="aditem">';
        $code .= '<form id="adnew" action="" method="POST" enctype="multipart/form-data">';
        $code .= '<input type="hidden" name="action" value="addnewad" />';
        $code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
        $code .= '<input type="file" name="adpic" accept="image/*" />';
        $code .= '<p><label>Check this box if the image is animated<input type="checkbox" name="animated" value="1" /></label></p>';
        $code .= '<p><label>Advert Width<input type="number" min="' . $this->minwidth . '" max="' . $this->maxwidth . '" name="adwidth" value="" required /></label></p>';
        $code .= '<p><label>Advert Height<input type="number" min="' . $this->minheight . '" max="' . $this->maxheight . '" name="adheight" value="" required /></label></p>';
        $code .= '<p><label>Link url<input type="url" name="target_url" value="http://" required /></label></p>';
        $code .= '<p><label>Advert order<input type="number" min="1" name="ad_order" value="" required /></label></p>';
        $code .= '<p><label>#id<input type="text" name="css_id" value="" /></label></p>';
        $code .= '<p><label>.class<input type="text" name="css_class" value="" /></label></p>';
        $code .= '<p><input type="submit" value="Submit" /></p>';
        $code .= '</form></div>';
        return $code;
    }

    public function updateAd()
    {
        $this->validateInput();
        if ($_FILES['adpic']['error'] == 0) {
            if ($this->animated) {
                $imageSuccess = $this->saveAnimatedImage();
            } else {
                $imageSuccess = $this->saveImage();
            }
            if ($imageSuccess) {
                $updatePrepare = 'UPDATE ' . $this->table . ' SET image=?, width=?, height=?, target_url=?, ad_order=?, css_id=?, css_class=? WHERE id = ?';
                $updateExec = array(
                    $this->filename,
                    $this->imagewidth,
                    $this->imageheight,
                    $this->targeturl,
                    $this->adorder,
                    $this->cssid,
                    $this->cssclass,
                    $_POST['adid']
                );
            }
        } else {
            $updatePrepare = 'UPDATE ' . $this->table . ' SET target_url=?, ad_order=?, css_id=?, css_class=? WHERE id = ?';
            $updateExec = array($this->targeturl, $this->adorder, $this->cssid, $this->cssclass, $_POST['adid']);
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sth = $db->prepare($updatePrepare);
        $sth->execute($updateExec);
    }

    public function addNewAd()
    {
        $this->validateInput();
        if ($this->animated) {
            $imageSuccess = $this->saveAnimatedImage();
        } else {
            $imageSuccess = $this->saveImage();
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $query = 'INSERT INTO ' . $this->table . ' (image, width, height, target_url, ad_order, css_id, css_class) VALUES (:image, :width, :height, :targeturl, :adorder, :cssid, :cssclass)';
        $sth = $db->prepare($query);
        $sth->execute(
            array(
                ':image' => $this->filename,
                ':width' => $this->imagewidth,
                ':height' => $this->imageheight,
                ':targeturl' => $this->targeturl,
                ':adorder' => $this->adorder,
                ':cssid' => $this->cssid,
                ':cssclass' => $this->cssclass
            )
        );
    }

    private
    function saveImage()
    {
        $handle = new upload($_FILES['adpic']);
        if ($handle->uploaded) {
            $handle->image_resize = true;
            $handle->image_x = $this->imagewidth;
            $handle->image_y = $this->imageheight;
            $handle->image_ratio = true;
            $handle->file_name_body_add = time();
            $handle->process(ROOT_DIR . ADIMGDIR);
            if ($handle->processed) {
//get width and height after process to save to db
                $this->imagewidth = $handle->image_dst_x;
                $this->imageheight = $handle->image_dst_y;
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

    private function saveAnimatedImage()
    {
        //
        list($filename, $extension) = explode(".", basename($_FILES["adpic"]["name"]));
        $this->filename = $filename . time() . "." . $extension;
        $newFilename = ROOT_DIR . ADIMGDIR . $this->filename;
        if (move_uploaded_file($_FILES["adpic"]["tmp_name"], $newFilename)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteAd($id)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $query = 'DELETE FROM ' . $this->table . ' WHERE id=?';
        $sth = $db->prepare($query);
        $sth->execute(array($id));
    }

    private function getData($id = 0)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'SELECT * FROM ' . $this->table;
        if ($id > 0) {
            $sql .= ' WHERE id = ?';
        }
        $sql .= ' ORDER BY ad_order ASC';
        $this->stmt = $db->prepare($sql);
        if ($id > 0) {
            $this->stmt->execute($id);
        } else {
            $this->stmt->execute();
        }
        $dbcon = null;
    }

    private function getDataByOrder($order)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ad_order = ?';
        $this->stmt = $db->prepare($sql);
        if ($order > 0) {
            $param = array($order);
            $this->stmt->execute($param);
        }
        $dbcon = null;
    }

    private function getMaxOrder()
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'SELECT MAX(ad_order) AS ad_order FROM ' . $this->table;
        $this->stmt = $db->prepare($sql);
        $this->stmt->execute();
        $dbcon = null;
    }

    private function validateInput()
    {
        if ($_POST['animated'] == "1") {
            $this->animated = 1;
        } else {
            $this->animated = null;
        }
        $this->imagewidth = $this->checkInt($_POST['adwidth']);
        $this->imageheight = $this->checkInt($_POST['adheight']);
        if ($_POST['target_url'] != "") {
            $this->targeturl = $this->encode($this->convertUrl($_POST['target_url']));
        } else {
            $this->targeturl = null;
        }
        $this->adorder = $this->checkInt($_POST['ad_order']);
        if ($_POST['css_id'] != "") {
            $this->cssid = $this->encode($this->convertSentence($_POST['css_id']));
        } else {
            $this->cssid = null;
        }
        if ($_POST['css_class'] != "") {
            $this->cssclass = $this->encode($this->convertSentence($_POST['css_class']));
        } else {
            $this->cssclass = null;
        }
        return;
    }
}