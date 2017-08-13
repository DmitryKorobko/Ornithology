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

if ($_POST["ref_url"]) {
    $ref_url = $_POST["ref_url"];
} else {
    $ref_url = $_GET["ref_url"];
}


//perform article update/insert/delete
if ($action) {
    switch ($action) {
        case "photo":
            //quick hack to allow for gorgia europe and USA
            if ($subcategory == "georgia") {
                $country = $category . $subcategory;
            } else {
                $country = $subcategory;
            }
            $select = "SELECT * FROM $info_type WHERE `country` = '$country'";
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            //echo $select;
            //only allow for 1 photo to exist
            if ($count < 1) {
                // set max image dimensions
                $max_nuwidth = 550;
                $max_nuheight = 550;
                $userfile = "file1";
                $filename = "";
                $dir = dir_const . "photos";
                //set variable to generate thumbnail
                $thumbnail = 0;

                // cmn - for php running as an apache module, must set parent dir as writable
                $chmodf = chmod_file . "photos";
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
                $chmodf = chmod_file . "photos";
                ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

                if ($upload_message == "no_image") {
                    $success = 0;
                } else {
                    if ($upload_message != "") {
                        $success = 0;
                    } else {

                        $imageInfo = getimagesize($HTTP_POST_FILES[$userfile]['tmp_name']);
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];

                        $sqlsel = "INSERT INTO $info_type " . LINKS_PHOTO_FIELDS . " VALUES (";

                        //quick hack to allow for gorgia europe and use update the country feild in the database
                        if ($subcategory == "georgia") {
                            $country = $category . $subcategory;
                        } else {
                            $country = $subcategory;
                        }

                        $sqlsel .= "'" . $country . "', ";
                        $sqlsel .= "'" . htmlspecialchars($filename, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($width, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($height, ENT_QUOTES) . "', ";
                        $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "')";
                        $sqlquery = $sqlsel;
                        //echo $sqlquery;
                        $result = mysql_query($sqlquery);
                        if ($result) {
                            $success = 1;
                            $message = "Image added";
                        } else {
                            $success = 0;
                            $message = "No image was added";
                        }

                    }
                }
            }
            break;
        case "map":

            $max_nuwidth = 800;
            $max_nuheight = 800;
            $userfile = "file1";
            $dir = dir_const . "images";
            $filename = "map_" . $subcategory;

            $type = $HTTP_POST_FILES[$userfile]['type'];

            if ($type == "image/gif") {
                $filename .= ".gif";
            } else {
                if (type == "image/pjpeg") {
                    $filename .= ".jpg";
                }
            }


            //set variable to generate thumbnail
            $thumbnail = 0;

            // cmn - for php running as an apache module, must set parent dir as writable
            $chmodf = chmod_file . "images";
            ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

            $upload_message = upload_image(
                $filename,
                $HTTP_POST_FILES[$userfile]['tmp_name'],
                $HTTP_POST_FILES[$userfile]['type'],
                $HTTP_POST_FILES[$userfile]['size'],
                $max_nuwidth,
                $max_nuheight,
                $filename,
                $thumbnail,
                $dir,
                0
            );

            // cmn - for php running as an apache module, must reset parent dir back to non writable
            $chmodf = chmod_file . "images";
            ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

            //echo "HERE";
            //$upload_message = "complete";
            $success = 1;
            //echo $success;
            //handle the image map text area
            $msqlsel = "SELECT * FROM `map` WHERE `category`='$category' AND `sub-category`='$subcategory'";
            //echo $msqlsel;
            $mresult = mysql_query($msqlsel);
            $mcount = mysql_num_rows($mresult);
            //echo $mcount;

            $row = mysql_fetch_assoc($mresult);


            $map_tag_start = "<map name=\"" . $subcategory . "\">";
            $map_tag_end = "</map>";

            if ($count < 1) {
                $sqlsel = "INSERT INTO $info_type " . LINKS_MAP_FIELDS . " VALUES (";
                if (htmlspecialchars($_POST['imagemap'], ENT_QUOTES)) {
                    $sqlsel .= "'" . $map_tag_start . htmlspecialchars(
                            $_POST['imagemap'],
                            ENT_QUOTES
                        ) . $map_tag_end . "', ";
                } else {
                    $sqlsel .= "'" . null . "', ";
                }
                if (htmlspecialchars($_POST['regionlist'], ENT_QUOTES)) {
                    $sqlsel .= "'" . $map_tag_start . htmlspecialchars(
                            $_POST['regionlist'],
                            ENT_QUOTES
                        ) . $map_tag_end . "', ";
                } else {
                    $sqlsel .= "'" . null . "', ";
                }
                $sqlsel .= "'" . $subcategory . "', ";
                $sqlsel .= "'" . $category . "')";

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


            } else {

                if (($_POST['imagemap']) || ($_POST['regionlist'])) {
                    $sqlsel = "UPDATE $info_type SET ";
                    if (htmlspecialchars($_POST['imagemap'], ENT_QUOTES)) {
                        $sqlsel .= "imagemap = '" . $map_tag_start . htmlspecialchars(
                                $_POST['imagemap'],
                                ENT_QUOTES
                            ) . $map_tag_end . "' ";
                        $check = 1;
                    }
                    if (htmlspecialchars($_POST['regionlist'], ENT_QUOTES)) {
                        if ($check = 1) {
                            $sqlsel .= ",";
                        }
                        $sqlsel .= "regionlist = '" . $map_tag_start . htmlspecialchars(
                                $_POST['regionlist'],
                                ENT_QUOTES
                            ) . $map_tag_end . "' ";
                    }

                    if ($check = 1) {
                        $sqlsel .= "WHERE `category` = '" . $category . "'";
                        $sqlsel .= " AND `sub-category` ='" . $subcategory . "'";
                    }
                }

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

//echo $html_dir;
//echo $html_name;
        $sql_cat = $category;
        $sql_subcat = $subcategory;
        $table = $info_type;
        global $alt_dir;
        global $alt_file;
//echo getcontent($table, $category, $subcategory);
        createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat);

//set the root folder to chmod 755
        ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

    }


    $new_article_id = mysql_insert_id();
    $mode = "SUCCESS";
//get a hanlde for the recoed taht has just been added

}

//use the table names to display the relavent insert forms as long as the mode variable is blank
if (!$mode) {
    switch ($info_type) {
        case "photos":
            $mode = "PHOTO";
            break;
        case "map":
            $mode = "MAP";
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
                    opener.location.href = '<?php echo $ref_url; ?>?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
                    refreshed = 'yes';
                }

                function checkrefreshPickRecord(js_category, js_subcategory, js_random) {
                    if (refreshed == 'no') {
                        opener.location.href = '<?php echo $ref_url; ?>?category=' + js_category + '&subcategory=' + js_subcategory + '&random=' + js_random;
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
            if ($info_type == "map") {
                ?>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="1"><b>Image : </b></font></td>
                    <td><img src="../../images/<?php echo $filename; ?>"></td>
                </tr>
            <?php
            } else {
                //get a summary of the recoed that has just been added
                $sqlsel = "SELECT * FROM $info_type WHERE id=" . $new_article_id;
                //echo $sqlsel;
                $result = mysql_query($sqlsel);
                while ($row = mysql_fetch_assoc($result)) {

                    switch ($info_type) {
                        case "photos":
                            ?>
                            <tr>
                                <td valign="top"><font face="Verdana,Arial" size="1"><b>Image : </b></font></td>
                                <td><font face="Verdana,Arial" size="1"><img
                                            src="<?php echo ROOT_URL; ?>/photos/<?php echo html_entity_decode(
                                                $row["filename"]
                                            ); ?>"></font></td>
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
            }
            ?>
        </table>
        <p>
        <center><a href="javascript:window.close();"
                   onMouseOver="window.status='Close this window and continue';return true"
                   onMouseOut="window.status='';return true"><img src="../images/continue_button.gif" width="163"
                                                                  height="24" border="0"></a>&nbsp;&nbsp;
            <!--<a href="<?php echo $_SERVER['PHP_SELF']; ?>?category=<?php echo $category; ?>&info_type=<?php echo $info_type; ?>&subcategory=<?php echo $subcategory; ?>&random=1114596898" onMouseOver="window.status='Add another record';return true" onMouseOut="window.status='';return true"><img src="../images/add_another.gif" width="163" height="24" border="0"></a>-->
        </center>
        </p>
        </body>
        </html>
        <?php
        break;
    case "PHOTO":
        ?>
        <html>

        <head>
            <title>Add photo HERE</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F"
              onload="document.add_row.text.focus();">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new photo</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=photo" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text : </b></font></td>
                    <td><textarea name="text" cols="50" rows="10"></textarea></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>File : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="file" name="file1" size="53"><input
                                type="hidden" name="MAX_FILE_SIZE" value="50000"></font></td>
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
    case "MAP":
        ?>
        <html>

        <head>
            <title>Add map</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F"
              onload="document.add_row.imagemap.focus();">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new map</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=map" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td colspan="2">NOTE : When adding the image map code DO NOT include the &lt;MAP&gt; tags as the
                        system will create these.
                    </td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>File : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="file" name="file1" size="53"><input
                                type="hidden" name="MAX_FILE_SIZE" value="50000"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>ImageMap (HTML Code) : </b></font></td>
                    <td><textarea name="imagemap" cols="50" rows="10"></textarea></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Region links : </b></font></td>
                    <td><textarea name="regionlist" cols="50" rows="10"></textarea></td>
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
