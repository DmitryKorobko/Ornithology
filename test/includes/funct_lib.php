<?php
/*define ("ROOT_DIR", $_SERVER['DOCUMENT_ROOT'] . "/");
define ("SITE_ROOT", "/");
define ("ADIMGDIR", "adimages/");
define ("BANNERIMGDIR", "adimages/banners/");
define ("MAP_DIR", "images/maps/");
define ("TEMPLATE_DIR", "includes/");
define ("BOX_SCROLL_LIMIT", 5);
define ("LONG_DATE", "jS F Y");
define ("SHORT_DATE", "d/m/y");
define ("ANNOUNCEMENTS_ICON_DIR", "images/icons/announcements/");
define ("NEWS_ICON_DIR", "images/icons/news/");
define ("REVIEWS_ICON_DIR", "images/icons/reviews/");
include(ROOT_DIR . "admin/firelogger.php");
include(ROOT_DIR . 'includes/class.upload.php');
include(ROOT_DIR . 'includes/phpmailer/PHPMailerAutoload.php');
include(ROOT_DIR . 'includes/lib_autolink.php');
include(ROOT_DIR . "admin/storydisplay.php");
include(ROOT_DIR . "admin/intro.php");*/
// db settings

/*remote*/









// table field lists
define ("NEWS_TEXT_FIELDS", "(article, paragraph, text, type)");
define ("NEWS_MAIN_FIELDS", "(title, summary, desc_1, desc_2, desc_3, created)");
define ("NEWS_STATUS_FIELDS", "(article, state)");
define ("REVIEW_TEXT_FIELDS", "(article, paragraph, text, type)");
define ("REVIEW_MAIN_FIELDS", "(title, summary, desc_1, desc_2, desc_3, rating, created)");
define ("REVIEW_STATUS_FIELDS", "(article, state, section)");
define ("ANNOUNCEMENT_TEXT_FIELDS", "(article, paragraph, text, type)");
define ("ANNOUNCEMENT_MAIN_FIELDS", "(title, summary, desc_1, desc_2, desc_3, created)");
define ("ANNOUNCEMENT_STATUS_FIELDS", "(article, state)");
define ("LINKS_INTRO_FIELDS", "(`paragraph`, `text`, `sub-category`, `category`)");
define ("LINKS_CONTRIBUTOR_FIELDS", "(`name`, `title`, `location`, `email`, `url1`, `url1_title`, `sub-category`, `category`)");
define ("LINKS_RECORDER_FIELDS", "(`name`, `address`, `telephone`, `fax`, `email`, `sub-category`, `category` )");
define ("LINKS_SPECIES_FIELDS", "(`title`, `text`, `sub-category`, `category`)");
define ("LINKS_LINKS_FIELDS", "(`title`, `url1`, `url1_title`, `link_desc`, `sub-category`, `category`)");
define ("LINKS_LINKS2_FIELDS", "(`title`, `url1`, `url1_title`, `url2`, `url2_title`, `text`, `sub-category`, `category`)");
define ("LINKS_MAILING_FIELDS", "(`title`, `url1`, `url1_title`, `post_email`, `contact`, `sub_email`, `unsub_email`, `sub_message`, `body_message`, `text`, `sub-category`, `category`)");
define ("LINKS_PHOTO_FIELDS", "(`country`, `filename`, `width`, `height`, `text`)");
define ("LINKS_TOPSITES_FIELDS", "(`title`, `grid_reference`, `text`, `sub-category`, `category`)");
define ("LINKS_USEFUL_FIELDS", "(`title`, `text`, `isbn`, `sub-category`, `category`)");
define ("LINKS_USEFULINFO_FIELDS", "(`title`, `text`, `sub-category`, `category`)");
define ("LINKS_MAP_FIELDS", "(`imagemap`, `regionlist`, `sub-category`, `category`)");
define ("LINKS_BANNERS_FIELDS", "(`filename`, `url1`, `url1_title`, `sponsor`, `text`, `width`, `height`, `sub-category`, `category`)");
define ("LINKS_SPONSOR_FIELDS", "(`title`, `url1`, `url1_title`, `link_desc`, `filename`, `section`)");
define ("SUBLINKS", "(`sublinks`, `sub-category`, `category`)");


class ConvertInput
{

    public function encode($string)
    {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
    }

    public function decode($string)
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
    }

    public function convertForInput($string)
    { //use before encode() on new input from a form
        $string = str_ireplace("<i>", "<em>", $string);
        $string = str_ireplace("</i>", "</em>", $string);
        $string = str_ireplace("<b>", "<strong>", $string);
        $string = str_ireplace("</b>", "</strong>", $string);
        $string = str_ireplace("<br>", "<br />", $string);
        $string = str_ireplace("...", "&hellip;", $string);
        $string = str_ireplace("&NewLine;", '</p><p>', $string);
        $string = str_ireplace(chr(10), '</p><p>', $string);
        $string = str_ireplace(chr(13), '', $string);
        $string = str_ireplace("</p><p></p><p>", "</p><p>", $string);
        /*$string = str_ireplace(chr(92), "&rsquo;", $string);
        //$string = str_ireplace(chr(128), "&euro;", $string);Trashes everything
        $string = str_ireplace(chr(133), "&hellip;", $string);
        $string = str_ireplace(chr(145), "&lsquo;", $string);
        $string = str_ireplace(chr(146), "&rsquo;", $string);
        $string = str_ireplace(chr(147), "&ldquo;", $string);
        $string = str_ireplace(chr(148), "&rdquo;", $string);
        $string = str_ireplace(chr(150), "&ndash;", $string);
        $string = str_ireplace(chr(151), "&mdash;", $string);*/
        $string = decode($string);
        return $string;
    }

    public function convertDateToTimestamp($newdate)
    {
        $date = DateTime::createFromFormat('j/n/y', $newdate)->getTimestamp();
        return $date;
    }

    public function convertTimestampToDate($format, $newdate)
    {
        if ($format == "short") {
            return date(SHORT_DATE, $newdate);
        }
        if ($format == "long") {
            return date(LONG_DATE, $newdate);
        }
    }

    protected function prepareUpload($width, $height)
    {
        $imagewidth = $width;
        $imageheight = $height;
        $handle = new upload($_FILES['newimage']);
        if ($handle->uploaded) {
            $name = strip_tags($handle->file_src_name_body);
            $handle->image_resize = true;
            $handle->image_x = $imagewidth;
            $handle->image_y = $imageheight;
            $handle->image_ratio = true;
            $handle->file_name_body_add = time();
            $handle->process(ROOT_DIR . $this->imgdir);
            if ($handle->processed) {
                $newname = $handle->file_dst_name;
                $handle->clean();
                return $newname;
            } else {
                echo 'error : ' . $handle->error;
            }
        }
    }

}

function encode($string)
{
    return htmlentities($string, ENT_HTML5 | ENT_QUOTES, "UTF-8", false);
}

function decode($string)
{
    return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
}

function convertForInput($string)
{ //use before encode() on new input from a form
    $string = str_ireplace("<i>", "<em>", $string);
    $string = str_ireplace("</i>", "</em>", $string);
    $string = str_ireplace("<b>", "<strong>", $string);
    $string = str_ireplace("</b>", "</strong>", $string);
    $string = str_ireplace("<br>", "<br />", $string);
    $string = str_ireplace("...", "&hellip;", $string);
    $string = str_ireplace("&NewLine;", '</p><p>', $string);
    $string = str_ireplace(chr(10), '</p><p>', $string);
    $string = str_ireplace(chr(13), '', $string);
    $string = str_ireplace("</p><p></p><p>", "</p><p>", $string);
    /*$string = str_ireplace(chr(92), "&rsquo;", $string);
    //$string = str_ireplace(chr(128), "&euro;", $string);Trashes everything
    $string = str_ireplace(chr(133), "&hellip;", $string);
    $string = str_ireplace(chr(145), "&lsquo;", $string);
    $string = str_ireplace(chr(146), "&rsquo;", $string);
    $string = str_ireplace(chr(147), "&ldquo;", $string);
    $string = str_ireplace(chr(148), "&rdquo;", $string);
    $string = str_ireplace(chr(150), "&ndash;", $string);
    $string = str_ireplace(chr(151), "&mdash;", $string);*/
    $string = decode($string);
    return $string;
}

