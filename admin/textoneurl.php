<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:43
 */
class TextOneUrl
{

    protected $id;
    protected $placeId;
    protected $category;
    protected $subcategory;
    protected $dbtable;
    protected $data;

    public function __construct($dbtable, $placeId = "", $cat = "", $subCat = "", $id = "")
    {
        if ($placeId <> "") {
            $this->placeId = $placeId;
        } elseif ($cat <> "" && $subCat <> "") {
            $this->category = $cat;
            $this->subcategory = $subCat;
        }
        if ($id != "") {
            $this->id = $id;
        }
        if ($dbtable != "") {
            $this->dbtable = $dbtable;
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
        $sql = 'INSERT INTO `' . $this->dbtable . '` (`title`, `url1`, `url1title`, `url1desc`, `category`, `sub-category`, `placeid`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $result = $db->prepare($sql);
        $result->execute($params);
        $no = $result->rowCount();
        if ($no == 0) {
            echo $result->errorinfo();
            return;
        }
    }

    public function updateRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'UPDATE ' . $this->dbtable . ' SET `title`=?, `url1`=?, `url1title`=?, `url1desc`=? WHERE `id`=?';
        $result = $db->prepare($sql);
        $result->execute($params);
        $no = $result->rowCount();
        if ($no == 0) {
            echo $result->errorinfo();
            return;
        }
    }

    public function deleteRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'DELETE FROM ' . $this->dbtable . ' WHERE id=?';
        $result = $db->prepare($sql);
        $result->execute($params);
        $no = $result->rowCount();
        if ($no == 0) {
            echo $result->errorinfo();
            return;
        }
    }

    protected function getData($orderby = "", $order = "", $limit = "")
    {
        $sql = 'SELECT * FROM ' . $this->dbtable;
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        //where
        if ($this->id != "") {
            $sql .= ' WHERE `id` = ' . $this->id;
        } elseif ($this->placeId <> "") {
            $sql .= ' WHERE `placeid` = ' . $this->placeId;
        } elseif ($this->category <> "" && $this->subcategory <> "") {
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