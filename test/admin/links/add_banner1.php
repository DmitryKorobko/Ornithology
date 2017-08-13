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


//perform article update/insert/delete
if ($action) {
    switch ($action) {
        case "banners":
            //do a check to see if we're adding or overwriting an image
            $select = "SELECT * FROM $info_type WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
            //echo $select;
            $result = mysql_query($select);
            $count = mysql_num_rows($result);

            //only allow for two banners to exist
            if ($count < 3) {

                // set max image dimensions
                $max_nuwidth = 550;
                $max_nuheight = 550;
                $userfile = "file1";
                $filename = "";
                $dir = dir_const . "adverts";
                //set variable to generate thumbnail
                $thumbnail = 0;

                // cmn - for php running as an apache module, must set parent dir as writable
                $chmodf = chmod_file . "adverts";
                ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

                $upload_message = upload_image(
                    $HTTP_POST_FILES[$userfile]['name'],
                    $HTTP_POST_FILES[$userfile]['tmp_name'],
                    $HTTP_POST_FILES[$userfile]['type'],
                    $HTTP_POST_FILES[$userfile]['size'],
                    $max_nuwidth,
                    $max_nuheight,
                    $filename,
                    $thumbnail,
                    $dir
                );
                //$upload_message = "complete";

                // cmn - for php running as an apache module, must reset parent dir back to non writable
                $chmodf = chmod_file . "adverts";
                ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

                if ($upload_message == "no_image") {
                    $success = 0;
                } else {
                    if ($upload_message != "") {
                        /*
                        $message = "Error Uploading Image" echo $i . ": " . $HTTP_POST_FILES[$userfile]['name'] . "  error:" .  $upload_message;
                        $SQLfiles=$SQLfiles . "NULL, ";
                        $upload_fail = "yes";
                        */
                        $success = 0;
                    } else {

                        $imageInfo = getimagesize($HTTP_POST_FILES[$userfile]['tmp_name']);
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        $sqlsel = "INSERT INTO $info_type " . LINKS_BANNERS_FIELDS . " VALUES (";
                        $sqlsel .= "'" . htmlspecialchars($filename, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($_POST["url1"], ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($_POST["url1_title"], ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($_POST["sponsor"], ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($_POST["text"], ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($width, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($height, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($subcategory, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($category, ENT_QUOTES) . "')";
                        $sqlquery = $sqlsel;
                        //echo $sqlquery;

                        $result = mysql_query($sqlquery);
                        if ($result) {
                            $success = 1;
                            $message = "Image added";
                        } else {
                            $success = 0;
                            $message = "Image could not be added";
                        }

                    }
                }

            }
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

//get a hanlde for the recoed taht has just been added
    $new_article_id = mysql_insert_id();
    $mode = "SUCCESS";
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
            <title>Add new row</title>

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
        <table>
            <?php
            //get a summary of the recoed that has just been added
            $sqlsel = "SELECT * FROM $info_type WHERE id=" . $new_article_id;
            //echo $sqlsel;
            $result = mysql_query($sqlsel);
            $count = mysql_num_rows($result);
            while ($row = mysql_fetch_assoc($result)) {

                switch ($info_type) {
                    case "links_banners":
                        ?>
                        <tr>
                            <td valign="top"><font face="Verdana,Arial" size="1"><b>File : </b></font></td>
                            <td><font face="Verdana,Arial" size="1"><img
                                        src="<?php echo ROOT_URL; ?>/adverts/<?php echo html_entity_decode(
                                            $row["filename"]
                                        ); ?>"></font></td>
                        </tr>
                        <tr>
                            <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                            <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                        $row["url1"]
                                    ); ?></font></td>
                        </tr>
                        <tr>
                            <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                            <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                        $row["url1_title"]
                                    ); ?></font></td>
                        </tr>
                        <tr>
                            <td valign="top"><font face="Verdana,Arial" size="1"><b>In Assoc with : </b></font></td>
                            <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                        $row["sponsor"]
                                    ); ?></font></td>
                        </tr>
                        <tr>
                            <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                            <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                        $row["text"]
                                    ); ?></font></td>
                        </tr>
                        <?php
                        break;
                }
            }
            ?>
        </table>
        <p>
        <center><a href="javascript:window.close();"
                   onMouseOver="window.status='Close this window and continue';return true"
                   onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163"
                                                                  height="24" border="0"></a>&nbsp;&nbsp;
            <?php
            if (!$count > 1){
            ?>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?category=<?php echo $category; ?>&subcategory=<?php echo $subcategory; ?>&random=1114596898"
               onMouseOver="window.status='Add another record';return true"
               onMouseOut="window.status='';return true"><img src="../images/add_another.gif" width="163" height="24"
                                                              border="0"></a></center>
        </p>
        <?php
        }
        ?>
        </body>
        </html>
        <?php
        break;
    case "BANNERS":
        ?>
        <html>

        <head>
            <title>Add banner</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F"
              onLoad="document.add_row.url.focus();">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new banner</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=banners" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>File : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="file" name="file1" size="53"><input
                                type="hidden" name="MAX_FILE_SIZE" value="50000"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
                    <td><font face="ms sans serif" size="2"><input type="text" name="url1" size="53"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL Title : </b></font></td>
                    <td><font face="ms sans serif" size="2"><input type="text" name="url1_title" size="53"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>In Assoc with: </b></font></td>
                    <td><font face="ms sans serif" size="2"><input type="text" name="sponsor" size="53"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text : </b></font></td>
                    <td><textarea name="text" cols="50" rows="8"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><a href="javascript:document.add_row.submit()"
                                                     onMouseOver="window.status='Add this new record to the current page';return true"
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
