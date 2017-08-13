<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
$main = "news_article_main";
$text = "news_article_text";
$status = "news_article_status";

if ($_POST["article"]) {
    $article_id = $_POST["article"];
} else {
    $article_id = $_GET["article"];
}

if ($_POST["action"]) {
    $action = $_POST["action"];
} else {
    $action = $_GET["action"];
}

//perform article summary and heading update
if ($action == "insert") {

    //insert new article
    $sqlsel = "INSERT INTO $main " . NEWS_MAIN_FIELDS . " VALUES (";
    $sqlsel .= "'" . encode(convertForInput($_POST['title'])) . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['summary'])) . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['desc_1'])) . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['desc_2'])) . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['desc_3'])) . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['created'])) . "')";
    $sqlquery = $sqlsel;
    //echo $sqlquery;

    $result = mysql_query($sqlquery);
    $new_article_id = mysql_insert_id();
    //get new article id to maintain data integrity
    $sqlsel = "UPDATE $main SET article=$new_article_id WHERE id=$new_article_id";
    $result = mysql_query($sqlsel);
    //echo $sqlsel;

    //insert new article status
    $sqlsel = "INSERT INTO $status " . NEWS_STATUS_FIELDS . " VALUES (";
    $sqlsel .= "'" . $new_article_id . "', ";
    $sqlsel .= "'" . encode(convertForInput($_POST['state'])) . "')";
    $sqlquery = $sqlsel;
    //echo $sqlquery;
    $result = mysql_query($sqlquery);
    $new_article_id = mysql_insert_id();
    if ($result) {
        header("Location: pick_news.php?article=$new_article_id&mode=EDIT"); /* Redirect browser */
    }
}

?>
<html>
<head>
    <title>Create News Article</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <style type="text/css">
        <!--
        p {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
        }

        td {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #000000;
        }

        th {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            text-align: left;
            font-weight: normal
        }

        .title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #999999;
        }

        a:hover {
            color: #FF0000;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        a:link {
            color: #0000FF;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        a:visited {
            color: #0000FF;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        .major_title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
        }

        textarea {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        input {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        select {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        -->
    </style>
    <script language="JavaScript">
        //this script simply sets a flag when new variation text is added
        function validateText(form) {
            var today = "<?php echo date("dS M Y"); ?>";
            form.created.value = today;
        }
    </script>
</head>

<body>
<p class="major_title">Creating News Article....</p>

<form name="news" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=insert">
    <table border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td align="right" class="title" width="100">Title:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" style="font-weight: bold" name="title" size="80" maxlength="80" value=""></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="title">Summary:<br>
                <small>(for homepage)</small>
            </td>
            <td valign="top" align="right">&nbsp;</td>
            <td><textarea name="summary" cols="80" rows="5" wrap="VIRTUAL"></textarea></td>
        </tr>
        <tr>
            <td align="right" class="title">Heading 1:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" name="desc_1" size="80" value="" maxlength="80"></td>
        </tr>
        <tr>
            <td align="right" class="title">Heading 2:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" name="desc_2" style="font-style: italic" size="80" value="" maxlength="80"></td>
        </tr>
        <tr>
            <td align="right" class="title">Heading 3:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" name="desc_3" size="80" value="" maxlength="80"></td>
        </tr>
        <tr>
            <td colspan=3>&nbsp;</td>
        </tr>
        <tr>
            <td align="right" class="title">Created:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" name="created" size="30" maxlength="30" value=""> Set to today's date <input
                    type="checkbox" name="checkbox" value="checkbox" onClick="validateText(news)"></td>
        </tr>
        <tr>
            <td align="right" class="title">State:</td>
            <td align="right">&nbsp;</td>
            <td><font size="2">hidden</font><input type="hidden" name="state" value="hidden"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><br><input type="submit"
                           style="border-color: #FFFFFF #808080 #808080 #FFFFFF; border-width: thin thin thin thin"
                           value="Save title/summary"></td>
        </tr>
    </table>
</form>

</body>
</html>