function session_authenticated()
{
    session_start();
    if (session_is_registered("user_auth") && ($_SESSION ['user_auth'] == "user")) {
        return true;
    } else {
        return false;
    }
}

function connect_db()
{
    if (!mysql_connect(DB_HOST, USER, PASS)) {
        echo "<p>Unable to connect to MySQL server: <strong><em>" . DB_HOST . "</em></strong></p>";
        exit;
    }
    if (!mysql_select_db(DB)) {
        echo "<p>Unable to find database <strong><em>" . DB . "</em></strong> on host <strong><em>" . DB_HOST . "</em></strong></p>";
        echo "<p>" . mysql_error() . "</p>";

        exit;
    }
}

function createHTML($tmpl_name, $category, $subcategory, $article_id = "", $message = "")
{
    //perform quick variable validation loops
    if ($tmpl_name == "") {
        $ConfMsg = "No template name was provided";
        return $ConfMsg;
    } else {
        $text = "";
        $template_name = $tmpl_name;
        $template_path = INCLUDES . $template_name;
        //open the template file and place a handle on it
        $template_handle = @fopen($template_path, "r");
        //echo stream_get_contents($template_handle);
        if (!$template_handle) {
            print "failed to open template<br> ";
            $ConfMsg = "<em>$template_path</em> not found on server";
        } else {
            while (!feof($template_handle)) {
                $buffer = fgets($template_handle, 4096);
                $text .= $buffer;
            }
            /*********************************************
             ********* DEAL WITH TEMPLATE TOKENS *********/
            $output_string = "";
            $tok = strtok($text, "[]");
            //$sql = $tmpl_sql;
            while ($tok !== false) {
                //then perform an action based on the flag within the tokened area
                switch ($tok) {
                    case "FACEBOOK_SHARE_BUTTON":
                        $output_string .= getFacebookShareButtonCode();
                        break;
                    case "TITLE":
                        $output_string .= gettitle($subcategory, $category);
                        break;
                    case "COPYRIGHT":
                        $output_string .= "&#169;Fatbirder 1997 - " . date("Y");
                        break;
                    case "ADSLEFT":
                        $ads = new Adverts("left");
                        $output_string .= $ads->displayAds();
                        break;
                    case "ADSRIGHT":
                        $ads = new Adverts("right");
                        $output_string .= $ads->displayAds();
                        break;
                    case "NAVIGATION":
                        $target = ROOT_DIR . "includes/menu.inc.php";
                        $output_string .= file_get_contents($target);
                        break;
                    case "FEEDBACK":
                        if ($category == "feedback" || $subcategory == "feedback") {
                            $target = ROOT_DIR . "includes/feedback.inc.php";
                            $output_string .= file_get_contents($target);
                            if ($message != "") {
                                $output_string .= '<p class="feedback_message">' . $message . '</p>';
                            }
                        }
                        break;
                    case "SEARCH":
                        $target = ROOT_DIR . "includes/searchbox.inc.php";
                        $output_string .= file_get_contents($target);
                        break;
                    case "PHOTO":
                        $table = "photos";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                    case "INTRO":
                        //echo $tok;
                        $table = "links_introduction";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                   /* case "INTRO2":
                        //echo $tok;
                        $table = "intro2";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;*/
                    case "FAMILYMENU":
                        //echo $tok;
                        $table = "familymenu";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                   /* case "SPECIESLIST":
                        //echo $tok;
                        $table = "specieslist";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;*/
                    case "FAMILY_LINKS":
                        //echo $tok;
                        $table = "links_family_links";
                        $output_string .= getcontent($table, $category, $subcategory, "Family Links");
                        if ($output_string <> "") {
                            $familylinkscheck = 1;
                        }
                        break;
                    case "SPECIES_LINKS":
                        $table = "links_species_links";
                        $output_string .= getcontent($table, $category, $subcategory, "Species Links");
                        break;
                    case "BANNER":
                        $table = "banner";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                    case "TOPSITES":
                        $table = "links_top_sites";
                        $output_string .= getcontent($table, $category, $subcategory, "Top Sites");
                        if ($output_string <> "") {
                            $topsitescheck = 1;
                        }
                        break;
                    case "CONTRIBUTOR":
                        $table = "links_contributor";
                        $output_string .= getcontent($table, $category, $subcategory, "Contributor");
                        if ($output_string <> "") {
                            $contributorcheck = 1;
                        }
                        break;
                    case "RECORDER":
                        $table = "links_county_recorder";
                        $output_string .= getcontent($table, $category, $subcategory, "County Recorder");
                        if ($output_string <> "") {
                            $recordercheck = 1;
                        }
                        break;
                    case "SPECIES":
                        $table = "links_number_species";
                        $output_string .= getcontent($table, $category, $subcategory, "Number of Species");
                        if ($output_string <> "") {
                            $speciescheck = 1;
                        }
                        break;
                    case "ENDEMICS":
                        $table = "links_endemics";
                        $output_string .= getcontent($table, $category, $subcategory, "Endemics");
                        if ($output_string <> "") {
                            $endemicscheck = 1;
                        }
                        break;
                    case "CHECKLIST":
                        $table = "checklist";
                        $output_string .= getcontent($table, $category, $subcategory, "Checklist");
                        if ($output_string <> "") {
                            $checklistcheck = 1;
                        }
                        break;
                    case "READING":
                        $table = "links_useful_reading";
                        $output_string .= getcontent($table, $category, $subcategory, "Useful Reading");
                        if ($output_string <> "") {
                            $readingcheck = 1;
                        }
                        break;
                    case "USEFUL":
                        $table = "links_useful_information";
                        $output_string .= getcontent($table, $category, $subcategory, "Useful Information");
                        if ($output_string <> "") {
                            $usefulcheck = 1;
                        }
                        break;
                    case "ORGANISATIONS":
                        $table = "links_organisations";
                        $output_string .= getcontent($table, $category, $subcategory, "Organisations");
                        if ($output_string <> "") {
                            $organisationscheck = 1;
                        }
                        break;
                    case "FESTIVALS":
                        $table = "links_festivals";
                        $output_string .= getcontent($table, $category, $subcategory, "Festivals");
                        if ($output_string <> "") {
                            $festivalscheck = 1;
                        }
                        break;
                    case "OBSERVATORIES":
                        $table = "links_observatories";
                        $output_string .= getcontent($table, $category, $subcategory, "Observatories");
                        if ($output_string <> "") {
                            $observatoriescheck = 1;
                        }
                        break;
                    case "MUSEUMS":
                        $table = "links_museums";
                        $output_string .= getcontent($table, $category, $subcategory, "Museums");
                        if ($output_string <> "") {
                            $museumscheck = 1;
                        }
                        break;
                    case "RESERVES":
                        $table = "links_reserves";
                        $output_string .= getcontent($table, $category, $subcategory, "Reserves");
                        if ($output_string <> "") {
                            $reservescheck = 1;
                        }
                        break;
                    case "TRIPS":
                        $table = "links_trip_reports";
                        $output_string .= getcontent($table, $category, $subcategory, "Trip Reports");
                        if ($output_string <> "") {
                            $tripscheck = 1;
                        }
                        break;
                    case "GUIDES":
                        $table = "links_holiday_companies";
                        $output_string .= getcontent($table, $category, $subcategory, "Guides & Tour Operators");
                        if ($output_string <> "") {
                            $guidescheck = 1;
                        }
                        break;
                    case "PLACES":
                        $table = "links_places_to_stay";
                        $output_string .= getcontent($table, $category, $subcategory, "Places to Stay");
                        if ($output_string <> "") {
                            $placescheck = 1;
                        }
                        break;
                    case "MAILING":
                        $table = "links_mailing_lists";
                        $output_string .= getcontent($table, $category, $subcategory, "Forums & Mailing Lists");
                        if ($output_string <> "") {
                            $mailingcheck = 1;
                        }
                        break;
                    case "LINKS":
                        $table = "links";
                        $output_string .= getcontent($table, $category, $subcategory, "Other Links");
                        if ($output_string <> "") {
                            $linkscheck = 1;
                        }
                        break;
                    case "BLOGS":
                        $table = "links_blogs";
                        $output_string .= getcontent($table, $category, $subcategory, "Blogs");
                        if ($output_string <> "") {
                            $blogscheck = 1;
                        }
                        break;
                    case "ARTISTS":
                        $table = "links_artists_photographers";
                        $output_string .= getcontent($table, $category, $subcategory, "Photographers & Artists");
                        if ($output_string <> "") {
                            $artistscheck = 1;
                        }
                        break;
                    case "LAST_UPDATED":
                        $table = "LAST_UPDATED";
                        $output_string .= getcontent($table, "", "");
                        if ($output_string <> "") {
                            $lastupdatedcheck = 1;
                        }
                        break;
                    case "NEWS":
                        $table = "NEWS";
                        $output_string .= getcontent($table, $category, "", "News");
                        if ($output_string <> "") {
                            $newscheck = 1;
                        }
                        break;
                    case "ARTICLE"://NEWS
                        $table = "ARTICLE";
                        $output_string .= getcontent($table, $category, "", "", $article_id);
                        break;
                    case "ARCHIVE"://NEWS
                        $table = "ARCHIVE";
                        $output_string .= getcontent($table, $category, "", "News Archive", $article_id);
                        break;
                    case "OFFERS":
                        $table = "OFFERS";
                        $output_string .= getcontent($table, $category, "", "Offers");
                        break;
                    case "OFFERS_ARTICLE":
                        $table = "OFFERS_ARTICLE";
                        $output_string .= getcontent($table, $category, "", "", $article_id);
                        break;
                    case "OFFERS_ARCHIVE":
                        $table = "OFFERS_ARCHIVE";
                        $output_string .= getcontent($table, $category, "", "Offers Archive", $article_id);
                        break;
                    case "ANNOUNCEMENT":
                        $table = "ANNOUNCEMENT";
                        $output_string .= getcontent($table, $category, "", "Announcements");
                        if ($output_string <> "") {
                            $announcementcheck = 1;
                        }
                        break;
                    case "ARTICLE_ANNOUNCEMENT":
                        $table = "ARTICLE_ANNOUNCEMENT";
                        $output_string .= getcontent($table, $category, "", "", $article_id);
                        break;
                    /*case "ARCHIVE_ANNOUNCEMENT":
                        $table = "ARCHIVE_ANNOUNCEMENT";
                        $output_string .= getcontent($table, $category, "", "Announcements Archive", $article_id);
                    break;*/
                    case "REVIEW":
                        $table = "REVIEW";
                        $output_string .= getcontent($table, $category, "", "Reviews");
                        if ($output_string <> "") {
                            $reviewcheck = 1;
                        }
                        break;
                    case "REVIEW_ARTICLE":
                        $table = "REVIEW_ARTICLE";
                        $output_string .= getcontent($table, $category, "", "", $article_id);
                        break;
                    case "REVIEW_ARCHIVE":
                        $table = "REVIEW_ARCHIVE";
                        $output_string .= getcontent($table, $category, "", "Reviews Archive", $article_id);
                        break;
                    case "DEFAULT_HOME":
                        $table = "links_introduction";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                    case "DEFAULT_ABOUT":
                        $table = "links_introduction";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                    case "SITE_MAP":
                        $table = "site_map";
                        $output_string = getcontent($table, $category, $subcategory);
                        break;
                    case "MAP":
                        $table = "map";
                        $output_string .= getcontent($table, $category, $subcategory);
                        break;
                    default:
                        $output_string .= $tok;
                }
                $tok = strtok("[]");
            }
            if ($output_string) {
                return $output_string;
            } else {
                return "Could not create Page";
            }
            //}
            /*********************************************
             ************ END CREATE HTML FILE ***********/
            // end check that template file can be found
        }
        //end if check for html file name and tempate name
    }
    return $ConfMsg;
//end function
}

