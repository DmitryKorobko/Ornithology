<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    switch ($db_name) {
        case"c_america":
            $db_name = "General Central America";
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
        parent.home.location.href = 'pick_record_geo.php?category=europe&subcategory=' + object.options[object.selectedIndex].value + '&random=1115131379' + rand(255);
        return false;
    }
}

function quickload(place) {
    parent.home.location.href = 'pick_record_geo.php?category=europe&subcategory=' + place + '&random=1115131379' + rand(255);
}


function showlinks() {
    if (subcategory == 'Albania') {
        opt('', 'No further response required');
        quickload('albania');
    }
    if (subcategory == 'Andorra') {
        opt('', 'No further response required');
        quickload('andorra');
    }
    if (subcategory == 'Armenia') {
        opt('', 'No further response required');
        quickload('armenia');
    }
    if (subcategory == 'Austria') {
        opt('', 'No further response required');
        quickload('austria');
    }
    if (subcategory == 'Azerbaijan') {
        opt('', 'No further response required');
        quickload('azerbaijan');
    }
    if (subcategory == 'Belgium') {
        opt('', 'No further response required');
        quickload('belgium');
    }
    if (subcategory == 'Belarus') {
        opt('', 'No further response required');
        quickload('belarus');
    }
    if (subcategory == 'Bosnia') {
        opt('', 'No further response required');
        quickload('bosnia');
    }
    if (subcategory == 'Bulgaria') {
        opt('', 'No further response required');
        quickload('bulgaria');
    }
    if (subcategory == 'Croatia') {
        opt('', 'No further response required');
        quickload('croatia');
    }
    if (subcategory == 'Cyprus') {
        opt('', 'No further response required');
        quickload('cyprus');
    }
    if (subcategory == 'Czech Republic') {
        opt('', 'No further response required');
        quickload('czech_republic');
    }
    if (subcategory == 'Denmark') {
        opt('Select a Region', 'Select a Region');
        opt('denmark_greenland', 'Greenland');
        opt('denmark_faeroe_islands', 'Faeroe Islands');
        opt('denmark', 'Denmark General');
    }
    if (subcategory == 'Estonia') {
        opt('', 'No further response required');
        quickload('estonia');
    }
    if (subcategory == 'Finland') {
        opt('', 'No further response required');
        quickload('finland');
    }
    if (subcategory == 'France') {
        opt('Select a Region', 'Select a Region');
        opt('france_alsace', 'Alsace');
        opt('france_aquitaine', 'Aquitaine');
        opt('france_auvergne', 'Auvergne');
        opt('france_basse_normandie', 'Basse-Normandie (Lower-Normandy)');
        opt('france_bourgogne', 'Bourgogne (Burgundy)');
        opt('france_bretagne', 'Bretagne (Brittany)');
        opt('france_centre', 'Centre');
        opt('france_champagne-ardenne', 'Champagne-Ardenne');
        opt('france_corsica', 'Corse (Corsica)');
        opt('france_franche_comte', 'Franche-Comt�');
        opt('france_haute_normandie', 'Haute-Normandie (Upper-Normandy)');
        opt('france_ile_de_france', 'Ile-de-France');
        opt('france_languedoc_roussillon', 'Languedoc-Roussillon');
        opt('france_limousin', 'Limousin');
        opt('france_lorraine', 'Lorraine');
        opt('france_midi_pyrenees', 'Midi-Pyr�n�es');
        opt('france_nord_pas_de_calais', 'Nord-Pas-de-Calais');
        opt('france_pays_de_la_loire', 'Pays de la Loire');
        opt('france_picardie', 'Picardie');
        opt('france_poitou_charentes', 'Poitou-Charentes');
        opt('france_provence_alpes_cote_dazur', 'Provence-Alpes-C�te d\'Azur');
        opt('france_rhone_alpes', 'Rh�ne-Alpes');
        opt('france', 'France - General');
    }
    if (subcategory == 'Georgia') {
        opt('', 'No further response required');
        quickload('georgia');
    }
    if (subcategory == 'Germany') {
        opt('', 'No further response required');
        quickload('germany');
    }
    if (subcategory == 'Gibraltar') {
        opt('', 'No further response required');
        quickload('gibraltar');
    }
    if (subcategory == 'Greece') {
        opt('Select a Region', 'Select a Region');
        opt('greece_crete', 'Crete');
        opt('greece_lesvos', 'Lesvos');
        opt('greece_rhodes', 'Rhodes');
        opt('greece', 'Greece - General');
    }
    if (subcategory == 'Holland') {
        opt('', 'No further response required');
        quickload('holland');
    }
    if (subcategory == 'Hungary') {
        opt('', 'No further response required');
        quickload('hungary');
    }
    if (subcategory == 'Iceland') {
        opt('', 'No further response required');
        quickload('iceland');
    }
    if (subcategory == 'Irish Republic') {
        opt('', 'No further response required');
        quickload('irish_republic');
    }
    if (subcategory == 'Italy') {
        opt('', 'No further response required');
        quickload('italy');
    }
    if (subcategory == 'Kosovo') {
        opt('', 'No further response required');
        quickload('kosovo');
    }
    if (subcategory == 'Latvia') {
        opt('', 'No further response required');
        quickload('latvia');
    }
    if (subcategory == 'Lichtenstein') {
        opt('', 'No further response required');
        quickload('lichtenstein');
    }
    if (subcategory == 'Lithuania') {
        opt('', 'No further response required');
        quickload('lithuania');
    }
    if (subcategory == 'Luxemburg') {
        opt('', 'No further response required');
        quickload('luxemburg');
    }
    if (subcategory == 'Macedonia') {
        opt('', 'No further response required');
        quickload('macedonia');
    }
    if (subcategory == 'Malta') {
        opt('', 'No further response required');
        quickload('malta');
    }
    if (subcategory == 'Moldova') {
        opt('', 'No further response required');
        quickload('moldova');
    }
    if (subcategory == 'Monaco') {
        opt('', 'No further response required');
        quickload('monaco');
    }
    if (subcategory == 'Montenegro') {
        opt('', 'No further response required');
        quickload('montenegro');
    }
    if (subcategory == 'Norway') {
        opt('', 'No further response required');
        quickload('norway');
    }
    if (subcategory == 'Poland') {
        opt('', 'No further response required');
        quickload('poland');
    }
    if (subcategory == 'Portugal') {
        opt('Select a Region', 'Select a Region');
        opt('portugal_azores', 'Azores');
        opt('portugal_madeira', 'Madeira');
        opt('portugal', 'Portugal - General');
    }
    if (subcategory == 'Romania') {
        opt('', 'No further response required');
        quickload('romania');
    }
    if (subcategory == 'Russia') {
        opt('', 'No further response required');
        quickload('russia');
    }
    if (subcategory == 'San Marino') {
        opt('', 'No further response required');
        quickload('san_marino');
    }
    if (subcategory == 'Serbia') {
        opt('', 'No further response required');
        quickload('serbia');
    }
    if (subcategory == 'Siberia') {
        opt('', 'No further response required');
        quickload('siberia');
    }
    if (subcategory == 'Slovakia') {
        opt('', 'No further response required');
        quickload('slovakia');
    }
    if (subcategory == 'Slovenia') {
        opt('', 'No further response required');
        quickload('slovenia');
    }
    if (subcategory == 'Spain') {
        opt('Select a Region', 'Select a Region');
        opt('spain_andalucia', 'Andalucia');
        opt('spain_aragon', 'Aragon');
        opt('spain_asturias', 'Asturias');
        opt('spain_balearic_islands', 'Balearic Islands');
        opt('spain_canary_islands', 'Canary Islands');
        opt('spain_cantabria', 'Cantabria');
        opt('spain_castilla-la mancha', 'Castilla-La Mancha');
        opt('spain_castilla-leon', 'Castilla-Leon');
        opt('spain_catalonia', 'Cataluna (Catalunya)');
        opt('spain_extremadura', 'Extremadura');
        opt('spain_galicia', 'Galicia');
        opt('spain_madrid', 'Madrid');
        opt('spain_murcia', 'Murcia');
        opt('spain_navarra', 'Navarra');
        opt('spain_pais_vasco', 'Pais Vasco (Basque subcategory)');
        opt('spain_rioja', 'Rioja');
        opt('spain_valencia', 'Valencia');

        opt('spain', 'Spain - General');
    }
    if (subcategory == 'Sweden') {
        opt('', 'No further response required');
        quickload('sweden');
    }
    if (subcategory == 'Switzerland') {
        opt('', 'No further response required');
        quickload('switzerland');
    }
    if (subcategory == 'Turkey') {
        opt('', 'No further response required');
        quickload('turkey');
    }
    if (subcategory == 'Ukraine') {
        opt('', 'No further response required');
        quickload('ukraine');
    }
    if (subcategory == 'United Kingdom') {
        opt('Select a Region', 'Select a Region');
        opt('england_avon_and_bristol', 'England - Avon and Bristol');
        opt('england_berkshire', 'England - Berkshire');
        opt('england_bedfordshire', 'England - Bedfordshire');
        opt('england_buckinghamshire', 'England - Buckinghamshire');
        opt('england_cambridgeshire', 'England - Cambridgeshire');
        opt('england_cheshire', 'England - Cheshire');
        opt('england_cleveland', 'England - Cleveland');
        opt('england_cornwall', 'England - Cornwall & Scilly Isles');
        opt('england_cumbria', 'England - Cumbria');
        opt('england_derbyshire', 'England - Derbyshire');
        opt('england_devon', 'England - Devon');
        opt('england_dorset', 'England - Dorset');
        opt('england_durham', 'England - Durham');
        opt('england_essex', 'England - Essex');
        opt('england_gloucestershire', 'England - Gloucestershire');
        opt('england_greater_london', 'England - Greater London');
        opt('england_greater_manchester', 'England - Greater Manchester');
        opt('england_hampshire', 'England - Hampshire');
        opt('england_herefordshire', 'England - Herefordshire');
        opt('england_hertfordshire', 'England - Hertfordshire');
        opt('england_kent', 'England - Kent');
        opt('england_lancashire', 'England - Lancashire');
        opt('england_leicestershire', 'England - Leicestershire & Rutland');
        opt('england_lincolnshire', 'England - Lincolnshire');
        opt('england_norfolk', 'England - Norfolk');
        opt('england_northamptonshire', 'England - Northamptonshire');
        opt('england_northumberland', 'England - Northumberland');
        opt('england_nottinghamshire', 'England - Nottinghamshire');
        opt('england_oxfordshire', 'England - Oxfordshire');
        opt('england_shropshire', 'England - Shropshire');
        opt('england_somerset', 'England - Somerset');
        opt('england_staffordshire', 'England - Staffordshire');
        opt('england_suffolk', 'England - Suffolk');
        opt('england_surrey', 'England - Surrey');
        opt('england_sussex', 'England - Sussex');
        opt('england_warwickshire', 'England - Warwickshire');
        opt('england_west_midlands', 'England - West Midlands');
        opt('england_wiltshire', 'England - Wiltshire');
        opt('england_worcester', 'England - Worcester');
        opt('england_yorkshire', 'England - Yorkshire');
        opt('england_general', 'England - General');
        opt('ulster_antrim', 'Northern Ireland - County Antrim');
        opt('ulster_armagh', 'Northern Ireland - County Armagh');
        opt('ulster_down', 'Northern Ireland - County Down');
        opt('ulster_fermanagh', 'Northern Ireland - County Fermanagh');
        opt('ulster_londonderry', 'Northern Ireland - County Londonderry');
        opt('ulster_tyrone', 'Northern Ireland - County Tyrone');
        opt('ulster_general', 'Northern Ireland - General ');
        opt('scotland_aberdeenshire', 'Scotland - Aberdeenshire');
        opt('scotland_argyll_bute', 'Scotland - Argyll and Bute ');
        opt('scotland_ayrshire', 'Scotland - Ayrshire');
        opt('scotland_angus', 'Scotland - Angus and Dundee');
        opt('scotland_borders', 'Scotland - Borders');
        opt('scotland_central', 'Scotland - Central');
        opt('scotland_central_strathclyde', 'Scotland - Central Strathclyde');
        opt('scotland_dumfries_and_galloway', 'Scotland - Dumfries and Galloway');
        opt('scotland_fife', 'Scotland - Fife');
        opt('scotland_highlands_and_islands', 'Scotland - Highlands and Islands');
        opt('scotland_lothian', 'Scotland - Lothian');
        opt('scotland_moray', 'Scotland - Moray and Nairn');
        opt('scotland_orkney', 'Scotland - Orkney');
        opt('scotland_perth_kinross', 'Scotland - Perth and Kinross');
        opt('scotland_shetland', 'Scotland - Shetland');
        opt('scotland_western_isles', 'Scotland - Western Isles');
        opt('scotland_general', 'Scotland - General');
        opt('wales_clywd', 'Wales - Clywd');
        opt('wales_dyfed', 'Wales - Dyfed');
        opt('wales_glamorgan', 'Wales - Glamorgan');
        opt('wales_gwent', 'Wales - Gwent');
        opt('wales_gwyndd', 'Wales - Gwyndd');
        opt('wales_powys', 'Wales - Powys');
        opt('wales_general', 'Wales - General');
        opt('united_kingdom_channel_islands', 'United Kingdom - Channel Islands');
        opt('united_kingdom_isle_of_man', 'United Kingdom - Isle of Man');
        opt('united_kingdom_isle_of_wight', 'United Kingdom - Isle of Wight');
        opt('united_kingdom_scilly_isles', 'United Kingdom - Scilly Isles');
        opt('united_kingdom_general', 'United Kingdom - General ');
    }
    if (subcategory == 'Europe - General') {
        opt('', 'No further response required');
        quickload('europe_general');
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Europe ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "europe";

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
                    $sub_category_cnt = 1;

                    //add item the spare item to the array at the start
                    $sub_category[0] = "kosovo";

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