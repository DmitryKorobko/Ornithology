<?php
//set_time_limit(2000);
include("../common/funct_lib.php");
connect_db();
?>
<html>
<head>
    <title>Update All Pages Part 1</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <style type="text/css">
        <!--
        p {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
        }

        td {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #000000;
        }

        th {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            text-align: left;
            font-weight: normal
        }

        .title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #999999;
        }

        a:hover {
            color: #FF0000;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        a:link {
            color: #0000FF;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        a:visited {
            color: #0000FF;
            text-decoration: none;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        .major_title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
        }

        textarea {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        input {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        select {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            border-color: #EEEEEE #EEEEEE #EEEEEE #EEEEEE;
            border-width: thin thin thin thin;
            border: thin #EEEEEE solid
        }

        -->
    </style>
<p class="major_title">Updating Pages Part 1 (Please wait this will take a few minutes)....</p>
<?

$mycnt = 0;

/* comment out all the old update code!

//update about us page
$category = "default_about";
$subcategory = "default_about";
update_all_pages ($category, $subcategory);

$category = "petbirds";
$subcategory = "petbirds";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "timor_leste";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "india_delhi";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "malaysia_kelantan";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "malaysia_melaka";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "malaysia_melaka";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "malaysia_perlis";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "malaysia_terengganu";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_irian";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_kalimantan_barat";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_kalimantan_selatan";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_kalimantan_tengah";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_kalimantan_timur";
update_all_pages ($category, $subcategory);

$category = "asia";
$subcategory = "indonesia_sumatera";
update_all_pages ($category, $subcategory);


$category = "s_america";
$subcategory = "argentina_buenos_aires";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_buenos_aires_city";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_catamarca";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_cordoba";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_entre_rios";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_formosa";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_jujuy";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_la_pampa";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_la_rioja";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_mendoza";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_neuquen";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_rio_negro";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_salta";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_san_juan";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_san_luis";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_santa_cruz";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_santa_fe";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_tierra_del_fuego";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "argentina_tucumain";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "paraiba";
update_all_pages ($category, $subcategory);

$category = "s_america";
$subcategory = "sergipe";
update_all_pages ($category, $subcategory);

$category = "africa";
$subcategory = "south_africa_free_state";
update_all_pages ($category, $subcategory);

$category = "africa";
$subcategory = "south_africa_northern_province";
update_all_pages ($category, $subcategory);

$category = "africa";
$subcategory = "south_africa_north-west_province";
update_all_pages ($category, $subcategory);

$category = "africa";
$subcategory = "south_africa_free_state";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "kosovo";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "spain_cantabria";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "spain_castilla-leon";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "spain_pais_vasco";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "spain_rioja";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_aquitaine";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_bourgogne";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_bretagne";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_centre";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_champagne-ardenne";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_corsica";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_franche_comte";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_languedoc_roussillon";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_limousin";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_lorraine";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_midi_pyrenees";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_pays_de_la_loire";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_picardie";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_poitou_charentes";
update_all_pages ($category, $subcategory);

$category = "europe";
$subcategory = "france_provence_alpes_cote_dazur";
update_all_pages ($category, $subcategory);


$category = "n_america";
$subcategory = "yukon";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_chiapas";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_chihuahua";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_colima";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_durango";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_guerrero";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_hidalgo";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_mexico";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_morelos";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_oaxaca";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_san_luis_potosi";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_sinaloa";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_tabasco";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_tamaulipas";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_tlaxcala";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_zacatecas";
update_all_pages ($category, $subcategory);

$category = "n_america";
$subcategory = "mexico_districto_federal";
update_all_pages ($category, $subcategory);

$category = "ornithology";
$subcategory = "Cracidae";
update_all_pages ($category, $subcategory);

$category = "ornithology";
$subcategory = "Formicariidae";
update_all_pages ($category, $subcategory);

$category = "ornithology";
$subcategory = "Megapodiidae";
update_all_pages ($category, $subcategory);

$category = "ornithology";
$subcategory = "Mesitornithidae";
update_all_pages ($category, $subcategory);

$category = "ornithology";
$subcategory = "Solitaire";
update_all_pages ($category, $subcategory);




//get a list of all categorys and subcategorys which will include all links pages
$sqlsel="SELECT DISTINCT `category`, `sub-category` FROM `links` ORDER BY `category`, `sub-category`";
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
	//update all links pages
	$category = $row["category"];
	$subcategory = $row["sub-category"];

	update_all_pages ($category, $subcategory);

}

/*

// this bit is commented out and done in part2

//get a list of all categorys and subcategorys which will include all geo links pages
$sqlsel="SELECT DISTINCT `category`, `sub-category` FROM `links_useful_information` ORDER BY `category`, `sub-category`";
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
	//update all links pages
	$category = $row["category"];
	$subcategory = $row["sub-category"];

	update_all_pages ($category, $subcategory);

}

//get a list of all categorys and subcategorys which will include all geo links pages
$sqlsel="SELECT DISTINCT `category`, `sub-category` FROM `links_reserves` ORDER BY `category`, `sub-category`";
$result = mysql_query($sqlsel);
while ($row = mysql_fetch_assoc($result))
{
	//update all links pages
	$category = $row["category"];
	$subcategory = $row["sub-category"];

	update_all_pages ($category, $subcategory);

}
*/
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

// first get a list of all the different categories and subcategories from all the tables.

$allcatsandsubcats = array();

for ($i = 1; $i <= 22; $i++) {

    $sql = "SELECT DISTINCT `category`,`sub-category` FROM " . $tables[$i] . " WHERE 1 GROUP BY `category`,`sub-category` ORDER BY `category`,`sub-category` ASC";
    //echo $sql;
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {

        //echo $row["sub-category"];

        if (!(array_key_exists($row["category"], $allcatsandsubcats))) {
            $allcatsandsubcats[$row["category"]] = array();
        }

        if (!(in_array($row["sub-category"], $allcatsandsubcats[$row["category"]]))) {
            array_push($allcatsandsubcats[$row["category"]], $row["sub-category"]);
        }

    }

    //echo $sub_category[$sub_category_cnt];

}

// we should now have an array of every unique category and within each category a unique list of subcats.
// thus guaranting we have every combination to make all pages!

// now with any luck the easy bit - itterate over the little buggers!

reset($allcatsandsubcats);
foreach ($allcatsandsubcats as $category => $subcategory_arr) {
    reset($subcategory_arr);
    foreach ($subcategory_arr as $subcategory) {
        update_all_pages($category, $subcategory);
    }
}

echo "<br><br>Finished!";

function update_all_pages($category, $subcategory)
{

//create new HTML file based on database for the given section
    $tmpl_dir = "";
    if ($category == "default_about") {
        $tmpl_name = "template_about.htm";
    } else {
        if ($category == "n_america") {
            $tmpl_name = "template_n_america.htm";
        } else {
            $tmpl_name = "template.htm";
        }
    }


    //create the correct output dir and html file name based on subcategory and category
    list ($alt_dir, $alt_file) = makeALTDIR($category, $subcategory);
    $html_dir = dir_const . $alt_dir;
    $html_name = $alt_file;


    //set the root folder to chmod 777
    $chmodf = chmod_file . $alt_dir;
    ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0777");

    global $mycnt;

    $mycnt += 1;

    echo "<br>$mycnt ) " . date("H:i", time()) . " - " . $html_dir;
    echo $html_name;
    $sql_cat = $category;
    $sql_subcat = $subcategory;
    global $alt_dir;
    global $alt_file;
    createHTML($tmpl_dir, $tmpl_name, $html_dir, $html_name, $sql_cat, $sql_subcat);

    //set the root folder to chmod 755
    ftp_change_mod(chmod_ip, chmod_login, chmod_pass, $chmodf, "0755");

}

?>
</body>
</html>
