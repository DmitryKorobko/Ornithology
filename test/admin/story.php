<?php

/*Class Story
 * getTitleList() - Select box of all title sorted by date published
 *
 *
 */

class Story
{

    private $code;
    private $table;
    private $titles;
    private $data;

    public function __construct($table = "")
    {
        if ($table <> "") {
            $this->table = $table;
        }
    }

    public function getFullData($id = "", $status = "", $orderby = "", $order = "", $limit = "", $publishedCheck = false)
    {
        $this->getData($id, $status, $orderby, $order, $limit, $publishedCheck);
        return $this->data;
    }

    public function getDataById($id)
    { // this func is redundant - see above - but still in use
        $this->getData($id);
        return $this->data;
    }

    public function getTitleSelect()
    {
        $this->getTitles();
        return $this->titles;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function addStory($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        if ($this->table == "news" || $this->table == "announcements" || $this->table == "offers") {
            $sql = 'INSERT INTO ' . $this->table . ' (`title`, `status`, `summary`, `created`, `published`, `image`, `story`, `credit`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        } else {
            if ($this->table == "reviews") {
                $sql = 'INSERT INTO ' . $this->table . ' (title, status, summary, created, published, image, story, section, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
            }
        }
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return "Story added";
        } else {
            echo $result->errorinfo();
            return $success;
        }
    }

    public function updateStory($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        if ($this->table == "news" || $this->table == "announcements" || $this->table == "offers") {
            $sql = 'UPDATE ' . $this->table . ' SET title=?, status=?, summary=?, created=?, published=?, image=?, story=?, credit=? WHERE id=?';
        } else {
            if ($this->table == "reviews") {
                $sql = 'UPDATE ' . $this->table . ' SET title=?, status=?, summary=?, created=?, published=?, image=?, story=?, section=?, rating=? WHERE id=?';
            }
        }
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return "Story updated";
        } else {
            echo $result->errorinfo();
            return $success;
        }
    }

    public function deleteStory($params)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id=?';
        $result = $db->prepare($sql);
        $success = $result->execute($params);
        if ($success) {
            return "Story deleted";
        } else {
            echo $result->errorinfo();
            return $success;
        }
    }

    private function getTitles()
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $sql = 'SELECT id, title FROM ' . $this->table . ' ORDER BY published DESC, title ASC';
        $result = $db->prepare($sql);
        $result->execute();
        if ($result->rowCount() > 0) {
            $this->titles = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    private function getData($id = "", $status = "", $orderby = "", $order = "", $limit = "", $publishedCheck = false)
    {
        $sql = 'SELECT * FROM ' . $this->table;
        if ($id != "" || $status != "") {
            $sql .= ' WHERE ';
            if ($id != "") {
                $sql .= 'id = ' . $id;
            }
            if ($id != "" && $status != "") {
                $sql .= ' AND ';
            }
            if ($status != "") {
                $sql .= 'status = "' . $status . '"';
            }
            if (($id != "" || $status != "") && $publishedCheck === true) {
                $sql .= ' AND ';
            }
            if($publishedCheck === true){
                $convert = new prepareInput();
                $sql .= 'published <= ' . $convert->convertDateToTimestamp(date('j/n/y'));
            }
        }
        if ($orderby != "") {
            $sql .= ' ORDER BY ' . $orderby . ' ' . $order;
        }
        if ($limit != "") {
            $sql .= ' LIMIT ' . $limit;
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute();
        if ($result->rowCount() > 0) {
            $this->data = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}