<?php


include("../includes/funct_lib.php");
$params = array();
$dbcon = new Dbconnection();
$db = $dbcon->connect();
$sql = 'SELECT DISTINCT article FROM news_article_main ORDER BY article ASC';
$result = $db->prepare($sql);
$result->execute();
$sqltext = 'SELECT state FROM news_article_status WHERE article = ?';
$res = $db->prepare($sqltext);
$insert = 'UPDATE news SET status=? WHERE article = ?';
$newres = $db->prepare($insert);
$articlelist = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($articlelist as $artnum) {
    $res->execute(array($artnum['article']));
    $newstatus = "";
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $newstatus = $row['state'];
    }
    $params = array($newstatus, $artnum['article']);
    $newres->execute($params);
    echo "$newstatus<br />";
}
?>