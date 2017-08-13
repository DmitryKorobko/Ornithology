<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:43
 */
class Species
{

    protected $id;
    protected $familyNameId;
    protected $scientificName;
    protected $commonName;
    protected $url;
    protected $category;
    protected $subcategory;
    protected $placeId;
    protected $order;
    protected $table = "species";
    protected $data;

    public function __construct($id = "")
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
    }

    public function returnData($orderby = "", $order = "", $limit = "")
    {
        $this->getData($orderby, $order, $limit);
        return $this->data;
    }

    /**
     * @param $params
     */
    public function addRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'INSERT INTO `' . $this->table . '` (`familynameid`, `scientificname`, `commonname`, `url`, `category`, `sub-category`, `placeid`) VALUES (?, ?, ?, ?, ?, ?, ?)';
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
        $sql = 'UPDATE ' . $this->table . ' SET `familynameid`=?, `scientificname`=?, `commonname`=?, `url`=?, `category`=?, `sub-category`=?, `placeid`=? WHERE `id`=?';
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
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id=?';
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
        $sql = 'SELECT * FROM ' . $this->table;
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        //where
        if ($this->id != "") {
            $sql .= ' WHERE `id` = ' . $this->id;
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