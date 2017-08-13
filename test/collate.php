<?php
$execute_sql = false;

$host = 'localhost';
$username = 'fbiwadm_fbmysql';
$password = 'X5h7W23g';
$dbname = 'fbiwadm_fbnewsite';

$db = new mysqli($host, $username, $password, $dbname);

$collation = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci';
$collationPK = 'CHARACTER SET utf8 COLLATE utf8_bin';

$result = $db->query("SET foreign_key_checks = 0");

echo '<div>';
if($execute_sql) $db->query("ALTER DATABASE $dbname $collation");

$result = $db->query("SHOW TABLES");

$count = 0;
while($row = $result->fetch_assoc()) {
	$table = $row['Tables_in_'.$dbname];
	if($execute_sql) $db->query("ALTER TABLE $table DEFAULT $collation");
	$result1 = $db->query("SHOW FULL COLUMNS FROM $table");
	$alter = '';
	while($row1 = $result1->fetch_assoc()) {
		if (preg_match('~char|text|enum|set~', $row1["Type"])) {
			// support a different collation for primary keys
			if ($row1["Key"] == "PRI" || $row1["Key"] == "MUL") {
				$newCollation = $collationPK;
			} else {
				$newCollation = $collation;
			}
			// check if we actually need to change the collation
			$alter .= (strlen($alter)?", \n":" ") . "MODIFY `$row1[Field]` $row1[Type] $newCollation" . ($row1["Null"] ? "" : " NOT NULL") . ($row1["Default"] && $row1["Default"] != "NULL" ? " DEFAULT '$row1[Default]'" : "");
		}
	}
	if(strlen($alter)){
		$sql = "ALTER TABLE $table".$alter.";";
		echo "<div>$sql\n\n</div>";
		if($execute_sql) {
			if ($db->query($sql)) {
				echo "<div>OK</div>";
			} else {
				echo "<div style=\"color: #f00;\">Error: $db->error</div>";
			}
		}
	}
	$count++;
}
echo '</div><br />';
?>