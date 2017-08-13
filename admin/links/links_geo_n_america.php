<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    switch ($db_name) {
        case"n_america":
            $db_name = "General North America";
            break;
        case"canada":
            $db_name = "General Canada";
            break;
        case"mexico":
            $db_name = "General Mexico";
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
            parent.home.location.href = 'pick_record_geo.php?category=n_america&subcategory=' + object.options[object.selectedIndex].value + '&random=1115133393' + rand(255);
            return false;
        }
    }

    function quickload(place) {
        parent.home.location.href = 'pick_record_geo.php?category=n_america&subcategory=' + place + '&random=1115133393' + rand(255);
    }

    function showlinks() {
        if (subcategory == 'Bermuda') {
            opt('', 'No further response required');
            quickload('bermuda');
        }
        if (subcategory == 'St. Pierre et Miquelon') {
            opt('', 'No further response required');
            quickload('st_pierre_et_miquelon');
        }
        if (subcategory == 'Mexico') {
            opt('Select a Region', 'Select a Region');
            opt('mexico_aguascalientes', 'Aguascalientes');
            opt('mexico_baja_california', 'Baja California');
            opt('mexico_baja_california_sur', 'Baja California Sur');
            opt('mexico_campeche', 'Campeche');
            opt('mexico_chiapas', 'Chiapas');
            opt('mexico_chihuahua', 'Chihuahua');
            opt('mexico_coahuila', 'Coahuila');
            opt('mexico_colima', 'Colima');
            opt('mexico_durango', 'Durango');
            opt('mexico_guanajuato', 'Guanajuato');
            opt('mexico_guerrero', 'Guerrero');
            opt('mexico_hidalgo', 'Hidalgo');
            opt('mexico_jalisco', 'Jalisco');
            opt('mexico_mexico', 'M�xico');
            opt('mexico_michoacan', 'Michoac�n');
            opt('mexico_morelos', 'Morelos');
            opt('mexico_nayarit', 'Nayarit');
            opt('mexico_nuevo_leon', 'Nuevo Le�n');
            opt('mexico_oaxaca', 'Oaxaca');
            opt('mexico_puebla', 'Puebla');
            opt('mexico_queretaro', 'Quer�taro');
            opt('mexico_quintana_roo', 'Quintana Roo');
            opt('mexico_san_luis_potos�', 'San Luis Potos�');
            opt('mexico_sinaloa', 'Sinaloa');
            opt('mexico_sonora', 'Sonora');
            opt('mexico_tabasco', 'Tabasco');
            opt('mexico_tamaulipas', 'Tamaulipas');
            opt('mexico_tlaxcala', 'Tlaxcala');
            opt('mexico_veracruz', 'Veracruz');
            opt('mexico_yucatan', 'Yucat�n');
            opt('mexico_zacatecas', 'Zacatecas');
            opt('mexico_districto_federal', 'Districto Federal');
            opt('mexico', 'Mexico General');
        }
        if (subcategory == 'Canada') {
            opt('Select a Region', 'Select a Region');
            opt('alberta', 'Alberta');
            opt('british_columbia', 'British Columbia');
            opt('manitoba', 'Manitoba');
            opt('new_brunswick', 'New Brunswick');
            opt('newfoundland', 'Newfoundland');
            opt('nova_scotia', 'Nova Scotia');
            opt('nunavut', 'Nunavut');
            opt('nw_territory', 'NW Terriory');
            opt('ontario', 'Ontario');
            opt('prince_edward_island', 'Prince Edward Island');
            opt('quebec', 'Quebec');
            opt('saskatchewan', 'Saskatchewan');
            opt('yukon', 'Yukon');
            opt('canada', 'General Canada');
        }
        if (subcategory == 'United States') {
            opt('Select a Region', 'Select a Region');
            opt('alabama', 'Alabama');
            opt('alaska', 'Alaska');
            opt('arizona', 'Arizona');
            opt('arkansas', 'Arkansas');
            opt('california', 'California');
            opt('colorado', 'Colorado');
            opt('connecticut', 'Connecticut');
            opt('delaware', 'Delaware');
            opt('district_columbia', 'District of Columbia');
            opt('florida', 'Florida');
            opt('georgia', 'Georgia');
            opt('hawaii', 'Hawaii');
            opt('idaho', 'Idaho');
            opt('illinois', 'Illinois');
            opt('indiana', 'Indiana');
            opt('iowa', 'Iowa');
            opt('kansas', 'Kansas');
            opt('kentucky', 'Kentucky');
            opt('louisianna', 'Lousianna');
            opt('maine', 'Maine');
            opt('maryland', 'Maryland');
            opt('massachusetts', 'Massachusetts');
            opt('michigan', 'Michigan');
            opt('minnesota', 'Minnesota');
            opt('mississippi', 'Mississippi');
            opt('missouri', 'Missouri');
            opt('montana', 'Montana');
            opt('nebraska', 'Nebraska');
            opt('nevada', 'Nevada');
            opt('new_hampshire', 'New Hampshire');
            opt('new_jersey', 'New Jersey');
            opt('new_mexico', 'New Mexico');
            opt('new_york', 'New York');
            opt('north_carolina', 'North Carolina');
            opt('north_dakota', 'North Dakota');
            opt('ohio', 'Ohio');
            opt('oklahoma', 'Oklahoma');
            opt('oregon', 'Oregon');
            opt('pennsylvania', 'Pennsylvania');
            opt('rhode_island', 'Rhode Island');
            opt('south_carolina', 'South Carolina');
            opt('south_dakota', 'South Dakota');
            opt('tennesse', 'Tennesse');
            opt('texas', 'Texas');
            opt('utah', 'Utah');
            opt('vermont', 'Vermont');
            opt('virginia', 'Virginia');
            opt('washington', 'Washington');
            opt('west_virginia', 'West Virginia');
            opt('wisconsin', 'Wisconsin');
            opt('wyoming', 'Wyoming');
            opt('united_states', 'General United States');
        }
        if (subcategory == 'General North America') {
            opt('', 'No further response required');
            quickload('n_america');
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;North America > </font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "n_america";

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