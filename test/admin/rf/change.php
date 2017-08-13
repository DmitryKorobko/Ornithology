<?php

class change{
	private $stmt;
	private $num;
	private $all;

	private function getData(){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$sql = 'SELECT * FROM links_museums ORDER BY id ASC';
		$this->$stmt = prepare($sql);
		$this->stmt->execute();
		$this->num = $this->stmt->rowCount();
		$this->all = $this->stmt->fetch(PDO::FETCH_ALL);
		flog("num - ", $this->num);
		//change collation
		//loop through $all, decode(), encode(), update
	}

	function decode($string){
		return html_entity_decode($string, ENT_QUOTES, "ISO-8859-1");
	}

	function encode($string){
		return htmlentities($string, ENT_HTML5 | ENT_QUOTES, "UTF-8", false);
	}

}

class Dbconnection {

	private $host = "localhost";
	private $db_name = "fbiwadm_fbnewsite";
	private $username = "root";
	private $password = "X5h7W23g";

	public function __construct(){
	}

	public function connect(){
		try{
			$con = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
			return $con;
		}
		catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}
	}
}
?>