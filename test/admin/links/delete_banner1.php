<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
if ($_POST["info_type"]) {
    $info_type = $_POST["info_type"];
} else {
    $info_type = $_GET["info_type"];
}

if ($_POST["article"]) {
    $article_id = $_POST["article"];
} else {
    $article_id = $_GET["article"];
}

if ($_POST["id"]) {
    $id = $_POST["id"];
} else {
    $id = $_GET["id"];
}

if ($_POST["paragraph"]) {
    $paragraph = $_POST["paragraph"];
} else {
    $paragraph = $_GET["paragraph"];
}

if ($_POST["action"]) {
    $action = $_POST["action"];
} else {
    $action = $_GET["action"];
}

if ($HTTP_GET_VARS ['mode'] <> "") {
    $mode = $HTTP_GET_VARS ['mode'];
}

if ($_POST["category"]) {
    $category = $_POST["category"];
} else {
    $category = $_GET["category"];
}

if ($_POST["subcategory"]) {
    $subcategory = $_POST["subcategory"];
} else {
    $subcategory = $_GET["subcategory"];
}

if ($_POST["filename"]) {
    $filename = $_POST["filename"];
} else {
    $filename = $_GET["filename"];
}


//perform article update/insert/delete
if ($action) {
    switch ($action) {
        case "delete":
            //do sql delete
            $sqlsel = "DELETE FROM $info_type";
            $sqlsel .= " WHERE id =" . $id;
            $sqlquery = $sqlsel;
            //echo $sqlquery;
            $result = mysql_query($sqlquery);
            if ($result) {
                $success = 1;
                $message = "Image deleted";
                //get file to clean up file system
                unlink("../../adverts/$filename");
            } else {
                $success = 0;
                $message = "Image could not be deleted";
            }
            $mode = "SUCCESS";
            break;
    }
//create new HTML file based on database for the given section
    if ($success == 1) {
        $tmpl_dir = "";
        $tmpl_name = "template.htm";

//create the correct output dir and html file name based on subcategory and category
        list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
        $html_dir = dir_const . $alt_dir;
        $html_name = $alt_file;

//set the root folder to chmod 777
        $chmodf = chmod_file . $alt_dir;
        ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

        echo $html_dir;
        echo $html_name;
        $sql_cat = $category;
        $sql_subcat = $subcategory;
        $table = $info_type;
        global $alt_dir;
        global $alt_file;
//echo getcontent($table, $category, $subcategory);
        echo createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat);

//set the root folder to chmod 755
        ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

    }
}

//use the table names to display the relavent insert forms as long as the mode variable is blank
if (!$mode) {
    switch ($info_type) {
        case "":
            $info_type = "links_banners";
            $mode = "BANNERS";
            break;
    }
}
?>

<?php
switch ($mode) {
    case "SUCCESS":
        ?>
        <html>

        <head>
            <title>Delete record</title>

            <script language="JavaScript">
                <!--

                refreshed = 'no';

                function refreshPickRecord(js_category, js_subcategory, js_random) {
                    opener.location.href = 'pick_record.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
                    refreshed = 'yes';
                }

                function checkrefreshPickRecord(js_category, js_subcategory, js_random) {
                    if (refreshed == 'no') {
                        opener.location.href = 'pick_record.php?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
                        refreshed = 'yes';
                    }
                }


                //-->
            </script>

        </head>

        <body onBlur="self.focus()"
              onLoad="refreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');"
              onUnload="checkrefreshPickRecord('<?php echo $category; ?>','<?php echo $subcategory; ?>','1114596898');"
              background="../images/popup_edit_row_background.jpg" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF"
              text="#FFFFFF" bgcolor="#13474F">
        <p><font face="Verdana,Arial" size="5" color="#FAD400"><?php echo $message; ?></font></p>

        <p><font face="Verdana,Arial" size="4">The main window content is now being refreshed.....</font></p>

        <p>
        <center><a href="javascript:window.close();"
                   onMouseOver="window.status='Close this window and continue';return true"
                   onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163"
                                                                  height="24" border="0"></a></center>
        </p>
        </body>
        </html>
        <?php
        break;
    case "BANNERS":
        ?>
        <html>

        <head>
            <title>Delete Record</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Delete record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" method="post">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <table cellpadding="3">
                <?php
                //get a summary of the recoed that has just been added
                $sqlsel = "SELECT * FROM $info_type WHERE id=" . $id;
                //echo $sqlsel;
                $result = mysql_query($sqlsel);
                while ($row = mysql_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>File : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><img
                                    src="<?php echo ROOT_URL; ?>/adverts/<?php echo html_entity_decode(
                                        $row["filename"]
                                    ); ?>"></font></td>
                        <input type="hidden" name="filename" value="<?php echo $row["filename"]; ?>">
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>In Assoc with : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["sponsor"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="2" align="right"><a href="javascript:document.add_row.submit()"
                                                     onMouseOver="window.status='Delete this image from the current page';return true"
                                                     onMouseOut="window.status='';return true"><img
                                src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;<a
                            href="javascript:window.close()"
                            onMouseOver="window.status='Abandon your changes and close this window';return true"
                            onMouseOut="window.status='';return true"><img src="../images/abandon_changes_button.gif"
                                                                           width="163" height="24" border="0"></a></td>
                </tr>
            </table>
        </form>
        </body>
        </html>
        <?php
        break;
}
?>
