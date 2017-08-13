<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    $db_name = str_replace("_", " ", $db_name);
    return $db_name;
}

?>
<html>
<head>

    <style type="text/css">
        <!--
        body {
            background-color: #317B84;
        }

        td {
            font-family: ms sans serif;
            font-size: 14pt;
            color: white;
        }

        /
        /
        -->
    </style>


    <script language="JavaScript">
        <!--

        rnd.today = new Date();
        rnd.seed = rnd.today.getTime();

        function rnd() {
            rnd.seed = (rnd.seed * 9301 + 49297) % 233280;
            return rnd.seed / (233280.0);
        }

        function rand(number) {
            return Math.ceil(rnd() * number);
        }

        function reshow(object) {
            subcategory = object.options[object.selectedIndex].value;
            if (subcategory == 'Select a subcategory') {
                document.category.subcategory.focus();
            }
            else {
                if (msie) window.document.frames[0].location.href = 'what.htm';
                else {
                    for (var i = document.region.names.length; i > 0; i--)
                        document.region.names.options[0] = null;
                    reloading = true;
                    showlinks();
                    document.region.names.options[0].selected = true;
                }
                return false;
            }
        }

        function load(object) {
            if (object.options[object.selectedIndex].value == 'Select a Region') {
                document.region.names.focus();
            }
            else {
                parent.home.location.href = 'pick_record_geo.php?category=webbirder&subcategory=' + object.options[object.selectedIndex].value + '&random=1115129184' + rand(255);
                return false;
            }
        }

        function quickload(place) {
            parent.home.location.href = 'pick_record_geo.php?category=webbirder&subcategory=webbirder&random=1115129184' + rand(255);
        }

        function showlinks() {
            quickload('webbirder');
        }

        }

        function opt(href, text) {
            if (reloading) {
                var optionName = new Option(text, href, false, false)
                var length = document.region.names.length;
                document.region.names.options[length] = optionName;
            }
            else
                document.write('<option value="', href, '">', text, '</option>');

        }
        //-->
    </script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;webbirder ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "webbirder";

                    $tables = array();
                    $tables[1] = "links_introduction";
                    $tables[2] = "links_contributor";
                    $tables[3] = "links_county_recorder";
                    $tables[4] = "links_number_species";
                    $tables[5] = "links_endemics";
                    $tables[6] = "links_festivals";
                    $tables[7] = "links_mailing_lists";
                    $tables[8] = "links_museums";
                    $tables[9] = "links_observatories";
                    $tables[10] = "links_organisations";
                    $tables[11] = "links_places_to_stay";
                    $tables[12] = "links";
                    $tables[13] = "links_artists_photographers";
                    $tables[14] = "links_top_sites";
                    $tables[15] = "links_useful_reading";
                    $tables[16] = "links_useful_information";
                    $tables[17] = "links_reserves";
                    $tables[18] = "links_trip_reports";
                    $tables[19] = "links_holiday_companies";
                    $tables[20] = "links_banners";
                    $tables[21] = "links_family_links";
                    $tables[22] = "links_species_links";

                    $sub_category = array();
                    $sub_category_cnt = 0;

                    $ids = '';
                    for ($i = 1; $i <= 22; $i++) {
//                        $sql = "SELECT DISTINCT `sub-category` FROM " . $tables[$i] . " WHERE `category` = '" . $category . "' ORDER BY `sub-category` ASC";
                        //echo $sql;
                        $sqlLinksTables = "SELECT `page_id` FROM " . $tables[$i];
                        $result2 = mysql_query($sqlLinksTables);
                        while ($row = mysql_fetch_assoc($result2)) {
                            $ids .= $row['page_id'] . ',';
                        }
                        //echo $sub_category[$sub_category_cnt];
                    }


                    $ids = trim($ids, ',');

                    $sql = "SELECT DISTINCT `sub-category` FROM `pagename` WHERE `category` = '" . $category .
                        "' AND `page_id` IN (" . $ids . ") ORDER BY `sub-category` ASC";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_assoc($result)) {
                        if (!(in_array($row["sub-category"], $sub_category))) {
                            $sub_category[$sub_category_cnt] = $row["sub-category"];
                            //echo "<option value=\"$sub_category[$sub_category_cnt]\">".alt_name($sub_category[$sub_category_cnt])."</option>";
                            $sub_category_cnt++;
                        }
                    }

                    //sort the array into alphabetical order
                    sort($sub_category);

                    for ($i = 0; $i < count($sub_category); $i++) {
                        echo "<option value=\"$sub_category[$i]\">" . alt_name($sub_category[$i]) . "</option>";
                    }
                    ?>
                </select>
            </form>
        </td>
        <td valign="top">&gt;</td>
        <td>
            <script language="JavaScript">
                <!--
                var reloading = false;
                var subcategory = document.category.subcategory.options[0].text;

                if (navigator.appVersion.indexOf('MSIE 3') != -1) var msie = true; else var msie = false;

                if (msie) {
                    document.write('<IFRAME FRAMEBORDER=0 SCROLLING=NO SRC="what.htm" WIDTH="100%" HEIGHT="100">');
                    document.write('</IFRAME>');
                }
                else {
                    document.write('<form name="region">');
                    document.write('<select name="names" onChange="return load(document.region.names)">');
                    showlinks();
                    document.write('</select>');
                    document.write('</form>');
                }
                //-->
            </script>
        </td>
    </tr>
</table>

</body>
</html>