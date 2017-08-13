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
        parent.home.location.href = 'pick_record_geo.php?category=asia&subcategory=' + object.options[object.selectedIndex].value + '&random=1115129184' + rand(255);
        return false;
    }
}

function quickload(place) {
    parent.home.location.href = 'pick_record_geo.php?category=asia&subcategory=' + place + '&random=1115129184' + rand(255);
}

function showlinks() {
    if (subcategory == 'Afghanistan') {
        opt('', 'No further response required');
        quickload('afghanistan');
    }
    if (subcategory == 'Bangladesh') {
        opt('', 'No further response required');
        quickload('bangladesh');
    }
    if (subcategory == 'Bhutan') {
        opt('', 'No further response required');
        quickload('bhutan');
    }
    if (subcategory == 'Brunei') {
        opt('', 'No further response required');
        quickload('brunei');
    }
    if (subcategory == 'Cambodia') {
        opt('', 'No further response required');
        quickload('cambodia');
    }
    if (subcategory == 'China') {
        opt('', 'No further response required');
        quickload('china');
    }
    if (subcategory == 'Hong Kong') {
        opt('', 'No further response required');
        quickload('hong_kong');
    }
    if (subcategory == 'India') {
        opt('Select a Region', 'Select a Region');
        opt('india_andaman_nicobar', 'Andaman & Nicobar');
        opt('india_andhra_pradesh', 'Andhra Pradesh');
        opt('india_arunachal_pradesh', 'Arunachal Pradesh');
        opt('india_assam', 'Assam');
        opt('india_bihar', 'Bihar');
        opt('india_chhattisgarh', 'Chhattisgarh');
        opt('india_delhi', 'Delhi');
        opt('india_goa', 'Goa');
        opt('india_gujarat', 'Gujarat');
        opt('india_haryana', 'Haryana');
        opt('india_himachal_pradesh', 'Himachal Pradesh');
        opt('india_jammu_kashmir', 'Jammu & Kashmir');
        opt('india_jharkhand', 'Jharkhand');
        opt('india_karnataka', 'Karnataka');
        opt('india_kerala', 'Kerala');
        opt('india_lakshadweep', 'Lakshadweep');
        opt('india_madhya_pradesh', 'Madhya Pradesh');
        opt('india_maharashtra', 'Maharashtra');
        opt('india_manipur', 'Manipur');
        opt('india_meghalaya', 'Meghalaya');
        opt('india_mizoram', 'Mizoram');
        opt('india_nagaland', 'Nagaland');
        opt('india_orissa', 'Orissa');
        opt('india_punjab', 'Punjab');
        opt('india_rajasthan', 'Rajasthan');
        opt('india_sikkim', 'Sikkim');
        opt('india_tamil_nadu', 'Tamil Nadu');
        opt('india_tripura', 'Tripura');
        opt('india_uttar_pradesh', 'Uttar Pradesh');
        opt('india_uttaranchal', 'Uttaranchal');
        opt('india_west_bengal', 'West Bengal');
        opt('india', 'India - General');
    }
    if (subcategory == 'Indonesia') {
        opt('Select a Region', 'Select a Region');
        opt('indonesia_sumatera', 'Sumatra');
        opt('indonesia_java', 'Java');
        opt('indonesia_bali', 'Bali');
        opt('indonesia_nusa_tenggara_barat', 'Nusa Tenggara Barat');
        opt('indonesia_kalimantan_selatan', 'Kalimantan Selatan');
        opt('indonesia_kalimantan_barat', 'Kalimantan Barat');
        opt('indonesia_kalimantan_tengah', 'Kalimantan Tengah');
        opt('indonesia_kalimantan_timur', 'Kalimantan Timur');
        opt('indonesia_maluku', 'Maluku');
        opt('indonesia_sulawesi', 'Sulawesi');
        opt('indonesia_irian', 'Irian');
        opt('indonesia', 'Indonesia General');
    }
    if (subcategory == 'Japan') {
        opt('', 'No further response required');
        quickload('japan');
    }
    if (subcategory == 'Kazakhstan') {
        opt('', 'No further response required');
        quickload('kazakhstan');
    }
    if (subcategory == 'Kyrgyzstan') {
        opt('', 'No further response required');
        quickload('kyrgyzstan');
    }
    if (subcategory == 'Laos') {
        opt('', 'No further response required');
        quickload('laos');
    }
    if (subcategory == 'Malaysia') {
        opt('Select a Region', 'Select a Region');
        opt('malaysia_johor', 'Johor');
        opt('malaysia_kedah', 'Kedah');
        opt('malaysia_kelantan', 'Kelantan');
        opt('malaysia_kuala_lumpur', 'Kuala Lumpur');
        opt('malaysia_melaka', 'Melaka');
        opt('malaysia_negeri_sembilan', 'Negeri Sembilan');
        opt('malaysia_pahang', 'Pahang');
        opt('malaysia_penang', 'Penang');
        opt('malaysia_perak', 'Perak');
        opt('malaysia_perlis', 'Perlis');
        opt('malaysia_sabah', 'Sabah');
        opt('malaysia_sarawak', 'Sarawak');
        opt('malaysia_selangor', 'Selangor');
        opt('malaysia_terengganu', 'Terengganu');
        opt('malaysia', 'Malaysia - General');
    }
    if (subcategory == 'Maldives') {
        opt('', 'No further response required');
        quickload('maldives');
    }
    if (subcategory == 'Marianas Islands') {
        opt('', 'No further response required');
        quickload('marianas_islands');
    }
    if (subcategory == 'Mongolia') {
        opt('', 'No further response required');
        quickload('mongolia');
    }
    if (subcategory == 'Myanmar') {
        opt('', 'No further response required');
        quickload('myanmar');
    }
    if (subcategory == 'Nepal') {
        opt('', 'No further response required');
        quickload('nepal');
    }
    if (subcategory == 'North Korea') {
        opt('', 'No further response required');
        quickload('north_korea');
    }
    if (subcategory == 'Pakistan') {
        opt('', 'No further response required');
        quickload('pakistan');
    }
    if (subcategory == 'Phillipines') {
        opt('', 'No further response required');
        quickload('phillipines');
    }
    if (subcategory == 'Singapore') {
        opt('', 'No further response required');
        quickload('singapore');
    }
    if (subcategory == 'South Korea') {
        opt('', 'No further response required');
        quickload('south_korea');
    }
    if (subcategory == 'Sri Lanka') {
        opt('', 'No further response required');
        quickload('sri_lanka');
    }
    if (subcategory == 'Taiwan') {
        opt('', 'No further response required');
        quickload('taiwan');
    }
    if (subcategory == 'Tajikistan') {
        opt('', 'No further response required');
        quickload('tajikistan');
    }
    if (subcategory == 'Thailand') {
        opt('', 'No further response required');
        quickload('thailand');
    }
    if (subcategory == 'Tibet') {
        opt('', 'No further response required');
        quickload('tibet');
    }
    if (subcategory == 'Timor Leste') {
        opt('', 'No further response required');
        quickload('timor_leste');
    }
    if (subcategory == 'Turkmenistan') {
        opt('', 'No further response required');
        quickload('turkmenistan');
    }
    if (subcategory == 'Uzbekistan') {
        opt('', 'No further response required');
        quickload('uzbekistan');
    }
    if (subcategory == 'Vietnam') {
        opt('', 'No further response required');
        quickload('vietnam');
    }
    if (subcategory == 'General Asia') {
        opt('', 'No further response required');
        quickload('asia');
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Asia ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "asia";

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

                    for ($i = 1; $i <= 22; $i++) {

                        $sql = "SELECT DISTINCT `sub-category` FROM " . $tables[$i] . " WHERE `category` = '" . $category . "' ORDER BY `sub-category` ASC";
                        //echo $sql;
                        $result = mysql_query($sql);
                        while ($row = mysql_fetch_assoc($result)) {

                            //echo $row["sub-category"];

                            if (!(in_array($row["sub-category"], $sub_category))) {
                                $sub_category[$sub_category_cnt] = $row["sub-category"];
                                //echo "<option value=\"$sub_category[$sub_category_cnt]\">".alt_name($sub_category[$sub_category_cnt])."</option>";
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