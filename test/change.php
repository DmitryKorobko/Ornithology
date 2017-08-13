<?php
class Change{
	private $stmt;
	private $num;
	private $all;
	private $table = "news_article_text";

	public function doit(){
		echo '<!DOCTYPE html>
		<html>
		<head>
		<meta charset="WINDOWS-1252">
		</head>
		<body>';
		$this->getData();
		echo'</body></html>';
	}

	private function getData(){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$sql = 'SELECT * FROM ' . $this->table . ' ORDER BY id ASC';
		$this->stmt = $db->prepare($sql);
		$this->stmt->execute();
		$this->num = $this->stmt->rowCount();
		$this->all = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		echo"<p>$this->table</p>";
		$this->reCollate();
		for($x=0;$x<$this->num;$x++){
			unset($arr);
			$sql2 = 'UPDATE ' . $this->table . ' SET  text=? WHERE id=' . $this->all[$x]['id'];
			//$sql2 = 'UPDATE ' . $this->table . ' SET name=?, address=?, telephone=?, fax=? email=?, category=?, sub-category=? WHERE id=' . $this->all[$x]['id'];
			//$sql2 = 'UPDATE ' . $this->table . ' SET name=?, title=?, location=?, email=?, url1=?, url_title=?, link_desc=?, category=?, sub-category=? WHERE id=' . $this->all[$x]['id'];
			//$sql2 = 'UPDATE ' . $this->table . ' SET t=?, url1=?, url_title=?, sponsor=?, text=?, width=?, height=?, category=?, sub-category=? WHERE id=' . $this->all[$x]['id'];
			$sth = $db->prepare($sql2);
			/*if(is_null($this->all[$x]['name'])){
				$name = null;
			}else{
				$name = $this->process($this->all[$x]['name'], $this->all[$x]['id'], "name");
			}*/
			/*if(is_null($this->all[$x]['title'])){
				$title = null;
			}else{
				$title = $this->process($this->all[$x]['title'], $this->all[$x]['id'], "title");
			}*/
			/*if(is_null($this->all[$x]['address'])){
				$address = null;
			}else{
				$address = $this->process($this->all[$x]['address'], $this->all[$x]['id'], "address");
			}
			if(is_null($this->all[$x]['telephone'])){
				$telephone = null;
			}else{
				$telephone = $this->process($this->all[$x]['telephone'], $this->all[$x]['id'], "telephone");
			}
			if(is_null($this->all[$x]['fax'])){
				$fax = null;
			}else{
				$fax = $this->process($this->all[$x]['fax'], $this->all[$x]['id'], "fax");
			}*/
			/*if(is_null($this->all[$x]['url1'])){
				$url1 = null;
			}else{
				$url1 = $this->process($this->all[$x]['url1'], $this->all[$x]['id'], "url1");
			}
			if(is_null($this->all[$x]['url1_title'])){
				$url1_title = null;
			}else{
				$url1_title = $this->process($this->all[$x]['url1_title'], $this->all[$x]['id'], "url1_title");
			}

			if(is_null($this->all[$x]['url2'])){
				$url2 = null;
			}else{
				$url2 = $this->process($this->all[$x]['url2'], $this->all[$x]['id'], "url2");
			}
			if(is_null($this->all[$x]['url2_title'])){
				$url2_title = null;
			}else{
				$url2_title = $this->process($this->all[$x]['url2_title'], $this->all[$x]['id'], "url2_title");
			}*/
			/*if(is_null($this->all[$x]['link_desc'])){
				$link_desc = null;
			}else{
				$link_desc = $this->process($this->all[$x]['link_desc'], $this->all[$x]['id'], "link_desc");
			}*/
			//$category = $this->process($this->all[$x]['category'], $this->all[$x]['category'], "category");
			//$subcategory = $this->process($this->all[$x]['sub-category'], $this->all[$x]['sub-category'], "sub-category");
			/*if(is_null($this->all[$x]['location'])){
				$location = null;
			}else{
				$location = $this->process($this->all[$x]['location'], $this->all[$x]['id'], "location");
			}*/
			if(is_null($this->all[$x]['text'])){
				$text = null;
			}else{
				$text = $this->process($this->all[$x]['text'], $this->all[$x]['id'], "text");
			}
			/*if(is_null($this->all[$x]['width'])){
				$width = null;
			}else{
				$width = $this->process($this->all[$x]['width'], $this->all[$x]['id'], "width");
			}if(is_null($this->all[$x]['height'])){
				$height = null;
			}else{
				$height = $this->process($this->all[$x]['height'], $this->all[$x]['id'], "height");
			}*/
			/*if(is_null($this->all[$x]['filename'])){
				$filename = null;
			}else{
				$filename = $this->process($this->all[$x]['filename'], $this->all[$x]['id'], "filename");
			}*/
			/*if(is_null($this->all[$x]['summary'])){
				$summary = null;
			}else{
				$summary = $this->process($this->all[$x]['summary'], $this->all[$x]['id'], "summary");
			}
			if(is_null($this->all[$x]['desc_1'])){
				$desc1 = null;
			}else{
				$desc1 = $this->process($this->all[$x]['desc_1'], $this->all[$x]['id'], "desc_1");
			}
			if(is_null($this->all[$x]['desc_2'])){
				$desc2 = null;
			}else{
				$desc2 = $this->process($this->all[$x]['desc_2'], $this->all[$x]['id'], "desc_2");
			}
			if(is_null($this->all[$x]['desc_3'])){
				$desc3 = null;
			}else{
				$desc3 = $this->process($this->all[$x]['desc_3'], $this->all[$x]['id'], "desc_3");
			}*/
			/*if(is_null($this->all[$x]['created'])){
				$created = null;
			}else{
				$created = $this->process($this->all[$x]['created'], $this->all[$x]['id'], "created");
			}
			if(is_null($this->all[$x]['rating'])){
				$rating = null;
			}else{
				$rating = $this->process($this->all[$x]['rating'], $this->all[$x]['id'], "rating");
			}
			$image = $this->process($this->all[$x]['image'], $this->all[$x]['id'], "image");*/

			$arr = array($text);
			var_dump($arr);
			echo"<br /><br />";
			$sth->execute($arr);
		}
	}