function getcontent($table, $category, $subcategory, $sectiontitle = "", $article_id = "")
{
    $id = $article_id; //TODO Get rid of this and change article_id to id above when news, reviews & Announcements finally finished
    $content = "";
    $select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title ASC";
    $defaultselect = 'SELECT * FROM ' . $table . ' WHERE "category" = ' . $category . '" AND "sub-category" = ' . $subcategory . 'ORDER BY title ASC';
    switch ($table) {
        case "photos":
            if ($category != "news" && $category != "reviews" && $category != "announcements" && $category != "offers" && $category != "feedback") {
                //quick hack to allow for gorgia europe and USA
                if ($subcategory == "georgia") {
                    $country = $category . $subcategory;
                } else {
                    $country = $subcategory;
                }
                $photo = new Photo();
                $data = $photo->retrieveSiteData($country);
                if($data) {
                    $page = new DisplayPhoto("site", $data);
                    $content .= $page->displayContent();
                }
                /*connect_db();
                $top_content = 1;
                $select = "SELECT * FROM $table WHERE `country` = '$country'";
                $result = mysql_query($select);
                $count = mysql_num_rows($result);
                if ($count > 0) {
                    $photo = new Photo();
                    while ($row = mysql_fetch_assoc($result)) {
                        $content .= $photo->getphoto($row);
                        /* $content .= '<figure class="photo">';
                         if ($row["filename"]){
                           // $content .= '<img src="' . ROOT_DIR . '/photos/' . decode($row["filename"]) . '" />';
                                $content .= '<img src="/photos/' . decode($row['filename']) . '" />';
                         }
                         if ($row["text"]){
                            $content .= "<figcaption>".decode($row["text"])."</figcaption>";
                         }
                         $content .= "</figure>";
                    }
                }*/
            }
            break;
        case "map":
            $map = new Map("", $category, $subcategory);
            $content .= '<div class="imagemap">';
            $content .= $map->getMap();
            $content .= $map->getUseMap();
            $content .= '<p class="imagemaplinks">';
            $content .= $map->getRegionList();
            $content .= "</p>";
            $content .= "</div>";
            break;
        case "site_map":
            $select = "";
            $select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
            //echo $select;
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= "<p>";
                    $target = "map_" . $subcategory . ".gif";
                    $directory = "../../images";
                    $content .= getmap($target, $directory, 1, $subcategory);
                    if ($row["imagemap"]) {
                        $content .= "<p>";
                        $content .= "\n" . decode($row["imagemap"]) . "\n";
                        $content .= "</p>";
                    }
                    if ($row["regionlist"]) {
                        $content .= "<p class=\"imagemaplinks\">";
                        $content .= "\n<br>" . decode($row["regionlist"]) . "\n";
                        $content .= "</p>";
                    }
                    $content .= "</p>";
                }
            }
            break;
        case "links_trip_reports":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                $content .= '<div class="resultSectionSponsor"><a class="content_link" href="http://www.wildlifenaturedestinations.com" target="_blank"><img src="/adimages/banners/wandlogoblack.png" border="0" height="67" width="300"></a>';
                $content .= '<p>Click on WAND for tours, guides, lodges and more&#8230;</p></div>';
                $content .= $resultsection->itemStart();
                $content .= $resultsection->tripReportDefaultLink(); // include default trip report link
                $content .= $resultsection->itemEnd();
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["url1"]) {
                        $content .= $resultsection->url($row["url1"], $row['url1_title']);
                    }
                    if ($row["link_desc"]) {
                        $content .= $resultsection->text($row["link_desc"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            } else {
                if ($category != "ornithology" && $category != "news" && $category != "reviews" && $category != "announcements" && $category != "offers" && $category != "default_about" && $category != "feedback" && $category != "sitemap" && $category != "signpost" && $category != "commerce" && $category != "images_and_sound" && $category != "listing_and_racing" && $category != "fun" && $category != "webbirder" && $category != "terms") {
                    $resultsection = new SectionContent;
                    $content .= $resultsection->sectionStart($sectiontitle, $count);
                    $content .= $resultsection->itemStart();
                    $content .= $resultsection->tripReportDefaultLink(); // include default trip report link
                    $content .= $resultsection->itemEnd();
                    $content .= $resultsection->sectionend();
                }
            }
            break;
        case "links_holiday_companies":
        case "links_places_to_stay":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                $content .= '<div class="resultSectionSponsor"><a class="content_link" href="http://www.wildlifenaturedestinations.com" target="_blank"><img src="/adimages/banners/wandlogoblack.png" border="0" height="67" width="300"></a>';
                $content .= '<p>Click on WAND for tours, guides, lodges and more&#8230;</p></div>';
            while ($row = mysql_fetch_assoc($result)) {
                $content .= $resultsection->itemStart();
                if ($row["title"]) {
                    $content .= $resultsection->title($row["title"]);
                }
                if ($row["url1"]) {
                    $content .= $resultsection->url($row["url1"], $row['url1_title']);
                }
                if ($row["link_desc"]) {
                    $content .= $resultsection->text($row["link_desc"]);
                }
                $content .= $resultsection->itemEnd();
            }
            $content .= $resultsection->sectionend();
        }
        break;

        case "links":
        case "links_artists_photographers":
        case "links_festivals":
        case "links_museums":
        case "links_organisations":
        case "links_family_links":
        case "links_species_links":
        case "links_blogs":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["url1"]) {
                        $content .= $resultsection->url($row["url1"], $row['url1_title']);
                    }
                    if ($row["link_desc"]) {
                        $content .= $resultsection->text($row["link_desc"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_endemics":
            $crudobj = new Crud();
            $result = $crudobj->retrieve($select);
            if ($result) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->endemicsTitle($row["title"]);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_number_species":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->speciesTitle($row["title"]);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "checklist":
            if ($category != "news" || $category != "reviews" || $category != "announcements" || $category != "offers" || $category != "feedback") {
                $check = new DisplayChecklist("", $category, $subcategory);
                $content .= $check->getDisplaySite();
            }
            break;
        case "links_introduction":
            if ($category == "news" || $category == "reviews" || $category == "announcements" || $category == "offers" || $category == "feedback") {
                break;
            } else {
                connect_db();
                $select = "SELECT * FROM `introduction` WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
                $result = mysql_query($select);
                $count = mysql_num_rows($result);
                $resultsection = new SectionContent;
                if ($count > 0) {
                    while ($row = mysql_fetch_assoc($result)) {
                        $content .= $resultsection->intro($row["text"], $category);
                    }
                } else {
                    $select = "SELECT * FROM `introduction` WHERE `category` = 'default_intro' AND `sub-category` = 'default_intro'";
                    //echo $select;
                    $result = mysql_query($select);
                    $count = mysql_num_rows($result);
                    while ($row = mysql_fetch_assoc($result)) {
                        $content .= $resultsection->intro($row["text"], $category);
                    }
                }
            }
            break;
        case "familymenu":
            if ($subcategory == "species_and_families_homepage_passerines" || $subcategory == "species_and_families_homepage_non_passerines") {
                $check = new DisplayBirdFamily("", $category, $subcategory);
                $content .= $check->getDisplaySite();
            }
            break;
        case "banner":
            /*if ($category != "news" || $category != "reviews" || $category != "announcements" || $category != "feedback") {
                $object = new DisplayBanner("", $category, $subcategory);
                $content .= $object->getDisplaySite();
            }*/
            $banner = new Banner();
            $data = $banner->retrieveSiteData($category, $subcategory);
            if($data) {
                $page = new DisplayBanner("site", $data);
                $content .= $page->displayContent();
            }
            break;
           /* $select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id";
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= '<div class="banner">';
                    if ($row["sponsor"]) {
                    $content .= '<p class="sponsor_title">This page is sponsored by...<span class="sponsor_name">';
                        if ($row["url1_title"]) {
                            $content .= $row["url1_title"];
                        } else {
                            $content .= $row["sponsor"];
                        }
                        $content .= '</span></p>';
                    }
                    if ($row["url1"]) {
                        $content .= '<p><a href="' . $row["url1"] . '"><img class="banner_link" src="' . SITE_ROOT . BANNERIMGDIR . $row["filename"] . '" width="' . $row["width"] . '" height="' . $row["height"] . '"></a></p>';
                    }
                    if ($row["text"]) {
                        $content .= '<p class="sponsor_text">' . $row["text"] . '</p>';
                    }
                    $content .= '</div>';
                }
            }
            break;*/
        case "links_contributor":
            $select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id DESC";
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["name"]) {
                        $content .= $resultsection->title($row["name"]);
                    }
                    if ($row["title"]) {
                        $content .= $resultsection->text($row["title"]);
                    }
                    if ($row["location"]) {
                        $content .= $resultsection->text($row["location"]);
                    }
                    if ($row["email"]) {
                        $content .= $resultsection->email($row["email"]);
                    }
                    if ($row["url1"]) {
                        $content .= $resultsection->url($row["url1"], $row['url1_title']);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_county_recorder":
            $select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id DESC";
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["name"]) {
                        $content .= $resultsection->title($row["name"]);
                    }
                    if ($row["address"]) {
                        $content .= $resultsection->text($row["address"]);
                    }
                    if ($row["telephone"]) {
                        $content .= $resultsection->text($row["telephone"]);
                    }
                    if ($row["fax"]) {
                        $content .= $resultsection->text($row["fax"]);
                    }
                    if ($row["email"]) {
                        $content .= $resultsection->email($row["email"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_mailing_lists":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["url1"]) {
                        $content .= $resultsection->url($row["url1"], $row['url1_title']);
                    }
                    if ($row["post_email"]) {
                        $linkdesc = 'To post to list: ';
                        $content .= $resultsection->email($row["post_email"], $linkdesc);
                    }
                    if ($row["contact"]) {
                        $linkdesc = 'List contact: ';
                        $content .= $resultsection->email($row["contact"], $linkdesc);
                    }
                    if ($row["sub_email"]) {
                        $linkdesc = 'To subscribe to list: ';
                        $content .= $resultsection->email($row["sub_email"], $linkdesc);
                    }
                    if ($row["sub_message"]) {
                        $linkdesc = 'To unsubscribe: ';
                        $content .= $resultsection->email($row["sub_message"], $linkdesc);
                    }
                    if ($row["body_message"]) {
                        $content .= $resultsection->text($row["body_message"]);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_top_sites":
        case "links_reserves":
        case "links_observatories":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["url1"]) {
                        $content .= $resultsection->url($row["url1"], $row['url1_title']);
                    }
                    if ($row["url2"]) {
                        $content .= $resultsection->url($row["url2"], $row['url2_title']);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_useful_reading":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                $content .= '<a class="content_link" href="http://www.nhbs.com/index.html?ad_id=5" target="_blank"><img src="/adimages/banners/nhbs.jpg" border="0" height="89" width="200"></a>';
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    if ($row["isbn"]) {
                        $content .= $resultsection->isbn($row["isbn"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "links_useful_information":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                $resultsection = new SectionContent;
                $content .= $resultsection->sectionStart($sectiontitle, $count);
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= $resultsection->itemStart();
                    if ($row["title"]) {
                        $content .= $resultsection->title($row["title"]);
                    }
                    if ($row["text"]) {
                        $content .= $resultsection->text($row["text"]);
                    }
                    $content .= $resultsection->itemEnd();
                }
                $content .= $resultsection->sectionend();
            }
            break;
        case "default_about":
            $result = mysql_query($select);
            $count = mysql_num_rows($result);
            if ($count > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $content .= "<p>";
                    if ($row["title"]) {
                        $content .= "<h2>" . decode($row["title"]) . "</h2>";
                    }
                    if ($row["text"]) {
                        $content .= decode($row["text"]);
                    }
                    $content .= "</p>";
                }
            }
            break;
        case "NEWS":
            if ($category == "default_home") {
                $news = new News();
                $content .= $news->getDisplaySite();
            }
            break;
        case "ARTICLE":
            if ($category == "news" && $id != "") {
                $news = new News();
                $content .= $news->getArticle($id);
            }
            break;
        case "ARCHIVE":
            if ($category == "news" && $article_id == "") {
                $news = new News();
                $content .= $news->getArchive();
            }
            break;
        case "OFFERS":
            if ($category == "default_home") {
                $offers = new Offers();
                $content .= $offers->getDisplaySite();
            }
            break;
        case "OFFERS_ARTICLE":
            if ($category == "offers" && $id != "") {
                $offers = new Offers();
                $content .= $offers->getArticle($id);
            }
            break;
        case "OFFERS_ARCHIVE":
            if ($category == "offers" && $article_id == "") {
                $offers = new Offers();
                $content .= $offers->getArchive();
            }
            break;
        case "REVIEW":
            if ($category == "default_home") {
                $review = new Review();
                $content .= $review->getDisplaySite();
            }
            break;
        case "REVIEW_ARTICLE":
            if ($category == "reviews" && $id != "") {
                $news = new Review();
                $content .= $news->getArticle($id);
            }
            break;
        case "REVIEW_ARCHIVE":
            if ($category == "reviews" && $article_id == "") {
                $news = new Review();
                $content .= $news->getArchive();
            }
            break;
        case "ANNOUNCEMENT":
            if ($category == "default_home") {
                $announce = new Announcement();
                $content .= $announce->getDisplaySite();
            }
            break;
        case "ARTICLE_ANNOUNCEMENT":
            if ($category == "announcements" && $article_id != "") {
                $announce = new Announcement();
                $content .= $announce->getArticle($id);
            }
            break;
        /*case "ARCHIVE_ANNOUNCEMENT":
            if($category == "announcements" && $article_id != ""){
                $main = "announcements_article_main";
                $text = "announcements_article_text";
                $status = "announcements_article_status";
                $select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
                $select .= "AND $status.state = 'archived' ORDER BY $main.id DESC";
                $result = mysql_query($select);
                $count = mysql_num_rows($result);
                if ($count>0){
                    while ($row = mysql_fetch_assoc($result)){
                        $content .= "<p>";
                        $content .= "<font size=\"4\"><i>".$row["title"]."</i></font>";
                        //update 12-10-2005
                        //add a new icon to the news summary if it was pla ed in the last 14 days
                        $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
                        $current_news_date = date ("YmdHms");
                        $article_date = str_replace(array("-", " ", ":"), "", $row["article_timestamp"]);
                        if (($article_date > $old_news_date) && ($current_news_date > $article_date)){
                            $content .= " <img src=\"".ROOT_URL."/images/new_icon.gif";
                            $content .= "\" border=\"0\">";
                        }
                        // end add a new icon to the news summary if it was pla ed in the last 14 days
                        //update 12-10-2005
                        $content .= "<br>".auto_link( encode($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
                        $content .= "<br>Get full details here...</a>";
                        $content .= "<br>";
                        $content .= "<font size=\"1\" color=\"#404040\">published: ".$row["created"]."</font>";
                        $content .= "</p>";
                    }
                }
            }
        break;*/
        case "LAST_UPDATED":
            //output the last time the site was updated as a string message date format e.g. tuesday, 12th March 2004
            $content .= "This site was last updated on " . date(LONG_DATE) . ".";
            break;
        //end switch
    }
    return $content;
    /*if ($top_content){
        return $content;
    }else{
        if ($count > 7){
            return '<div class="content_scrollable">'.$content.'</div>';
        } else {
            if ($content <> "") {
                return '<div class="content">'.$content.'</div>';
            } else {
                return "";
            }
        }
    }*/
    //end function
}

//New version - includes titles
function auto_link($link, $title = null)
{
    /*$link_reg = '�\b((?:https?|ftp|file)://[-\w+&@#/%?=~|!:,.;]*[-\w+&@#/%=~|])�';
    $email_reg = "/([a-zA-Z0-9\.\-_]+)(@)([a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,6})/i";

    if (is_null($title) || trim($title) == ''){
        $link_repl = '<a href="\1">\1</a>';
        $email_repl = '<a href="mailto:\1\2\3">\1\2\3</a>';
    }
    else{
        $link_repl = '<a href="\1">'.$title.'</a>';
        $email_repl = '<a href="mailto:\1\2\3">'.$title.'</a>';
    }
   $string = mb_ereg_replace(URL_FORMAT, $link_repl, $string);
   $string = mb_ereg_replace($email_reg, $email_repl, $string);*/
    return decode($link);
}

//proportionally generate resize constraints within $max_nuwidth and $max_nuheight
function cons_resize($old_width, $old_height, $max_nuwidth, $max_nuheight)
{
    //set nu width and nu height in case they dont need changing
    $new_width = $old_width;
    $new_height = $old_height;
    //check for images that are too wide - resize proportionally to max width
    if ($old_width > $max_nuwidth) {
        // now we check for over-sized images and pare them down to the dimensions we need for display purposes
        $ratio = ($old_width > $max_nuwidth) ? (real)($max_nuwidth / $old_width) : 1;
        $new_width = ((int)($old_width * $ratio)); //full-size width
        $new_height = ((int)($old_height * $ratio)); //full-size height
    }
    //check for images that are still too high after width resize - resize proportionally to max height
    if ($new_height > $max_nuheight) {
        $ratio = ($new_height > $max_nuheight) ? (real)($max_nuheight / $new_height) : 1;
        $new_width = ((int)($new_width * $ratio)); //mid-size width
        $new_height = ((int)($new_height * $ratio)); //mid-size height
    }
    //echo "<br>ratio:$ratio new_width:$new_width new_height $new_height";
    //return new width/height constraints in an array
    return array($new_width, $new_height);
}

//end function cons_resize
//$HTTP_POST_FILES[$userfile]['name'], $HTTP_POST_FILES[$userfile]['tmp_name'], $HTTP_POST_FILES[$userfile]['type'], $HTTP_POST_FILES[$userfile]['size'], max_nuwidth, max_nuheight
//add function to add a specific variation_id, for a specific cookie_id
function upload_image(
    $name,
    $tmp_name,
    $type,
    $size,
    $max_nuwidth,
    $max_nuheight,
    &$filename,
    &$thumbnail,
    $filefolder = "",
    $rndm = 1
) {
    //echo "image size - ".$size;
    if ($name <> "") {
        if ($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || $size == 0) {
            //filesize of temp uploaded image - kick out for files greater thatn  > 1 meg= 1024800 bytes / 1mb
            if ($size > 1024800 || $size == 0) {
                return "image is too big. Max File Size = 1mb ";
            } else {
                $imageInfo = getimagesize($tmp_name);
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                //
                //check that a random number prefix has been requested in the argument list
                if ($rndm == 1) {
                    $token = md5(uniqid(rand(), 1));
                }
                //set thumbnail naming variable
                $thumbnail_prefix = "";
                $max_thumbwidth = "65";
                $max_thumbheight = "85";
                //$_files dont work so use older $HTTP_POST_FILES.
                if (is_uploaded_file($tmp_name)) {
                    // if the thumbnail parameter has been set to 1 then
                    // run the upload image funciton twice
                    // once to upload the main image
                    // again to create a thumbnail image
                    // otherwise just loop through once
                    if ($thumbnail == 1) {
                        $loop_limit = 3;
                    } else {
                        $loop_limit = 2;
                    }
                    for ($i = 1; $i < $loop_limit; $i++) {
                        if ($i > 1) {
                            //create the thumbnail image on the second loop through
                            list ($nu_width, $nu_height) = cons_resize(
                                $width,
                                $height,
                                $max_thumbwidth,
                                $max_thumbheight
                            );
                            //populate thumbnail naming variable
                            $thumbnail_prefix = "thumb_";
                        } else {
                            //create the thumbnail image on the second loop through
                            list ($nu_width, $nu_height) = cons_resize($width, $height, $max_nuwidth, $max_nuheight);
                            //populate thumbnail naming variable
                        }
                        //echo "width = $width , height = $height , nuWidth = $nu_width , nuheight = $nu_height max_nuwdith = $max_nuwidth , max_nuheight = $max_nuheight";
                        $dstbigImg = imagecreatetruecolor($nu_width, $nu_height);
                        /*
                        case "image/pjpeg"://---- all type jpeg code
                        case 'image/jpeg':
                        case 'image/jpg':
                        case "pjpeg":
                        case "jpeg":
                        case "jpg":
                        */
                        if ($type == "image/pjpeg") {
                            /*no need to re-sample and create the JPEG
                            //$srcImg = imagecreatefromjpeg($tmp_name);
                            //imagecopyresampled ($dstbigImg, $srcImg, 0, 0, 0, 0, $nu_width, $nu_height, $width, $height);
                            //if (!(imagejpeg($dstbigImg, "$filefolder/" . $thumbnail_prefix . $token . $name)))
                            */
                            if (!copy($tmp_name, "$filefolder/" . $thumbnail_prefix . $token . $name)) {
                                return "error creating JPEG";
                            } else {
                                $filename = $token . $name;
                            }
                        } else // gif current PHP distrib of GDLIB does not support GIF so cant resize
                        {
                            if ($width > $max_nuwidth || $height > $max_nuheight) {
                                $ret_upload_image = " Image is too large, w: $width , h: $height , max width = $max_nuwidth pixels, max height = $max_nuheight pixels";
                            } else {
                                if (!copy($tmp_name, "$filefolder/" . $thumbnail_prefix . $token . $name)) {
                                    return "error uploading file to server.";
                                } else {
                                    $filename = $token . $name;
                                    //echo $filename;
                                }
                            }
                            /*
                            // gdlib gif code
                            $srcImg = imagecreatefromgif($tmp_name);
                            echo "srcImg = " . $tmp_name . ", nuWidth = $nu_width , nuheight = $nu_height";
                            imagecopyresampled ($dstbigImg, $srcImg, 0, 0, 0, 0, $nu_width, $nu_height, $width, $height);
                            if (!(imagegif($dstbigImg, "../products/" . $token . $name)))
                            {
                                    return " error creating GIF";
                            }
                            else
                                return "complete";
                            */
                            //end second image type if
                        }
                        //end for loop
                    }
                    //end is upload if
                }
                //end size check
            }
        } // end type check
        else {
            return " is of wrong file type. Please use images of .gif or .jpg format. type = $type";
        }
        //end gif/jpeg check
    } //end name check
    else // no image to upload
    {
        return "no_image";
    }
}

// end upload_image function

function review_title($section)
{
    switch ($section) {
        case "books":
            $section = "Books";
            break;
        case "audio":
            $section = "Audio CD";
            break;
        case "cd_rom":
            $section = "CD-ROM";
            break;
        case "dvd":
            $section = "DVD";
            break;
        case "software":
            $section = "Software";
            break;
        case "binoculars":
            $section = "Binoculars";
            break;
        case "telescopes":
            $section = "Telescopes";
            break;
        case "cameras":
            $section = "Cameras";
            break;
        case "other":
            $section = "Outdoor Wear and Other Equipment";
            break;
        //other potential sections
        //Books, Audio CD, CD-ROM, DVD, Software, Binoculars, Telescopes, Cameras, Outdoor Wear and Other Equipment
    }
    return $section;
}

//(name of file, directory, show link as image 1 of 0 to show href, $map to attach an image map name)


/*
 *
 * */

class Map extends ConvertInput
{

    private $table = "map";

    private $mapname;
    private $imagemap; //True / False
    private $usemapname;

    private $id;
    private $cat;
    private $subcat;
    private $data;
    private $rowcount;
    private $mapcode;
    private $usemapcode;
    private $regionlistcode;

    public function __construct($id = "", $cat = "", $subcat = "")
    {
        if ($id <> "") {
            $this->id = $id;
            $this->getData();
        } else {
            $this->cat = $cat;
            $this->subcat = $subcat;
            $this->getData();
        }
    }

    public function getMap()
    {
        $this->createMapCode();
        return $this->mapcode;
    }

    public function getUseMap()
    {
        $this->createUseMapCode();
        return $this->usemapcode;
    }

    public function getRegionList()
    {
        $this->createRegionListCode();
        return $this->regionlistcode;
    }

    private function createMapCode()
    {
        if ($this->data[0]['imagefilename'] <> "") {
            $this->mapcode = '<img usemap="#' . $this->data[0]['sub-category'] . '" src="' . SITE_ROOT . MAP_DIR . $this->data[0]['imagefilename'] . '" />';
        }
    }

    private function createUseMapCode()
    {
        if ($this->data[0]['imagemap'] <> "") {
            $this->usemapcode = decode($this->data[0]['imagemap']);
        }
    }

    private function createRegionListCode()
    {
        if ($this->data[0]['regionlist'] <> "") {
            $this->regionlistcode = decode($this->data[0]['regionlist']);
        }
    }

    private function getData()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        if ($this->id <> "") {
            $sql .= ' WHERE id = ' . $this->id . ' LIMIT 0,1';
        } else {
            $sql .= ' WHERE `category` = "' . $this->cat . '" AND `sub-category` = "' . $this->subcat . '" LIMIT 0,1';
        }
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $result = $db->prepare($sql);
        $result->execute();
        $this->rowcount = $result->rowCount();
        if ($this->rowcount > 0) {
            $this->data = $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }


    /*	$select = "";
        $select = 'SELECT * FROM ' . $this->table . ' WHERE `category` = ' . $cat . ' AND `sub-category` = ' . $subcategory;
        $result = mysql_query($select);
        $count = mysql_num_rows($result);
        if ($count>0){
            while ($row = mysql_fetch_assoc($result)){
                $content .= '<div class="imagemap">';
                $target = "map_".$subcategory.".gif";
                $map = new Map();
                if ($row["imagemap"]){
                    $content .= $map->getMap($target, TRUE, $subcategory);//get as image map
                    $content .= "\n".decode($row["imagemap"])."\n";//the imagemap links
                }else{
                    $content .= $map->getMap($target, FALSE, $subcategory);	//get as plain image
                }
                if ($row["regionlist"]){
                    $content .= '<p class="imagemaplinks">';
                    $content .= decode($row["regionlist"]);// the text links
                    $content .= "</p>";
                    $content .= "</div>";
                }
            }
        }*/

}

function gettitle($input_title, $input_category = "")
{
    $input_title = ereg_replace('_', ' ', $input_title);
    $input_title = ereg_replace(' and ', ' &amp; ', $input_title);
    $formated_title = strtoupper($input_title{0}) . substr($input_title, 1);
    switch ($formated_title) {
        case "S america":
            $formated_title = "South America";
            break;
        case "N america":
            $formated_title = "North America";
            break;
        case "C america":
            $formated_title = "Central America";
            break;
        case "Books bookshops":
            $formated_title = "Bird bookshops";
            break;
        case "Tapes":
            $formated_title = "Videos, CDs and Software";
            break;
        case "Ulster general":
            $formated_title = "Northern Ireland";
            break;
        case "Ulster antrim":
            $formated_title = "Northern Ireland - County Antrim";
            break;
        case "Ulster armagh":
            $formated_title = "Northern Ireland - County Armagh";
            break;
        case "Ulster down":
            $formated_title = "Northern Ireland - County Down";
            break;
        case "Ulster fermanagh":
            $formated_title = "Northern Ireland - County Fermanagh";
            break;
        case "Ulster londonderry":
            $formated_title = "Northern Ireland - County Londonderry";
            break;
        case "Ulster tyrone":
            $formated_title = "Northern Ireland - County Tyrone";
            break;
        case "United kingdom channel islands":
            $formated_title = "Channel Islands";
            break;
        case "United kingdom isle of man":
            $formated_title = "Isle of Man";
            break;
        case "Indonesia sumatera":
            $formated_title = "Indonesia sumatara";
            break;
        case "South africa northern province":
            $formated_title = "Limpopo";
            break;
        case "Photos":
            $formated_title = "photos &amp; photographic tours";
            break;
        case "Index":
            if ($input_category == "trip_reports") {
                $formated_title = "Trip Reports";
            }
            break;
        case "Homepages":
            if ($input_category == "fun") {
                $formated_title = "Miscellany";
            }
            break;
    }
    //set the first letter of each word to upper case
    $formated_title = ucwords($formated_title);
    return $formated_title;
}

//(name of file, directory, type variable dictates the string format that is returned
function getfile($target, $directory, $type)
{
    if (is_dir($directory)) {
        $direc = opendir($directory);
        while (false !== ($file = readdir($direc))) {
            if ($file != "." && $file != "..") {
                if (is_file($directory . "/" . $file)) {
                    if (preg_match("/$target/i", $file)) {
                        switch ($type) {
                            case "image":
                                $type = "<img src=\"$directory/$file\" border=\"0\"><br>";
                                return $type;
                                break;
                            case "link":
                                $type = "<a href=\"$directory/$file\">$file</a><br>";
                                return $type;
                                break;
                            default:
                                $type = "$file";
                                return $type;
                        }
                    }
                } else {
                    if (is_dir($directory . "/" . $file)) {
                        // search($target,$directory."/".$file, $type);
                    }
                }
            }
        }
        closedir($direc);
    }
}


/**
 *
 */
class SectionContent extends ConvertInput
{

    function __construct()
    {

    }

    public function introHeading($text)
    {
        $sectioncontent = '<h2 class="introheading">' . $text . '</h2>';
        return $sectioncontent;
    }

    public function intro($text, $defhome)
    {
        if ($defhome == "default_home") {
            $sectioncontent = autolink($this->decode($text));
        } else {
            $sectioncontent = '<p class="text">' . autolink($this->decode($text)) . '</p>';
        }
        return $sectioncontent;
    }

    public function sectionStart($sectitle, $count = 0)
    {
        if($sectitle == "announcements"){
            $sectitle = "announcements &amp; articles";
        }
        $sectioncontent = '<section class="resultbox">
							<div class="resulttitle">
								<h2>' . $sectitle . '</h2>
							</div>
							<div class="resultarea';
        if ($count > BOX_SCROLL_LIMIT) {
            $sectioncontent .= ' scrollable';
        }
        $sectioncontent .= '">';
        return $sectioncontent;
    }

    public function sectionArchiveStart()
    {
        $sectioncontent = '<section class="resultboxarchive">
							<div class="resultarea">';
        return $sectioncontent;
    }

    /*---------------------------------------------------------*/

    public function newsitemStart()
    {
        $sectioncontent = '<div class="newsitem clearfix">';
        return $sectioncontent;
    }

    public function newsIcon($img, $dir)
    {
        $sectioncontent = '<div class="newsitemicon">';
        switch ($dir) {
            case "announcements":
                $icondir = ANNOUNCEMENTS_ICON_DIR;
                break;
            case "news":
                $icondir = NEWS_ICON_DIR;
                break;
            case "offers":
                $icondir = OFFERS_ICON_DIR;
                break;
            case "reviews":
                $icondir = REVIEWS_ICON_DIR;
                break;
        }
        $sectioncontent .= '<img src="' . SITE_ROOT . $icondir . $img;
        $sectioncontent .= '" width="90" height="90" alt=""/>';
        $sectioncontent .= '</div>';
        return $sectioncontent;
    }

    public function newsItemTextStart()
    {
        $sectioncontent = '<div class="newsitemtext">';
        return $sectioncontent;
    }

    public function newsItemSection($section)
    {
        $sectioncontent = '<p class="newsitemtype">' . $this->decode($section) . '</p>';
        return $sectioncontent;
    }

    public function newsSection($secname)
    {
        $sectioncontent = '<p class="reviewsection">' . $this->decode($secname) . '</p>';
        return $sectioncontent;
    }

    public function newsItemTitle($dir, $id, $title)
    {
        $sectioncontent = '<h3 class ="newsitemtitle"><a href="' . $dir . '/index.php?article=' . $id . '">' . $this->decode(
                $title
            ) . "</a></h3>";
        return $sectioncontent;
    }

    public function newsItemSummary($summ)
    {
        $sectioncontent = '<p class="newsitempreview">' . $this->decode($summ) . '</p>';
        return $sectioncontent;
    }

    public function newsItemDate($newstime)
    {
        $sectioncontent = '<p class="newsitemdate">' . $this->convertTimestampToDate("long", $newstime) . '</p>';
        return $sectioncontent;
    }

    public function newsitemEnd()
    {
        $sectioncontent = '</div>';
        return $sectioncontent;
    }

    public function newsArchiveLink($dir)
    {
        $sectioncontent = '<p class="morelink"><a href="' . $dir . '/archive.php">More stories are available in the ' . ucfirst(
                $dir
            ) . ' archive...</a></p>';
        return $sectioncontent;
    }

    public function articleHead($title, $summary)
    {
        $sectioncontent = '<h2>' . $this->decode($title) . '</h2>';
        $sectioncontent .= '<p class="articlesummary">' . $this->decode($summary) . '</p>';
        return $sectioncontent;
    }

    public function articleBody($text)
    {
        $sectioncontent = $this->decode($text);
        return $sectioncontent;
    }

    public function articleCredit($credit)
    {
        $sectioncontent = '<p class="articlecredit">' . $this->decode($credit) . '</p>';
        return $sectioncontent;
    }

    public function articleEnd($created)
    {
        $sectioncontent = '<p class="articlecreated">' . $this->convertTimestampToDate("long", $created) . '</p>';
        return $sectioncontent;
    }

    /*---------------------------------------------------*/

    public function itemStart()
    {
        $sectioncontent = '<div class="resultarea_item">';
        return $sectioncontent;
    }

    public function itemTitle($title){
        $sectioncontent = '<p class="resultarea_item_title">' . $title . '</p>';
        return $sectioncontent;
    }

    public function itemText($anytext)
    {
        $sectionoutput = '<p class="resultarea_item_text">' . $anytext . '</p>';
        return $sectionoutput;
    }

    public function itemUrl($link, $linktext)
    {
        if ($linktext == "") {
            $linktext = $link;
        }
        $sectionoutput = '<p class="resultarea_item_url">
									<a href="' . $link . '">' . $linktext . '</a>
								</p>';
        return $sectionoutput;
    }

    public function itemEnd()
    {
        $sectioncontent = '</div>';
        return $sectioncontent;
    }

    public function title($titletext)
    {
        $sectionoutput = '<p class="resultarea_item_title">' . $this->decode($titletext) . '</p>';
        return $sectionoutput;
    }

    public function endemicsTitle($entitle)
    {
        $sectioncontent = '<p><span class="bold">Number of endemics: ' . $this->decode($entitle) . '</p>';
        return $sectioncontent;
    }

    public function speciesTitle($sptitle)
    {
        $sectioncontent = '<p><span class="bold">Number of bird species: ' . $this->decode($sptitle) . '</p>';
        return $sectioncontent;
    }

    public function url($link, $linktext = "")
    {
        $link = $this->decode($link);
        if ($linktext == "") {
            $linktext = $link;
        } else {
            $linktext = $this->decode($linktext);
        }
        $sectionoutput = '<p class="resultarea_item_url">
									<a href="' . $link . '">' . $linktext . '</a>
								</p>';
        return $sectionoutput;
    }

    public function email($link, $linkdesc = "")
    {
        $link = $this->decode($link);
        $sectionoutput = '<p class="resultarea_item_url">';
        if ($linkdesc <> "") {
            $sectionoutput .= '<span class="bold">' . $linkdesc . '</span>';
        }
        $sectionoutput .= '<a href="mailto:' . $link . '">' . $link . '</a></p>';
        return $sectionoutput;
    }

    public function text($anytext)
    {
        $sectionoutput = '<p class="resultarea_item_text">' . $this->decode($anytext) . '</p>';
        return $sectionoutput;
    }

    public function sectionend()
    {
        $sectionoutput = '</div>
						</section>';
        return $sectionoutput;
    }

    public function tripReportDefaultLink()
    {
        $sectioncontent = $this->title("CloudBirders");
        $sectioncontent .= $this->url("http://www.cloudbirders.com", "Trip Report Repository");
        $sectioncontent .= $this->text(
            "CloudBirders was created by a group of Belgian world birding enthusiasts and went live on 21st of March 2013. They provide a large and growing database of birding trip reports, complemented with extensive search, voting and statistical features."
        );
        return $sectioncontent;
    }

    public function sectionSponsoredByBirdersTravel()
    {
        $sectioncontent = '<div class="content_link"><p>This section sponsored by: <a class="content_link" href="http://www.birderstravel.com" target="_blank"><img src="/images/sponsorbirderstravel.jpg" width="200" height="60"></a></p></div>';
        return $sectioncontent;
    }

    public function isbn($num)
    {
        $sectioncontent = '<p class="resultarea_item_text">ISBN: ' . $num . '</p>';
        $sectioncontent .= '<a href="http://www.nhbs.com/book_isbn_' . $num . '_ca_5.html" target="_blank">Buy this book from NHBS.com</a>';
        return $sectioncontent;
    }

    public function webbirderLink(){
        $sectioncontent = '<a class="content_link_right" href="http://webbirder.azurewebsites.net/?Fatbirder=1" target="_blank"><img src="/adimages/banners/WebBirderNews.jpg" border="0" height="90" width="90" /></a>';
        return $sectioncontent;
    }
}

/**
 *
 */
class Crud
{

    private $db;

    public function __construct()
    {
    }

    public function retrieve($query)
    {
        $dbcon = new Dbconnection();
        $db = $dbcon->connect();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            return $stmt;
        } else {
            return false;
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function __destruct()
    {
        $this->db = null;
    }

}

/**
 *
 */
class Dbconnection
{

    public function __construct()
    {
    }

    public function connect()
    {
        try {
            $dns = "mysql:host=" . DB_HOST . ";dbname=" . DB;
            $con = new PDO($dns, USER, PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            return $con;
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }
}

/**
 *

class Photo
{

    private $photocode;
    private $picdir = "photos/";

    public function getphoto($data)
    {
        $this->createCode($data);
        return $this->photocode;
    }

    private function createCode($data)
    {
        if ($data["filename"]) {
            $this->photocode .= '<figure class="photo">';
            $this->photocode .= '<img src="' . SITE_ROOT . $this->picdir . $data['filename'] . '" height="' . $data['height'] . '" width = "' . $data['width'] . '" alt="' . $data['alt'] . '" />';
            $this->photocode .= '<figcaption style="width:' . $data['width'] . 'px;">';
            if ($data['common_name']) {
                $this->photocode .= '<span class="bold">' . decode($data['common_name']) . '</span>';
            }
            if ($data['sci_name']) {
                $this->photocode .= ' <span class="latinname">' . decode($data['sci_name']) . '</span>';
            }
            if ($data['credit']) {
                $this->photocode .= ' ' . decode("&#169") . decode($data['credit']);
            }
            if ($data['url']) {
                $this->photocode .= ' <a href="' . decode($data['url']) . '">Website</a>';
            }
            $this->photocode .= '</figcaption>';
            $this->photocode .= "</figure>";
        }else{
            $this->photocode = "";
        }
    }
}*/

class email
{

    private $mailto = "bo@fatbirder.com";
    private $mail_subject = "Feedback from fatbirder.com";
    private $mail_body = "";
    private $mail_footer = "fatbirder.com";
    private $name = "";
    private $email = "";
    private $feedback = "";
    private $country;

    public function __construct($post)
    {
        if ($post['feedback_name']) {
            $this->name = strip_tags(stripslashes($post['feedback_name']));
        }
        $this->email = strip_tags(stripslashes($post['feedback_email']));
        $this->feedback = strip_tags(stripslashes($post['feedback']));
        $this->country = $post['country'];
    }

    public function send()
    {
        if ($this->country == "") {
            $this->mail_body = '<h3>Fatbirder Feedback Form</h3>';
            $this->mail_body .= '<p>From: ' . $this->name . '</p>';
            $this->mail_body .= '<p>Email: ' . $this->email . '</p>';
            $this->mail_body .= '<p>Message:<br>';
            $this->mail_body .= $this->feedback . '</p>';
            $mail = new PHPMailer();
            $mail->isHTML(true);
            $mail->From = $this->email;
            if ($this->name != "") {
                $mail->FromName = $this->name;
            } else {
                $mail->FromName = "Feedback form";
            }
            $mail->Subject = $this->mail_subject;
            $mail->Body = $this->mail_body;
            $mail->AltBody = strip_tags($this->mail_body);
            $mail->AddAddress($this->mailto);
            if (!$mail->send()) {
                return $mail->ErrorInfo;
            } else {
                $mess = 'Thank you for completing the feedback form, if you have asked a question Fatbirder will reply to just as soon as possible but bear in mind that a lot of questions are posed so the reply may not be instant. If you made a comment that needs no reply be assured it will be read and is appreciated. If you were just saying "hi", or making some nice comment, thanks and good birding.';
                return $mess;
            }
        }
    }
}
    
//-- Facebook Share Button functions START
function getFacebookShareButtonCode(){
    $absolute_url = full_url( $_SERVER );
    $fullCode = '<!-- Your share button code --> <div class="fb-share-button"  data-href="' . $absolute_url . '" data-layout="button_count">SHARE </div>';
    return $fullCode;
}
function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}
//-- Facebook Share Button functions END

?>