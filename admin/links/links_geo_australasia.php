<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list function

function alt_name($db_name)
{
    switch ($db_name) {
        case"petbirds":
            $db_name = "Pet Birds";
            break;
        default:
            $db_name = str_replace("_", " ", $db_name);
    }
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
                parent.home.location.href = 'pick_record_geo.php?category=australasia&subcategory=' +

                    object.options[object.selectedIndex].value + '&random=1115129864' + rand(255);
                return false;
            }
        }

        function quickload(place) {
            parent.home.location.href = 'pick_record_geo.php?category=australasia&subcategory=' + place + '&random=1115129864' + rand(255);
        }

        function showlinks() {
            if (subcategory == 'American Samoa') {
                opt('', 'No further response required');
                quickload('american_samoa');
            }
            if (subcategory == 'Australia') {
                opt('Select a Region', 'Select a Region');
                opt('australia_new_south_wales', 'New South Wales');
                opt('australia_northern_territories', 'Northern Territories');
                opt('australia_queensland', 'Queensland');
                opt('australia_south_australia', 'South Australia');
                opt('australia_tasmania', 'Tasmania');
                opt('australia_victoria', 'Victoria');
                opt('australia_western_australia', 'Western Australia');
                opt('australia', 'General Australia');
            }
            if (subcategory == 'Cocos (Keeling) Islands') {
                opt('', 'No further response required');
                quickload('australia_cocos_(keeling)_islands');
            }
            if (subcategory == 'Cook Islands') {
                opt('', 'No further response required');
                quickload('cook_islands');
            }
            if (subcategory == 'Christmas Island') {
                opt('', 'No further response required');
                quickload('christmas_island');
            }
            if (subcategory == 'Fiji') {
                opt('', 'No further response required');
                quickload('fiji');
            }
            if (subcategory == 'French Polynesia') {
                opt('', 'No further response required');
                quickload('french_polynesia');
            }
            if (subcategory == 'Niue') {
                opt('', 'No further response required');
                quickload('niue');
            }
            if (subcategory == 'New Caledonia') {
                opt('', 'No further response required');
                quickload('new_caledonia');
            }
            if (subcategory == 'New Zealand') {
                opt('', 'No further response required');
                quickload('new_zealand');
            }
            if (subcategory == 'Norfolk Island') {
                opt('', 'No further response required');
                quickload('norfolk_island');
            }
            if (subcategory == 'Papua New Guinea') {
                opt('', 'No further response required');
                quickload('papua_new_guinea');
            }
            if (subcategory == 'Soloman Isles') {
                opt('', 'No further response required');
                quickload('soloman_isles');
            }
            if (subcategory == 'Tonga') {
                opt('', 'No further response required');
                quickload('tonga');
            }
            if (subcategory == 'Tuvalu') {
                opt('', 'No further response required');
                quickload('tuvalu');
            }
            if (subcategory == 'Vanuatu') {
                opt('', 'No further response required');
                quickload('vanuatu');
            }
            if (subcategory == 'Western Samoa') {
                opt('', 'No further response required');
                quickload('western_samoa');
            }
            if (subcategory == 'General Australasia') {
                opt('', 'No further response required');
                quickload('australasia');
            }
            if (subcategory != 'Select a subcategory' && subcategory != '') {
                opt('', 'No further response required');
                quickload(subcategory);
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Austrolasia ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "australasia";

                    $tables = array();
                    $tables[1] = "introduction";
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