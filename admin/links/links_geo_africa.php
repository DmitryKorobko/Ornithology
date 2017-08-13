<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    switch ($db_name) {
        case"petbirds":
            $db_name = "Pet Birds";
            break;
        case"south_africa_northern_province":
            $db_name = "Limpopo";
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
            for (var i = document.region.subcategory.length; i > 0; i--)
                document.region.subcategory.options[0] = null;
            reloading = true;
            showlinks();
            document.region.subcategory.options[0].selected = true;
        }
        return false;
    }
}

function load(object) {
    if (object.options[object.selectedIndex].value == 'Select a Region') {
        document.region.subcategory.focus();
    }
    else {
        parent.home.location.href = 'pick_record_geo.php?category=africa&subcategory=' + object.options[object.selectedIndex].value + '&random=1114793750' + rand(255);
        return false;
    }
}

function quickload(place) {
    parent.home.location.href = 'pick_record_geo.php?category=africa&subcategory=' + place + '&random=1114793750' + rand(255);
}

function showlinks() {
    if (subcategory == 'Algeria') {
        opt('', 'No further response required');
        quickload('algeria');
    }
    if (subcategory == 'Angola') {
        opt('', 'No further response required');
        quickload('angola');
    }
    if (subcategory == 'Benin') {
        opt('', 'No further response required');
        quickload('benin');
    }
    if (subcategory == 'Botswana') {
        opt('', 'No further response required');
        quickload('botswana');
    }
    if (subcategory == 'Burkina Faso') {
        opt('', 'No further response required');
        quickload('burkina_faso');
    }
    if (subcategory == 'Burundi') {
        opt('', 'No further response required');
        quickload('burundi');
    }
    if (subcategory == 'Cameroon') {
        opt('', 'No further response required');
        quickload('cameroon');
    }
    if (subcategory == 'Cape Verde') {
        opt('', 'No further response required');
        quickload('cape_verde');
    }
    if (subcategory == 'Central African Republic') {
        opt('', 'No further response required');
        quickload('central_african_republic');
    }
    if (subcategory == 'Chad') {
        opt('', 'No further response required');
        quickload('chad');
    }
    if (subcategory == 'Comoros') {
        opt('', 'No further response required');
        quickload('comoros');
    }
    if (subcategory == 'Congo (Republic of)') {
        opt('', 'No further response required');
        quickload('republic_of_congo');
    }
    if (subcategory == 'Congo (Democratic Republic of - formally Zaire)') {
        opt('', 'No further response required');
        quickload('congo');
    }
    if (subcategory == 'Djibiuti') {
        opt('', 'No further response required');
        quickload('djibiuti');
    }
    if (subcategory == 'Equatorial Guinea') {
        opt('', 'No further response required');
        quickload('equatorial_guinea');
    }
    if (subcategory == 'Eritrea') {
        opt('', 'No further response required');
        quickload('eritrea');
    }
    if (subcategory == 'Ethiopia') {
        opt('', 'No further response required');
        quickload('ethiopia');
    }
    if (subcategory == 'Egypt') {
        opt('', 'No further response required');
        quickload('egypt');
    }
    if (subcategory == 'Gabon') {
        opt('', 'No further response required');
        quickload('gabon');
    }
    if (subcategory == 'Gambia') {
        opt('', 'No further response required');
        quickload('gambia');
    }
    if (subcategory == 'Ghana') {
        opt('', 'No further response required');
        quickload('ghana');
    }
    if (subcategory == 'Guinea') {
        opt('', 'No further response required');
        quickload('guinea');
    }
    if (subcategory == 'Guinea Bissau') {
        opt('', 'No further response required');
        quickload('guinea_bissau');
    }
    if (subcategory == 'Ivory Coast') {
        opt('', 'No further response required');
        quickload('ivory_coast');
    }
    if (subcategory == 'Kenya') {
        opt('', 'No further response required');
        quickload('kenya');
    }
    if (subcategory == 'Lesotho') {
        opt('', 'No further response required');
        quickload('lesotho');
    }
    if (subcategory == 'Liberia') {
        opt('', 'No further response required');
        quickload('liberia');
    }
    if (subcategory == 'Libya') {
        opt('', 'No further response required');
        quickload('libya');
    }
    if (subcategory == 'Madagascar') {
        opt('', 'No further response required');
        quickload('madagascar');
    }
    if (subcategory == 'Malawi') {
        opt('', 'No further response required');
        quickload('malawi');
    }
    if (subcategory == 'Mali') {
        opt('', 'No further response required');
        quickload('mali');
    }
    if (subcategory == 'Mauritania') {
        opt('', 'No further response required');
        quickload('mauritania');
    }
    if (subcategory == 'Mauritius') {
        opt('', 'No further response required');
        quickload('mauritius');
    }
    if (subcategory == 'Morocco') {
        opt('', 'No further response required');
        quickload('morocco');
    }
    if (subcategory == 'Mozambique') {
        opt('', 'No further response required');
        quickload('mozambique');
    }
    if (subcategory == 'Namibia') {
        opt('', 'No further response required');
        quickload('namibia');
    }
    if (subcategory == 'Niger') {
        opt('', 'No further response required');
        quickload('niger');
    }
    if (subcategory == 'Nigeria') {
        opt('', 'No further response required');
        quickload('nigeria');
    }
    if (subcategory == 'R�union') {
        opt('', 'No further response required');
        quickload('reunion');
    }
    if (subcategory == 'Rwanda') {
        opt('', 'No further response required');
        quickload('rwanda');
    }
    if (subcategory == 'Sao Tome & Principe') {
        opt('', 'No further response required');
        quickload('sao_tome_principe');
    }
    if (subcategory == 'Senegal') {
        opt('', 'No further response required');
        quickload('senegal');
    }
    if (subcategory == 'Seychelles') {
        opt('', 'No further response required');
        quickload('seychelles');
    }
    if (subcategory == 'Sierra Leone') {
        opt('', 'No further response required');
        quickload('sierra_leone');
    }
    if (subcategory == 'Somali Republic') {
        opt('', 'No further response required');
        quickload('somali_republic');
    }
    if (subcategory == 'South Africa') {
        opt('Select a Region', 'Select a Region');
        opt('south_africa_eastern_cape', 'Eastern Cape ');
        opt('south_africa_free_state', 'Free State');
        opt('south_africa_gauteng', 'Gauteng');
        opt('south_africa_kwazulu_natal', 'KwaZulu/Natal');
        opt('south_africa_mpumalanga', 'Mpumalanga');
        opt('south_africa_northern_province', 'Northern Province');
        opt('south_africa_northern_cape', 'Northern Cape');
        opt('south_africa_north-west_province', 'North-West Province');
        opt('south_africa_western_cape', 'Western Cape');
        opt('south_africa', 'South Africa - general');
    }
    if (subcategory == 'Sudan') {
        opt('', 'No further response required');
        quickload('sudan');
    }
    if (subcategory == 'Swaziland') {
        opt('', 'No further response required');
        quickload('swaziland');
    }
    if (subcategory == 'Tanzania') {
        opt('', 'No further response required');
        quickload('tanzania');
    }
    if (subcategory == 'Togo') {
        opt('', 'No further response required');
        quickload('togo');
    }
    if (subcategory == 'Tunisia') {
        opt('', 'No further response required');
        quickload('tunisia');
    }
    if (subcategory == 'Uganda') {
        opt('', 'No further response required');
        quickload('uganda');
    }
    if (subcategory == 'Western Sahara') {
        opt('', 'No further response required');
        quickload('western_sahara');
    }
    if (subcategory == 'Zambia') {
        opt('', 'No further response required');
        quickload('zambia');
    }
    if (subcategory == 'Zimbabwe') {
        opt('', 'No further response required');
        quickload('zimbabwe');
    }
    if (subcategory == 'General Africa') {
        opt('', 'No further response required');
        quickload('africa');
    }
    if (subcategory != 'Select a subcategory' && subcategory != '') {
        opt('', 'No further response required');
        quickload(subcategory);
    }
}

function opt(href, text) {
    if (reloading) {
        var optionName = new Option(text, href, false, false)
        var length = document.region.subcategory.length;
        document.region.subcategory.options[length] = optionName;
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Africa ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "africa";

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
                    document.write('<select name="subcategory" onChange="return load(document.region.subcategory)">');
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