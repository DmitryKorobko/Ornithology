<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 05/09/14
 * Time: 11:43
 */
class Family
{

    protected $id;
    protected $familyName;
    protected $order;
    protected $table = "family";
    protected $data;

    public function __construct($id = "")
    {
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
        $sql = 'INSERT INTO `' . $this->table . '` (`familyname`, `familyorder`) VALUES (?, ?)';
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
        $sql = 'UPDATE ' . $this->table . ' SET `familyname`=?, `familyorder`=? WHERE `id`=?';
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