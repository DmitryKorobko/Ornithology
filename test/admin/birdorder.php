<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 17/10/2014
 * Time: 12:41
 */
class BirdOrder
{

    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $category;
    /**
     * @var
     */
    private $subcategory;
    /**
     * @var
     */
    private $pageId;
    /**
     * @var
     */
    private $data;
    /**
     * @var string
     */
    private $table = "birdorder";
    private $count;

    /**
     * @param string $pageId
     * @param string $cat
     * @param string $subcat
     * @param string $id
     */
    public function __construct($pageId = "", $cat = "", $subcat = "", $id = "")
    {
        if ($pageId <> "") {
            $this->placeid = $pageId;
        } elseif ($cat <> "" && $subcat <> "") {
            $this->category = $cat;
            $this->subcategory = $subcat;
        }
        if ($id != "") {
            $this->id = $id;
        }
    }

    /**
     * @param string $orderby
     * @param string $order
     * @param string $limit
     * @return mixed
     */
   /* public function returnData($orderby = "", $order = "", $limit = "")
    {
        $this->getData($orderby, $order, $limit);
        return $this->data;
    }*/

    /**
     * @param string $orderby
     * @param string $order
     * @param string $limit
     */
  /*  protected function getData($orderby = "", $order = "", $limit = "")
    {
        $sql = 'SELECT * FROM `' . $this->table . "`";
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        //where
        if ($this->id != "") {
            $sql .= ' WHERE `id` = ' . $this->id;
        } elseif ($this->pageId <> "") {
            $sql .= ' WHERE `pageid` = ' . $this->pageId;
        } elseif ($this->category <> "" && $this->subcategory <> "") {
            $sql .= ' WHERE `category` = "' . $this->category . '" AND `sub-category` = "' . $this->subcategory . '"';
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
    }*/

    /**
     * @param $params
     */
    public function addRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'INSERT INTO `' . $this->table . '` (`scientificname`, `commonname`, `displayorder`, `category`, `sub-category`, `pageId`) VALUES (?, ?, ?, ?, ?, ?)';
        $result = $db->prepare($sql);
        $result->execute($params);
        $no = $result->rowCount();
        if ($no == 0) {
            echo $result->errorinfo();
            return;
        }
    }

    /**
     * @param $params
     */
    public function updateRecord($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'UPDATE `' . $this->table . '` SET `scientificname`=?, `commonname`=?, `displayorder`=?, `sub-category`=? WHERE `id`=?';
        $result = $db->prepare($sql);
        $result->execute($params);
        $no = $result->rowCount();
        if ($no == 0) {
            echo $result->errorinfo();
            return;
        }
    }

    /**
     * @param $params
     */
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

    public function getDataByField(
        $field = array(),
        $where = "",
        $what = "",
        $orderBy = "",
        $direction = "",
        $limit = ""
    ) {
        $sql = 'SELECT';
        $fieldCount = count($field);
        $num = 0;
        if($fieldCount > 0){
            foreach($field as $f){
                $num++;
                $sql .= ' `' . $f . '`';
                if($num < $fieldCount){
                    $sql .= ',';
                }
            }
        }else{
            $sql .= ' *';
        }
        $sql .= ' FROM `' . $this->table . '`';
        if ($where != "" && $what != "") {
            $sql .= ' WHERE `' . $where . '` = "' . $what . '"';
        }
        if ($orderBy != "") {
            $sql .= ' ORDER BY `' . $orderBy . '` ' . $direction;
        }
        if ($limit != "") {
            $sql .= ' LIMIT ' . $limit;
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute();
        $count = $result->rowCount;
        if ($count > 0) {
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    /**
    * @param array $field - array of fields to retrieve
    * @param array $where - Associative array (field=>value) i.e. (id=>1). If >1 row the connection will be &&
    * @param string $orderBy - Field to order results by
    * @param string $direction - ASC or DESC for $orderBy
    * @param string $limit -
    * @return mixed
    */
    public function getData($field = array(), $where = array(), $orderBy = "", $direction = "", $limit = "")
    {
        $sql = 'SELECT';
        $fieldCount = count($field);
        if ($fieldCount > 0) {
            $num = 0;
            foreach ($field as $f) {
                $num++;
                $sql .= ' `' . $f . '`';
                if ($num < $fieldCount) {
                    $sql .= ',';
                }
            }
        } else {
            $sql .= ' *';
        }
        $sql .= ' FROM `' . $this->table . '`';
        $whereCount = count($where);
        if ($whereCount > 0) {
            $num = 0;
            $sql .= ' WHERE';
            foreach ($where as $whereField => $value) {
                $num++;
                $sql .= ' `' . $whereField . '` = "' . $value . '"';
                if ($num < $whereCount) {
                    $sql .= ' &&';
                }
            }
        }
        if ($orderBy != "") {
            $sql .= ' ORDER BY `' . $orderBy . '` ' . $direction;
        }
        if ($limit != "") {
            $sql .= ' LIMIT ' . $limit;
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute();
        $count = $result->rowCount();
        if ($count > 0) {
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else {
            $error = "";//TODO Make error a real thing
            return $error;
        }
    }
} 