	private function process($field, $id, $fieldname){
		if($field == ""){
			return $field;
		}
		$string = $this->decode($field);
		$string = str_ireplace("<i>", "<em>", $string);
		$string = str_ireplace("</i>", "</em>", $string);
		$string = str_ireplace("<b>", "<strong>", $string);
		$string = str_ireplace("</b>", "</strong>", $string);
		$string = str_ireplace("<br>", "<br />", $string);
		$string = str_ireplace("...", "&hellip;", $string);
		$string = str_ireplace("&NewLine;", "", $string);
		$string = str_ireplace(chr(10), "", $string);
		$string = str_ireplace(chr(92), "&rsquo;", $string);
		$string = str_ireplace(chr(128), "&euro;", $string);
		$string = str_ireplace(chr(133), "&hellip;", $string);
		$string = str_ireplace(chr(145), "&lsquo;", $string);
		$string = str_ireplace(chr(146), "&rsquo;", $string);
		$string = str_ireplace(chr(147), "&ldquo;", $string);
		$string = str_ireplace(chr(148), "&rdquo;", $string);
		$string = str_ireplace(chr(150), "&ndash;", $string);
		$string = str_ireplace(chr(151), "&mdash;", $string);
		$string = $this->decode($string);
		$string = mb_convert_encoding($string, "UTF-8", "WINDOWS-1252");
		$string2 = $this->encode($string);
		$string = str_ireplace("&NewLine;", "", $string);
		if($string2 == ""){
			echo "<p>ID = $id<br />";
			echo "Field = $fieldname<br />";
			echo "encode failed<br />$string";
			echo "</p>";
			return $string;
		}else{
			return $string2;
		}
	}

	private function reCollate(){
		$execute_sql = true;

		$host = 'localhost';
		$username = 'fbiwadm_fbmysql';
		$password = 'X5h7W23g';
		$dbname = 'fbiwadm_newsite';

		$db = new mysqli($host, $username, $password, $dbname);

		$collation = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci';
		$collationPK = 'CHARACTER SET utf8 COLLATE utf8_bin';

		$result = $db->query("SET foreign_key_checks = 0");

		echo '<div>';
		//if($execute_sql) $db->query("ALTER DATABASE $dbname $collation");

		/*$result = $db->query("SHOW TABLES");

		$count = 0;
		while($row = $result->fetch_assoc()) {
			$table = $row['Tables_in_'.$dbname];*/
			if($execute_sql) $db->query("ALTER TABLE $this->table DEFAULT $collation");
			$result1 = $db->query("SHOW FULL COLUMNS FROM $this->table");
			$alter = '';
			while($row1 = $result1->fetch_assoc()) {
				if (preg_match('~char|text|enum|set~', $row1["Type"])) {
					// support a different collation for primary keys
					$newCollation = $collation;
					// check if we actually need to change the collation
					$alter .= (strlen($alter)?", \n":" ") . "MODIFY `$row1[Field]` $row1[Type] $newCollation" . ($row1["Null"] ? "" : " NOT NULL") . ($row1["Default"] && $row1["Default"] != "NULL" ? " DEFAULT '$row1[Default]'" : "");
				}
			}
			if(strlen($alter)){
				$sql = "ALTER TABLE $this->table".$alter.";";
				echo "<div>$sql\n\n</div><br />";
				if($execute_sql) {
					if ($db->query($sql)) {
						echo "<div>OK</div>";
					} else {
						echo "<div style=\"color: #f00;\">Error: $db->error</div>";
					}
				}
			}
			$count++;
		//}
		echo '</div>';
	}

	private function decode($string){
		return html_entity_decode($string, ENT_QUOTES, "WINDOWS-1252");
	}

	private function encode($string){
		return htmlentities($string, ENT_HTML5 | ENT_QUOTES, "UTF-8", false);
	}

}

class Dbconnection {

	private $host = "localhost";
	private $db_name = "fbiwadm_newsite";
	private $username = "fbiwadm_fbmysql";
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

$rf = new Change();
$rf->doit();
?>