<?php
//set_time_limit(2000);
include("../common/funct_lib.php");
connect_db();
?>
<html>
<head>
    <title>Echo all cats / subcats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
    <?

    $mycnt = 0;


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

    $sql = "SELECT DISTINCT `category`,`sub-category` FROM `pagename` WHERE `page_id` IN (" . $ids . ") GROUP BY `category`,`sub-category` ORDER BY `sub-category` ASC";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
        if (!(array_key_exists($row["category"], $allcatsandsubcats))) {
            $allcatsandsubcats[$row["category"]] = array();
        }

        if (!(in_array($row["sub-category"], $allcatsandsubcats[$row["category"]]))) {
            array_push($allcatsandsubcats[$row["category"]], $row["sub-category"]);
        }
    }

//    for ($i = 1; $i <= 22; $i++) {
//
//        $sql = "SELECT DISTINCT `category`,`sub-category` FROM " . $tables[$i] . " WHERE 1 GROUP BY `category`,`sub-category` ORDER BY `category`,`sub-category` ASC";
//        //echo $sql;
//        $result = mysql_query($sql);
//        while ($row = mysql_fetch_assoc($result)) {
//
//            //echo $row["sub-category"];
//
//            if (!(array_key_exists($row["category"], $allcatsandsubcats))) {
//                $allcatsandsubcats[$row["category"]] = array();
//            }
//
//            if (!(in_array($row["sub-category"], $allcatsandsubcats[$row["category"]]))) {
//                array_push($allcatsandsubcats[$row["category"]], $row["sub-category"]);
//            }
//
//        }
//
//        //echo $sub_category[$sub_category_cnt];
//
//    }

    // we should now have an array of every unique category and within each category a unique list of subcats.
    // thus guaranting we have every combination to make all pages!

    // now with any luck the easy bit - itterate over the little buggers!

    reset($allcatsandsubcats);
    foreach ($allcatsandsubcats as $category => $subcategory_arr) {
        reset($subcategory_arr);
        foreach ($subcategory_arr as $subcategory) {
            echo $category . " " . "('" . $subcategory . "',)," . "<br />";
        }
    }

    ?>
    </body>
</html>
