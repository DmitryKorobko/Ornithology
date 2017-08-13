<?php

class HeadText
{

    protected $id;
    protected $head;
    protected $text;
    protected $placeid;
    protected $category;
    protected $subcategory;
    protected $table;
    protected $data;

    public function __construct($table, $placeid = "", $cat = "", $subcat = "", $id = "")
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
        if ($table != "") {
            $this->table = $table;
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
        $sql = 'INSERT INTO `' . $this->table . '` (`heading`, `text`, `category`, `sub-category`, `placeid`) VALUES (?, ?, ?, ?, ?)';
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
        $sql = 'UPDATE `' . $this->table . '` SET `heading`=?, `text`=? WHERE `id`=?';
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
        $sql = 'DELETE FROM `' . $this->table . '` WHERE `id`=?';
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
        $sql = 'SELECT * FROM `' . $this->table . "`";
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        //where
        if ($this->placeid <> "") {
            $sql .= ' WHERE `id` = ' . $this->placeid;
        } else {
            if ($this->category <> "" && $this->subcategory <> "") {
                $sql .= ' WHERE `category` = "' . $this->category . '" AND `sub-category` = "' . $this->subcategory . '"';
            }
        }
        if ($orderby != "") {
            $sql .= ' ORDER BY `' . $orderby . '` ' . $order;
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