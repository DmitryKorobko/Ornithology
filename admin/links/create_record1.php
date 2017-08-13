<?php
include("../common/funct_lib.php");
connect_db();

if ($_POST["action"]) {
    $action = $_POST["action"];
} else {
    $action = $_GET["action"];
}

$category = $_REQUEST["category"];
$subcategory = $_REQUEST["subcategory"];
//perform article summary and heading update
if ($action == "insert") {

    $sub_category = strtolower(str_replace(" ", "_", strip_tags(htmlspecialchars($_POST["title"], ENT_QUOTES))));
    //echo $sub_category;
    header("Location: create_record.php?category=$category&subcategory=$sub_category"); /* Redirect browser */
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
        function validateTitle(form) {
            if (form.title.value == "") {
                alert("Please enter the page title");
                form.title.focus();
                return false;
            }
        }
    </script>
</head>

<body>
<p class="major_title">Creating Page....</p>

<form name="news" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=insert"
      onSubmit="return validateTitle(this);">
    <input type="hidden" name="category" value="<?php echo $_GET["category"]; ?>">
    <table border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td align="right" class="title" width="100">Title:</td>
            <td align="right">&nbsp;</td>
            <td><input type="text" style="font-weight: bold" name="title" size="80" maxlength="80" value=""></td>
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

