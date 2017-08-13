<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    switch ($db_name) {
        case"bird_humour":
            $db_name = "Bird Humour";
            break;
        case"blogs":
            $db_name = "Blogs";
            break;
        case"urban_myths":
            $db_name = "Birding stories and urban myths";
            break;
        case"facts":
            $db_name = "Facts";
            break;
        case"top_tens":
            $db_name = "Fatbirder top 10";
            break;
        case"hints_and_tips":
            $db_name = "Hints & Tips";
            break;
        case"homepages":
            $db_name = "Miscellany";
            break;
        case"quizzes":
            $db_name = "Quizzes";
            break;
        case"stamps":
            $db_name = "Stamps";
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
            if (subcategory == 'Select a category') {
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
            if (object.options[object.selectedIndex].value == 'Select a sub-category') {
                document.region.names.focus();
            }
            else {
                parent.home.location.href = 'pick_record.php?category=fun&subcategory=' + object.options[object.selectedIndex].value + '&random=1114685104' + rand(255);
                return false;
            }
        }

        function quickload(place) {
            parent.home.location.href = 'pick_record.php?category=fun&subcategory=' + place + '&random=1114685104' + rand(255);
        }

        function showlinks() {
            if (subcategory == 'Bird Humour') {
                opt('', 'No further response required');
                quickload('bird_humour');
            }
            if (subcategory == 'Blogs') {
                opt('', 'No further response required');
                quickload('blogs');
            }
            if (subcategory == 'Birding stories and urban myths') {
                opt('', 'No further response required');
                quickload('urban_myths');
            }
            if (subcategory == 'Facts') {
                opt('', 'No further response required');
                quickload('facts');
            }
            if (subcategory == 'Fatbirder top 10') {
                opt('', 'No further response required');
                quickload('top_tens');
            }
            if (subcategory == 'Hints & Tips') {
                opt('', 'No further response required');
                quickload('hints_and_tips');
            }
            if (subcategory == 'Miscellany') {
                opt('', 'No further response required');
                quickload('homepages');
            }
            if (subcategory == 'Quizzes') {
                opt('', 'No further response required');
                quickload('quizzes');
            }
            if (subcategory == 'Stamps') {
                opt('', 'No further response required');
                quickload('stamps');
            }
            if (subcategory != 'Select a category' && subcategory != '') {
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Fun pages></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a category</option>
                    <?php

                    $category = "fun";

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
                    $tables[23] = "links_blogs";
                    $tables[24] = "sublinks";

                    $sub_category = array();
                    $sub_category_cnt = 0;

                    for ($i = 1; $i <= 24; $i++) {

                        $sql = "SELECT DISTINCT `sub-category` FROM " . $tables[$i] . " WHERE `category` = '" . $category . "' ORDER BY `sub-category` ASC";
                        //echo $sql;
                        $result = mysql_query($sql);
                        while ($row = mysql_fetch_assoc($result)) {

                            //echo $row["sub-category"];

                            if (!(in_array($row["sub-category"], $sub_category))) {
                                $sub_category[$sub_category_cnt] = $row["sub-category"];
                                $sub_category_cnt++;
                            }
                        }

                        //echo $sub_category[$sub_category_cnt];

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
