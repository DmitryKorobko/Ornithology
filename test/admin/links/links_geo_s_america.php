<?php
include("../common/funct_lib.php");
connect_db();

//alternative name for drop list fucntion

function alt_name($db_name)
{
    switch ($db_name) {
        case"s_america":
            $db_name = "General South America";
            break;
        case"argentina":
            $db_name = "Argentina - General";
            break;
        case"brazil":
            $db_name = "Brazil - General";
            break;
        case"equador":
            $db_name = "Equador - General";
            break;
        case"equador":
            $db_name = "Equador - General";
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
                parent.home.location.href = 'pick_record_geo.php?category=s_america&subcategory=' + object.options[object.selectedIndex].value + '&random=1115137384' + rand(255);
                return false;
            }
        }

        function quickload(place) {
            parent.home.location.href = 'pick_record_geo.php?category=s_america&subcategory=' + place + '&random=1115137384' + rand(255);
        }

        function showlinks() {
            if (subcategory == 'Argentina') {
                opt('argentina_buenos_aires', 'Buenos Aires');
                opt('argentina_buenos_aires_city', 'Buenos Aires City');
                opt('argentina_catamarca', 'Catamarca');
                opt('argentina_chaco', 'Chaco');
                opt('argentina_chubut', 'Chubut');
                opt('argentina_cordoba', 'Cordoba');
                opt('argentina_corrientes', 'Corrientes');
                opt('argentina_entre_rios', 'Entre Rios');
                opt('argentina_formosa', 'Formosa');
                opt('argentina_jujuy', 'Jujuy');
                opt('argentina_la_pampa', 'La Pampa');
                opt('argentina_la_rioja', 'La Rioja');
                opt('argentina_mendoza', 'Mendoza');
                opt('argentina_misiones', 'Misiones');
                opt('argentina_neuquen', 'Neuquen');
                opt('argentina_patagonia', 'Patagonia');
                opt('argentina_rio_negro', 'Rio Negro');
                opt('argentina_salta', 'Salta');
                opt('argentina_san_juan', 'San Juan');
                opt('argentina_san_luis', 'San Luis');
                opt('argentina_santa_cruz', 'Santa Cruz');
                opt('argentina_santa_fe', 'Santa Fe');
                opt('argentina_santiago_del_estero', 'Santiago del Estero');
                opt('argentina_tierra_del_fuego', 'Tierra del Fuego');
                opt('argentina_tucumain', 'Tucumain');
                opt('argentina', 'Argentina - General');
            }
            if (subcategory == 'Bolivia') {
                opt('', 'No further response required');
                quickload('bolivia');
            }
            if (subcategory == 'Brazil') {
                opt('Select a Region', 'Select a Region');
                opt('acre', 'North - Acre');
                opt('amapa', 'North - Amap�');
                opt('amazonas', 'North - Amazonas');
                opt('para', 'North - Par�');
                opt('rondonia', 'North - Rond�nia');
                opt('roraima', 'North - Roraima');
                opt('tocantins', 'North - Tocantins');
                opt('alagoas', 'North East - Alagoas');
                opt('bahia', 'North East - Bahia');
                opt('ceara', 'North East - Cear�');
                opt('maranhao', 'North East - Maranh�o');
                opt('paraiba', 'North East - Para�ba');
                opt('pernambuco', 'North East - Pernambuco');
                opt('piaui', 'North East - Piau�');
                opt('rio_grande_do_norte', 'North East - Rio Grande do Norte');
                opt('sergipe', 'North East - Sergipe');
                opt('federal_district_inc_brasilia', 'Central West - Federal district including Bras�lia');
                opt('goias', 'Central West - Goi�s');
                opt('mato_grosso', 'Central West - Mato Grosso');
                opt('mato_grosso_do_sul', 'Central West - Mato Grosso do Sul');
                opt('parana', 'South - Paran�');
                opt('rio_grande_do_sul', 'South - Rio Grande do Sul');
                opt('santa_catarina', 'South - Santa Catarina');
                opt('espirito_santo', 'South East - Esp�rito Santo');
                opt('minas_gerais', 'South East - Minas Gerais');
                opt('rio_de_janeiro', 'South East - Rio de Janeiro');
                opt('sao_paulo', 'South East - S�o Paulo');
                opt('brazil', 'Brazil - General');
            }
            if (subcategory == 'Chile') {
                opt('', 'No further response required');
                quickload('chile');
            }
            if (subcategory == 'Columbia') {
                opt('', 'No further response required');
                quickload('columbia');
            }
            if (subcategory == 'Easter Island') {
                opt('', 'No further response required');
                quickload('chile_easter_island');
            }
            if (subcategory == 'Equador') {
                opt('', 'No further response required');
                opt('equador_galapagos', 'Galapagos Islands');
                opt('equador', 'Equador - General');
            }
            if (subcategory == 'Falkland Islands') {
                opt('', 'No further response required');
                quickload('falkland_islands');
            }
            if (subcategory == 'French Guiana') {
                opt('', 'No further response required');
                quickload('french_guiana');
            }
            if (subcategory == 'Guayana') {
                opt('', 'No further response required');
                quickload('guayana');
            }
            if (subcategory == 'Paraguay') {
                opt('', 'No further response required');
                quickload('paraguay');
            }
            if (subcategory == 'Peru') {
                opt('', 'No further response required');
                quickload('peru');
            }
            if (subcategory == 'Suriname') {
                opt('', 'No further response required');
                quickload('suriname');
            }
            if (subcategory == 'Uraguay') {
                opt('', 'No further response required');
                quickload('uraguay');
            }
            if (subcategory == 'Venezuela') {
                opt('', 'No further response required');
                quickload('venezuela');
            }
            if (subcategory == 'General South America') {
                opt('', 'No further response required');
                quickload('s_america');
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;South America ></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a subcategory</option>
                    <?php

                    $category = "s_america";

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