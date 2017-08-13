<?php


include("../includes/funct_lib.php");
$params = array();
$dbcon = new Dbconnection();
$db = $dbcon->connect();
$sql = 'SELECT article FROM reviews_article_text ORDER BY article ASC';
$result = $db->prepare($sql);
$result->execute();
$sqltext = 'SELECT state, section FROM reviews_article_status WHERE article = ?';
$res = $db->prepare($sqltext);
$insert = 'UPDATE reviews SET status=?, section=? WHERE article = ?';
$newres = $db->prepare($insert);
$articlelist = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($articlelist as $artnum) {
    $res->execute(array($artnum['article']));
    $newtext = "";
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $newtext .= $row['state'];
        $section = $row['section'];
    }
    $params = array($newtext, $section, $artnum['article']);
    $newres->execute($params);
}
?>
