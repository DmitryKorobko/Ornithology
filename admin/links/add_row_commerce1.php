<?php
include("../../includes/config.inc.php");
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

if ($HTTP_GET_VARS['mode'] <> "") {
    $mode = $HTTP_GET_VARS['mode'];
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
echo "mode = $mode"; //both start empty
echo "action =  $action";
//echo $action;
//perform article update/insert/delete

if ($action) {
    $sqlPage = "SELECT `page_id` FROM `pagename` WHERE `category` = '" . $category . "' AND `sub-category` = '" . $subcategory . "'";
    $result = mysql_query($sqlPage);
    while ($row = mysql_fetch_assoc($result)) {
        $pageId = $row['page_id'];
    }
    switch ($action) {
        case "intro" :
            //get the latest paragraph number
//            $select = "SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
//            $result = mysql_query($select);
//            $pageId = "";
//            while ($row = mysql_fetch_assoc($result)) { $pageId .= $row['page_id']; }
            $sqlsel = "SELECT * FROM $info_type WHERE `page_id`='" . $pageId . "'";
            //echo $sqlsel;
            $result = mysql_query($sqlsel);
            //get the new paragraph number
            $paragraph = mysql_num_rows($result) + 1;
            //do sql insert
            $sqlsel = "INSERT INTO $info_type " . LINKS_INTRO_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['pagraph_num'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "sublinks" :
            $sqlsel = "INSERT INTO $info_type " . SUBLINKS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['sublinks'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            //echo $sqlquery;
            break;

        case "contributor" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_CONTRIBUTOR_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['name'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['location'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['email'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1_title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "recorder" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_RECORDER_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['name'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['address'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['telephone'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['fax'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['email'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "species" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_SPECIES_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "links" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_LINKS_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1_title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['link_desc'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "links2" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_LINKS2_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1_title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url2'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url2_title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "mailing" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_MAILING_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['url1_title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['post_email'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['contact'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['sub_email'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['unsub_email'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['sub_message'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['body_message'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "topsites" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_TOPSITES_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['grid_reference'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "useful" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_USEFUL_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['isbn'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;

        case "useful_info" :
            $sqlsel = "INSERT INTO $info_type " . LINKS_USEFULINFO_FIELDS . " VALUES (";
            $sqlsel .= "'" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . htmlspecialchars($_POST['text'], ENT_QUOTES) . "', ";
            $sqlsel .= "'" . $pageId . "')";
            $sqlquery = $sqlsel;
            break;
    }

    //echo $sqlquery;
    $result = mysql_query($sqlquery);
    if ($result) {
        $success = 1;
        $message = "Article added";
    } else {
        $success = 0;
        $message = "Article could not be added";
    }

    //create new HTML file based on database for the given section
    /*if ($success==1){
     $tmpl_dir = "";
     $tmpl_name = "template.htm";

     //create the correct output dir and html file name based on subcategory and category
     list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
     $html_dir = dir_const.$alt_dir;
     $html_name = $alt_file;

     $chmodf = chmod_file.$alt_dir;
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

     ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");
     }*/

    //get a hanlde for the recoed taht has just been added
    $new_article_id = mysql_insert_id();
    $mode = "SUCCESS";
}

//use the table names to display the relavent insert forms as long as the mode variable is blank
if (!$mode) {
    switch ($info_type) {
        case "links" :
        case "links_artists_photographers" :
        case "links_festivals" :
        case "links_museums" :
        case "links_organisations" :
        case "links_places_to_stay" :
        case "links_trip_reports" :
        case "links_holiday_companies" :
        case "links_family_links" :
        case "links_species_links" :
        case "links_blogs" :
            $mode = "LINKS";
            break;
        case "links_endemics" :
        case "links_number_species" :
            $mode = "SPECIES";
            break;
        case "introduction" :
            $mode = "INTRO";
            break;
        case "links_contributor" :
            $mode = "CONTRIBUTOR";
            break;
        case "links_county_recorder" :
            $mode = "RECORDER";
            break;
        case "links_mailing_lists" :
            $mode = "MAILING";
            break;
        case "links_top_sites" :
        case "links_reserves" :
        case "links_observatories" :
            $mode = "LINKS2";
            break;
        case "links_useful_reading" :
            $mode = "USEFUL";
            break;
        case "links_useful_information" :
            $mode = "USEFUL_INFO";
            break;
        case "sublinks" :
            $mode = "SUBLINKS";
            break;
    }
}
echo "mode2 = $mode";
switch ($mode) {
    case "ADD":
        //get the latest paragraph number
        $sqlsel = "SELECT * FROM $text WHERE article=" . $article_id;
        //echo $sqlsel;
        $result = mysql_query($sqlsel);
        $paragraph = mysql_num_rows($result) + 1;
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body>
        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record Hi</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=insert" method="post">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Paragraph : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="5" name="paragraph"
                                                                   value="<?php echo $paragraph; ?>"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="53" rows="9" name="text"></textarea></font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><a href="javascript:document.add_row.submit()">Submit</a>&nbsp;&nbsp;
                        <a href="javascript:window.close()">Cancel</a>
                        <input type="hidden" name="article" value="<?php echo $article_id; ?>">
                        <input type="hidden" name="dbtable" value="<?php echo $text; ?>"></td>
                </tr>
            </table>
        </form>
        </body>
        </html>
        <?php
        break;
    case "SUCCESS":
        ?>
        <html>

        <head>
            <title>Add new row</title>

            <script language="JavaScript">
                <!--

                refreshed = 'no';

                function refreshPickRecord(js_category, js_subcategory, js_random) {
                    opener.location.href = '<?php echo $ref_url; ?>
                        ? "category = ' + js_category + ' & subcategory = ' + js_subcategory + ' & random = ' + js_random";
                        refreshed = 'yes';
                }

                function checkrefreshPickRecord(js_category, js_subcategory, js_random) {
                    if (refreshed == 'no') {
                        opener.location.href = '
                            <?php echo $ref_url; ?>
                            ? "category = ' + js_category + ' & subcategory = ' + js_subcategory + ' & random = ' + js_random";
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
        while ($row = mysql_fetch_assoc($result)) {

            switch ($info_type) {
                case "links":
                case "links_artists_photographers":
                case "links_festivals":
                case "links_museums":
                case "links_organisations":
                case "links_places_to_stay":
                case "links_trip_reports":
                case "links_holiday_companies":
                case "links_family_links":
                case "links_species_links":
                case "links_blogs":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["url1_title"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["link_desc"]
                                ); ?></font></td>
                    </tr>
                    <?php
                    break;
                case "links_top_sites":
                case "links_observatories":
                case "links_reserves":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["url1_title"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL 2 : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url2"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["link_desc"]
                                ); ?></font></td>
                    </tr>
                    <?php
                    break;
                case "links_endemics":
                case "links_number_species":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>No. species : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo $row["title"]; ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "introduction":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Paragraph: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo $row["paragraph"]; ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "links_contributor":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Name: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["name"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Location: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["location"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Email: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["email"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["url1_title"]
                                ); ?></font></td>
                    </tr>
                    <?php
                    break;
                case "links_county_recorder":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Name: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["name"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Address: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["address"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Telephone: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["telephone"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Fax: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["fax"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Email: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["email"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "links_mailing_lists":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["url1"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>URL Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["url1_title"]
                                ); ?></font></td>
                    </tr>

                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Post: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["post_email"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Contact: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["contact"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Subscribe : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["sub_email"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Unsubscribe : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["unsub_email"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Subject : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["sub_message"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Body : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["body_message"]
                                ); ?></font></td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "links_useful_reading":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>ISBN: </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["isbn"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "links_useful_information":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Title : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["title"]); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Text : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode($row["text"]); ?></font>
                        </td>
                    </tr>
                    <?php
                    break;
                case "sublinks":
                    ?>
                    <tr>
                        <td valign="top"><font face="Verdana,Arial" size="1"><b>Sub Links : </b></font></td>
                        <td><font face="Verdana,Arial" size="1"><?php echo html_entity_decode(
                                    $row["sublinks"]
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
                                                                  height="24" border="0"></a>&nbsp;&nbsp;<a
                href="<?php echo $_SERVER['PHP_SELF']; ?>?category=<?php echo $category; ?>&info_type=<?php echo $info_type; ?>&subcategory=<?php echo $subcategory; ?>&random=1114596898&ref_url=<?php echo $ref_url; ?>"
                onMouseOver="window.status='Add another record';return true"
                onMouseOut="window.status='';return true"><img src="../images/add_another.gif" width="163" height="24"
                                                               border="0"></a></center>
        </p>
        </body>
        </html>
        <?php
        break;
    case "SUBLINKS":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php echo $info_type; ?>"
              method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">

            <table cellpadding="3">
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Sub Links: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="53" rows="9"
                                                                      name="sublinks"></textarea></font></td>
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
    case "LINKS":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=links" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1_title"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="58" rows="9"
                                                                      name="link_desc"></textarea></font></td>
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
    case "LINKS2":
        ?>
        <html>
        <head>
            <title>Add new row</title>
        </head>
        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">
        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=links2" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3">
                            <input type="text" size="67" name="title">
                        </font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL Title: </b></font></td>
                    <td><font face="ms sans serif" size="3">
                            <input type="text" size="67" name="url1_title">
                        </font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL 2 : </b></font></td>
                    <td><font face="ms sans serif" size="3">
                            <input type="text" size="67" name="url2">
                        </font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL 2 Title: </b></font></td>
                    <td><font face="ms sans serif" size="3">
                            <input type="text" size="67" name="url2_title" value="Satellite View">
                        </font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3">
                            <textarea cols="58" rows="9" name="text"></textarea>
                        </font></td>
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
    case "SPECIES":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=species" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td nowrap><font face="Verdana,Arial" size="2"><b>No. species : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="60" name="title"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="52" rows="10" name="text"></textarea></font>
                    </td>
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
    case "INTRO":
        echo "info_type = $info_type";
//get the latest paragraph number
        $select = "SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
        $result = mysql_query($select);
        $pageId = "";
        while ($row = mysql_fetch_assoc($result)) { $pageId .= $row['page_id']; }
        $sqlsel = "SELECT * FROM $info_type WHERE `page_id`='" . $pageId . "'";
//echo $sqlsel;
        $result = mysql_query($sqlsel);
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body>
        <p><font face="Verdana,Arial" size="5">Add new record intro</font></p>
        <?php

        $introduction = new Introdisplay("", $category, $subcategory);
        echo $introduction->getDisplayAdmin();

        ?>
        <!--<form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=intro" method="post">
<input type="hidden" name="category" value="<?php echo $category; ?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
<input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
<input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">

<table cellpadding="3">
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
        <td><font face="ms sans serif" size="3"><textarea cols="53" rows="9" name="text"></textarea></font></td>
    </tr>
    <tr>
        <td colspan="2"align="right"><input type="submit" />&nbsp;&nbsp;
        	<a href="javascript:window.close()">Cancel</a></td>
    </tr>
</table>
</form>-->
        </body>
        </html>
        <?php
        break;
    case "CONTRIBUTOR":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F"
              onLoad="document.add_row.name.focus();">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=contributor" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Name : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="63" name="name"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Location : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="location"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="email"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1_title"></font></td>
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
    case "RECORDER":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=recorder" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Name : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="64" name="name"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Address : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="64" name="address"></font></td>
                </tr>
                <tr>
                    <td nowrap><font face="Verdana,Arial" size="2"><b>Telephone: </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="64" name="telephone"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Fax : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="64" name="fax"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Email : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="64" name="email"></font></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <a href="javascript:document.add_row.submit()"
                           onMouseOver="window.status='Add this new record to the current page';return true"
                           onMouseOut="window.status='';return true">
                            <img src="../images/submit_changes_button.gif" width="126" height="24" border="0"></a>&nbsp;&nbsp;
                        <a href="javascript:window.close()"
                           onMouseOver="window.status='Abandon your changes and close this window';return true"
                           onMouseOut="window.status='';return true">
                            <img src="../images/abandon_changes_button.gif" width="163" height="24" border="0"></a>
                    </td>
                </tr>
            </table>
        </form>
        </body>
        </html>
        <?php
        break;
    case "MAILING":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=mailing" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="title"></font></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>URL : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1"></font></td>
                    <td><font face="Verdana,Arial" size="2"><b>URL Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="url1_title"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Post: </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="post_email"></font></td>
                    <td><font face="Verdana,Arial" size="2"><b>Contact: </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="contact"></font></td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Subscribe : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="sub_email"></font></td>
                    <td><font face="Verdana,Arial" size="2"><b>Unsubscribe : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="unsub_email"></font></td>
                </tr>
                <tr>
                    <td nowrap><font face="Verdana,Arial" size="2"><b>Subject : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="sub_message"></font></td>
                    <td><font face="Verdana,Arial" size="2"><b>Body : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="24" name="body_message"></font>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td colspan="3"><font face="ms sans serif" size="3"><textarea cols="54" rows="5"
                                                                                  name="text"></textarea></font></td>
                </tr>
                <tr>
                    <td colspan="4" align="right"><a href="javascript:document.add_row.submit()"
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
    case "TOPSITES":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=topsites" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title"></font></td>
                </tr>
                <tr>
                    <td nowrap><font face="Verdana,Arial" size="2"><b>Grid Ref : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="grid_reference"></font>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="55" rows="8" name="text"></textarea></font>
                    </td>
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
    case "USEFUL":
        ?>
        <html>

        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=useful" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="57" name="title"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text : </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="45" rows="8" name="text"></textarea></font>
                    </td>
                </tr>
                <tr>
                    <td><font face="Verdana,Arial" size="2"><b>ISBN : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="57" name="isbn"></font></td>
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
    case "USEFUL_INFO":
        ?>
        <html>
        <head>
            <title>Add new row</title>
        </head>

        <body text="#FFFFFF" background="../images/popup_edit_row_background.jpg" bgcolor="#13474F">

        <p><font face="Verdana,Arial" size="5" color="#FAD400">Add new record</font></p>

        <form name="add_row" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=useful_info" method="post">
            <input type="hidden" name="pagraph_num" value="<?php echo $paragraph; ?>">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <input type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
            <input type="hidden" name="info_type" value="<?php echo $info_type; ?>">
            <input type="hidden" name="ref_url" value="<?php echo $ref_url; ?>">
            <table cellpadding="3">
                <tr>
                    <td nowrap><font face="Verdana,Arial" size="2"><b>Title : </b></font></td>
                    <td><font face="ms sans serif" size="3"><input type="text" size="67" name="title"></font></td>
                </tr>
                <tr>
                    <td valign="top"><font face="Verdana,Arial" size="2"><b>Text: </b></font></td>
                    <td><font face="ms sans serif" size="3"><textarea cols="58" rows="10" name="text"></textarea></font>
                    </td>
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
