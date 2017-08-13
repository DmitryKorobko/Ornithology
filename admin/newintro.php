<?php


include("../includes/funct_lib.php");
$params = array();
$dbcon = new Dbconnection();
$db = $dbcon->connect();
$sql = "SELECT DISTINCT `category`,`sub-category` FROM `links_introduction` ORDER BY `category`, `sub-category`";
$result = $db->prepare($sql);
$result->execute();
$catlist = $result->fetchAll(PDO::FETCH_ASSOC);
$sqltext = 'SELECT `text` FROM `links_introduction` WHERE `category` = ? AND `sub-category` = ? ORDER BY `paragraph`';
$textres = $db->prepare($sqltext);
$insert = 'INSERT INTO `introduction` (`text`, `category`, `sub-category`) VALUES (?, ?, ?)';
$insres = $db->prepare($insert);
foreach ($catlist as $place) {
    $textres->execute(array($place['category'], $place['sub-category']));
    $newtext = "";
    $count = 0;
    while ($row = $textres->fetch(PDO::FETCH_ASSOC)) {
        if ($count > 0) {
            $newtext .= chr(10);
        }
        $newtext .= decode($row['text']);
        $count++;
    }
    $newtext = "<p>" . $newtext . "</p>";
    $newtext = ConvertForInput($newtext);
    $newtext = encode($newtext);
    $params = array($newtext, $place['category'], $place['sub-category']);
    $insres->execute($params);
}
?>
