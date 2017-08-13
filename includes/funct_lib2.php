<?php

include("admin/storydisplay.php");
define ("ROOT_DIR", $_SERVER['DOCUMENT_ROOT'] . "/");
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
require_once(ROOT_DIR . 'includes/class.upload.php');
require (ROOT_DIR . 'includes/phpmailer/PHPMailerAutoload.php');
	// db settings

	/*remote*/


	define ("DB_HOST", "localhost");
	define ("DB", "fbiwadm_newsite");
	define ("USER", "root");
	define ("PASS", "X5h7W23g");


	//test settings
/*define ("DB_HOST", "localhost");
define ("DB", "fbiwadm_fbnewsite");
define ("USER", "fbiwadm_newfbuse");
define ("PASS", "oX@Wl&VAVby~");*/
define ("ROOT_URL", "http://www.fatbirder.com");
define ("dir_const", "/home/fbiwadm/www/");


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

	define('URL_FORMAT',
		'/^(https?|ftp):\/\/'.                                         // protocol
		'(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'.         // username
		'(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'.      // password
		'@)?(?#'.                                                  // auth requires @
		')((([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*'.                      // domain segments AND
		'[a-z][a-z0-9-]*[a-z0-9]'.                                 // top level domain  OR
		'|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}'.
		'(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'.                 // IP address
		')(:\d+)?'.                                                // port
		')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'. // path
		'(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'.      // query string
		'?)?)?'.                                                   // path and query string optional
		'(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'.      // fragment
		'$/i');

class ConvertInput{

	public function encode($string){
		return htmlentities($string, ENT_HTML5 | ENT_QUOTES, "UTF-8", false);
	}

	public function decode($string){
		return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
	}

	public function convertForInput($string){//use before encode() on new input from a form
		$string = str_ireplace("<i>", "<em>", $string);
		$string = str_ireplace("</i>", "</em>", $string);
		$string = str_ireplace("<b>", "<strong>", $string);
		$string = str_ireplace("</b>", "</strong>", $string);
		$string = str_ireplace("<br>", "<br />", $string);
		$string = str_ireplace("...", "&hellip;", $string);
		$string = str_ireplace("&NewLine;", "", $string);
		$string = str_ireplace(chr(10), "", $string);
		$string = str_ireplace(chr(92), "&rsquo;", $string);
		$string = str_ireplace(chr(128), "&euro;", $string);
		$string = str_ireplace(chr(133), "&hellip;", $string);
		$string = str_ireplace(chr(145), "&lsquo;", $string);
		$string = str_ireplace(chr(146), "&rsquo;", $string);
		$string = str_ireplace(chr(147), "&ldquo;", $string);
		$string = str_ireplace(chr(148), "&rdquo;", $string);
		$string = str_ireplace(chr(150), "&ndash;", $string);
		$string = str_ireplace(chr(151), "&mdash;", $string);
		$string = decode($string);
		return $string;
	}

	public function convertDateToTimestamp($newdate){
		$date = DateTime::createFromFormat('j/n/y', $newdate)->getTimestamp();;
		return $date;
	}

	public function convertTimestampToDate($format, $newdate){
		if($format == "short"){
			return date(SHORT_DATE, $newdate);
		}
		if($format == "long"){
			return date(LONG_DATE, $newdate);
		}
	}

}

function encode($string){
	return htmlentities($string, ENT_HTML5 | ENT_QUOTES, "UTF-8", false);
}

function decode($string){
	return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
}

function convertForInput($string){//use before encode() on new input from a form
	$string = str_ireplace("<i>", "<em>", $string);
	$string = str_ireplace("</i>", "</em>", $string);
	$string = str_ireplace("<b>", "<strong>", $string);
	$string = str_ireplace("</b>", "</strong>", $string);
	$string = str_ireplace("<br>", "<br />", $string);
	$string = str_ireplace("...", "&hellip;", $string);
	$string = str_ireplace("&NewLine;", "", $string);
	$string = str_ireplace(chr(10), "", $string);
	$string = str_ireplace(chr(92), "&rsquo;", $string);
	$string = str_ireplace(chr(128), "&euro;", $string);
	$string = str_ireplace(chr(133), "&hellip;", $string);
	$string = str_ireplace(chr(145), "&lsquo;", $string);
	$string = str_ireplace(chr(146), "&rsquo;", $string);
	$string = str_ireplace(chr(147), "&ldquo;", $string);
	$string = str_ireplace(chr(148), "&rdquo;", $string);
	$string = str_ireplace(chr(150), "&ndash;", $string);
	$string = str_ireplace(chr(151), "&mdash;", $string);
	$string = decode($string);
	return $string;
}

function session_authenticated()
{
	session_start();
	if (session_is_registered("user_auth") && ($_SESSION ['user_auth'] == "user"))
		return true;
	else
		return false;
}
function connect_db(){
	if(!mysql_connect(DB_HOST, USER, PASS))
	{
			echo "<p>Unable to connect to MySQL server: <strong><em>" . DB_HOST . "</em></strong></p>";
			exit;
	}
	if(!mysql_select_db(DB))
	{
			echo "<p>Unable to find database <strong><em>" . DB . "</em></strong> on host <strong><em>" . DB_HOST . "</em></strong></p>";
			exit;
	}
}

function createHTML($tmpl_name, $category, $subcategory, $article_id="", $message = ""){
	$id = $article_id;//GEt rid of this and change article_id to id above when news, reviews & Announcements finally finished
	//perform quick variable validation loops
	if($tmpl_name == ""){
		$ConfMsg = "No template name was provided";
		return $ConfMsg;
	}else{
		$template_name = $tmpl_name;
		$template_path = ROOT_DIR . TEMPLATE_DIR . $template_name;
		//open the template file and place a handle on it
		$template_handle = @fopen($template_path,"r");
		if(!$template_handle) {
			print "<br> failed to open template";
			$ConfMsg = "<B>$fileURL</B> not found on server";
		}else {
			while(!feof($template_handle)) {
				$buffer = fgets($template_handle,4096);
				$text .= $buffer;
			}
			/*********************************************
			********* DEAL WITH TEMPLATE TOKENS *********/
			$input_string = $text;
			$output_string = "";
			$tok = strtok($input_string,"[]");
			$sql = $tmpl_sql;
			while ($tok) {
				//then perform an action based on the flag within the tokened area
				switch ($tok){
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
			         $target = ROOT_DIR."includes/menu.inc.php";
			         $output_string .= file_get_contents($target);
			      break;
			      case "FEEDBACK":
						if($category == "feedback" || $subcategory == "feedback"){
				         $target = ROOT_DIR."includes/feedback.inc.php";
				         $output_string .= file_get_contents($target);
							if($message != ""){
								$output_string .= '<p class="feedback_message">' . $message . '</p>';
							}
						}
			      break;
			      case "SEARCH":
			         $target = ROOT_DIR."includes/searchbox.inc.php";
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
					case "FAMILY_LINKS":
						//echo $tok;
						$table = "links_family_links";
						$output_string .= getcontent($table, $category, $subcategory, "Family Links");
						if ($output_string <> ""){
							$familylinkscheck = 1;
						}
					break;
					case "SPECIES_LINKS":
						$table = "links_species_links";
						$output_string .= getcontent($table, $category, $subcategory, "Species Links");
						if ($output_string <> ""){
							$specieslinkscheck = 1;
						}
					break;
					case "BANNER":
						$table = "links_banners";
						$output_string .= getcontent($table, $category, $subcategory);
					break;
					case "TOPSITES":
						$table = "links_top_sites";
						$output_string .= getcontent($table, $category, $subcategory, "Top Sites");
						if ($output_string <> ""){
							$topsitescheck = 1;
						}
					break;
					case "CONTRIBUTOR":
						$table = "links_contributor";
						$output_string .= getcontent($table, $category, $subcategory, "Contributor");
						if ($output_string <> ""){
							$contributorcheck = 1;
						}
					break;
					case "RECORDER":
						$table = "links_county_recorder";
						$output_string .= getcontent($table, $category, $subcategory, "County Recorder");
						if ($output_string <> ""){
							$recordercheck = 1;
						}
					break;
					case "SPECIES":
						$table = "links_number_species";
						$output_string .= getcontent($table, $category, $subcategory, "Number of Species");
						if ($output_string <> ""){
							$speciescheck = 1;
						}
					break;
					case "ENDEMICS":
						$table = "links_endemics";
						$output_string .= getcontent($table, $category, $subcategory, "Endemics");
						if ($output_string <> ""){
							$endemicscheck = 1;
						}
					break;
					case "READING":
						$table = "links_useful_reading";
						$output_string .= getcontent($table, $category, $subcategory, "Useful Reading");
						if ($output_string <> ""){
						$readingcheck = 1;
							}
					break;
					case "USEFUL":
						$table = "links_useful_information";
						$output_string .= getcontent($table, $category, $subcategory, "Useful Information");
						if ($output_string <> ""){
							$usefulcheck = 1;
						}
					break;
					case "ORGANISATIONS":
						$table = "links_organisations";
						$output_string .= getcontent($table, $category, $subcategory, "Organisations");
						if ($output_string <> ""){
							$organisationscheck = 1;
						}
					break;
					case "FESTIVALS":
						$table = "links_festivals";
						$output_string .= getcontent($table, $category, $subcategory, "Festivals");
						if ($output_string <> ""){
							$festivalscheck = 1;
						}
					break;
					case "OBSERVATORIES":
						$table = "links_observatories";
						$output_string .= getcontent($table, $category, $subcategory, "Observatories");
						if ($output_string <> ""){
							$observatoriescheck = 1;
						}
					break;
					case "MUSEUMS":
						$table = "links_museums";
						$output_string .= getcontent($table, $category, $subcategory, "Museums");
						if ($output_string <> ""){
							$museumscheck = 1;
						}
					break;
					case "RESERVES":
						$table = "links_reserves";
						$output_string .= getcontent($table, $category, $subcategory, "Reserves");
						if ($output_string <> ""){
							$reservescheck = 1;
						}
					break;
					case "TRIPS":
						$table = "links_trip_reports";
						$output_string .= getcontent($table, $category, $subcategory, "Trip Reports");
						if ($output_string <> ""){
							$tripscheck = 1;
						}
					break;
					case "GUIDES":
						$table = "links_holiday_companies";
						$output_string .= getcontent($table, $category, $subcategory, "Guides & Tour Operators");
						if ($output_string <> ""){
							$guidescheck = 1;
						}
					break;
					case "PLACES":
						$table = "links_places_to_stay";
						$output_string .= getcontent($table, $category, $subcategory, "Places to Stay");
						if ($output_string <> ""){
							$placescheck = 1;
						}
					break;
					case "MAILING":
						$table = "links_mailing_lists";
						$output_string .= getcontent($table, $category, $subcategory, "Forums & Mailing Lists");
						if ($output_string <> ""){
							$mailingcheck = 1;
						}
					break;
					case "LINKS":
						$table = "links";
						$output_string .= getcontent($table, $category, $subcategory, "Other Links");
						if ($output_string <> ""){
							$linkscheck = 1;
						}
					break;
					case "BLOGS":
						$table = "links_blogs";
						$output_string .= getcontent($table, $category, $subcategory, "Blogs");
						if ($output_string <> ""){
							$blogscheck = 1;
						}
					break;
					case "ARTISTS":
						$table = "links_artists_photographers";
						$output_string .= getcontent($table, $category, $subcategory, "Photographers & Artists");
						if ($output_string <> ""){
							$artistscheck = 1;
						}
					break;
					case "LAST_UPDATED":
						$table = "LAST_UPDATED";
						$output_string .= getcontent($table, "", "");
						if ($output_string <> ""){
							$lastupdatedcheck = 1;
						}
					break;
					case "NEWS":
						$table = "NEWS";
						$output_string .= getcontent($table, $category, "", "News");
						if ($output_string <> ""){
							$newscheck = 1;
						}
					break;
					case "REVIEW":
						$table = "REVIEW";
						$output_string .= getcontent($table, $category, "", "Reviews");
						if ($output_string <> ""){
							$reviewcheck = 1;
						}
					break;
					case "ARTICLE":
						$table = "ARTICLE";
						$output_string .= getcontent($table, $category, "", "", $article_id);
					break;
					case "ARCHIVE":
						$table = "ARCHIVE";
						$output_string .= getcontent($table, $category, "", "News Archive", $article_id);
					break;
					case "ANNOUNCEMENT":
						$table = "ANNOUNCEMENT";
						$output_string .= getcontent($table, $category, "", "Announcements");
						if ($output_string <> ""){
							$announcementcheck = 1;
						}
					break;
					case "ARTICLE_ANNOUNCEMENT":
						$table = "ARTICLE_ANNOUNCEMENT";
						$output_string .= getcontent($table, $category, "", "", $article_id);
					break;
					case "ARCHIVE_ANNOUNCEMENT":
						$table = "ARCHIVE_ANNOUNCEMENT";
						$output_string .= getcontent($table, $category, "", "Announcements Archive", $article_id);
					break;
					case "REVIEW_ARTICLE":
						$table = "REVIEW_ARTICLE";
						$output_string .= getcontent($table, $category, "","", $article_id);
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
		   }else{
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

function getcontent($table, $category, $subcategory, $sectiontitle="",  $article_id=""){
	$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
	$defaultselect = 'SELECT * FROM ' . $table . ' WHERE "category" = ' . $category . ' AND "sub-category" = ' . $subcategory . 'ORDER BY title ASC';
	switch ($table){
      case "photos":
			if($category != "news" && $category != "reviews" && $category != "announcements" && $category != "feedback"){
	         //quick hack to allow for gorgia europe and USA
	         if ($subcategory == "georgia"){
	            $country = $category.$subcategory;
	         }else{
	            $country = $subcategory;
	         }
				$top_content=1;
	         $select = "SELECT * FROM $table WHERE `country` = '$country'";
	         $result = mysql_query($select);
	         $count = mysql_num_rows($result);
	         if ($count>0){
	         	$photo = new Photo();
	            while ($row = mysql_fetch_assoc($result)){
	            	$content .= $photo->getphoto($row);
	              /* $content .= '<figure class="photo">';
	               if ($row["filename"]){
	                 // $content .= '<img src="' . ROOT_DIR . '/photos/' . decode($row["filename"]) . '" />';
						  $content .= '<img src="/photos/' . decode($row['filename']) . '" />';
	               }
	               if ($row["text"]){
	                  $content .= "<figcaption>".decode($row["text"])."</figcaption>";
	               }
	               $content .= "</figure>";*/
	            }
	         }
			}
      break;
		case "map":
			$select = "";
			$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
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
			}
		break;
		case "site_map":
			$select = "";
			$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
			//echo $select;
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				while ($row = mysql_fetch_assoc($result)){
					$content .= "<p>";
					$target = "map_".$subcategory.".gif";
					$directory = "../../images";
					$content .= getmap($target, $directory, 1, $subcategory);
					if ($row["imagemap"]){
						$content .= "<p>";
						$content .= "\n".decode($row["imagemap"])."\n";
						$content .= "</p>";
					}
					if ($row["regionlist"]){
						$content .= "<p class=\"imagemaplinks\">";
						$content .= "\n<br>".decode($row["regionlist"])."\n";
						$content .= "</p>";
					}
					$content .= "</p>";
				}
			}
		break;
		case "links_trip_reports":
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
					$content .= $resultsection->itemStart();
					$content .= $resultsection->tripReportDefaultLink();// include default trip report link
					$content .= $resultsection->itemEnd();
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
				   if ($row["url1"]) {
					   $content .= $resultsection->url($row["url1"], $row['url1_title']);
				   }
					if ($row["link_desc"]){
						$content .= $resultsection->text($row["link_desc"]);
					}
					$content .= $resultsection->itemEnd();
				}
				$content .= $resultsection->sectionend();
			}else{
				if($category != "ornithology" && $category !="news" && $category != "reviews" && $category != "announcements" && $category != "default_about" && $category != "feedback" && $category != "sitemap" && $category !="signpost" && $category !="commerce" && $category !="images_and_sound" && $category !="listing_and_racing" && $category !="fun"){
					$resultsection = new SectionContent;
					$content .= $resultsection->sectionStart($sectiontitle, $count);
					$content .= $resultsection->itemStart();
					$content .= $resultsection->tripReportDefaultLink();// include default trip report link
					$content .= $resultsection->itemEnd();
					$content .= $resultsection->sectionend();
				}
			}
		break;
		case "links_holiday_companies":
		case "links":
		case "links_artists_photographers":
		case "links_festivals":
		case "links_museums":
		case "links_organisations":
		case "links_places_to_stay":
		case "links_family_links":
		case "links_species_links":
      case "links_blogs":
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				if ($table == "links_trip_reports" && $category != "ornithology"){
					$content .= $resultsection->itemStart();
					$content .= $resultsection->tripReportDefaultLink();// include default trip report link
					$content .= $resultsection->itemEnd();
				}
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
				   if ($row["url1"]) {
					   $content .= $resultsection->url($row["url1"], $row['url1_title']);
				   }
					if ($row["link_desc"]){
						$content .= $resultsection->text($row["link_desc"]);
					}
					$content .= $resultsection->itemEnd();
				}
				$content .= $resultsection->sectionend();
			}else{
				if($table == "links_trip_reports" && ($category != "ornithology" && $category !="news" && $category != "reviews" && $category != "announcements" && $category != "default_about" && $category != "feedback" && $category != "sitemap")){
					$resultsection = new SectionContent;
					$content .= $resultsection->sectionStart($sectiontitle, $count);
					$content .= $resultsection->itemStart();
					$content .= $resultsection->sectionSponsoredByBirdersTravel();
					$content .= $resultsection->tripReportDefaultLink();// include default trip report link
					$content .= $resultsection->itemEnd();
					$content .= $resultsection->sectionend();
				}
			}
		break;
		case "links_endemics":
			$crudobj = new Crud();
			$result = $crudobj->retrieve($select);
			if($result){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->endemicsTitle($row["title"]);
					}
					if ($row["text"]){
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
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->speciesTitle($row["title"]);
					}
					if ($row["text"]){
						$content .= $resultsection->text($row["text"]);
					}
					$content .= $resultsection->itemEnd();
				}
				$content .= $resultsection->sectionend();
			}
		break;
		case "links_introduction":
			if($category == "news" || $category == "reviews" || $category == "announcements" || $category == "feedback"){
				break;
			}else{
				$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY paragraph";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				$resultsection = new SectionContent;
				if ($count>0){
					while ($row = mysql_fetch_assoc($result)){
						$content .= $resultsection->intro($row["text"], $category);
					}
				}else{
					$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = 'default_intro' AND `sub-category` = 'default_intro') ORDER BY paragraph";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);
					while ($row = mysql_fetch_assoc($result)){
						$content .= $resultsection->intro($row["text"], $category);
					}
				}
			}
		break;
		case "links_banners":
			$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				while ($row = mysql_fetch_assoc($result)){
					$content .= '<div class="banner">';
					$content .= '<p class="sponsor_title">This page is sponsored by...</p>';
					if ($row["sponsor"]){
						$content .= '<p class="sponsor_name">';
						if($row["url1_title"]){
							$content .= $row["url1_title"];
						}else{
							$content .= $row["sponsor"];
						}
						$content .= '</p>';
					}
					if ($row["url1"]){
						$content .= '<p><a href="' . $row["url1"] . '"><img class="banner_link" src="' . SITE_ROOT . BANNERIMGDIR . $row["filename"] . '" width="' . $row["width"] . '" height="' . $row["height"] . '"></a></p>';
					}
					if ($row["text"]){
						$content .= '<p class="sponsor_text">' . $row["text"] . '</p>';
					}
					$content .= '</div>';
				}
			}
		break;
		case "links_contributor":
			$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["name"]){
						$content .= $resultsection->title($row["name"]);
					}
					if ($row["title"]){
						$content .= $resultsection->text($row["title"]);
					}
					if ($row["location"]){
						$content .= $resultsection->text($row["location"]);
					}
					if ($row["email"]){
						$content .= $resultsection->email($row["email"]);
					}
					if ($row["url1"]){
						$content .= $resultsection->url($row["url1"], $row['url1_title']);
					}
					$content .= $resultsection->itemEnd();
				}
				$content .= $resultsection->sectionend();
			}
		break;
		case "links_county_recorder":
			$select = "SELECT * FROM $table WHERE `page_id` = (SELECT `page_id` FROM `pagename` WHERE `category` = '$category' AND `sub-category` = '$subcategory') ORDER BY id ASC";
			$result = mysql_query($select);
			$count = mysql_num_rows($result);
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["name"]){
						$content .= $resultsection->title($row["name"]);
					}
					if ($row["address"]){
						$content .= $resultsection->text($row["address"]);
					}
					if ($row["telephone"]){
						$content .= $resultsection->text($row["telephone"]);
					}
					if ($row["fax"]){
						$content .= $resultsection->text($row["fax"]);
					}
					if ($row["email"]){
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
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
					if ($row["url1"]) {
						$content .= $resultsection->url($row["url1"], $row['url1_title']);
					}
					if ($row["post_email"]){
						$linkdesc = 'To post to list: ';
						$content .= $resultsection->email($row["post_email"], $linkdesc);
					}
					if ($row["contact"]){
						$linkdesc = 'List contact: ';
						$content .= $resultsection->email($row["contact"], $linkdesc);
					}
					if ($row["sub_email"]){
						$linkdesc = 'To subscribe to list: ';
						$content .= $resultsection->email($row["sub_email"], $linkdesc);
					}
					if ($row["sub_message"]){
						$linkdesc = 'To unsubscribe: ';
						$content .= $resultsection->email($row["sub_message"], $linkdesc);
					}
					if ($row["body_message"]){
						$content .= $resultsection->text($row["body_message"]);
					}
					if ($row["text"]){
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
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
					if ($row["url1"]) {
						$content .= $resultsection->url($row["url1"], $row['url1_title']);
					}
					if ($row["url2"]){
						$content .= $resultsection->url($row["url2"], $row['url2_title']);
					}
					if ($row["text"]){
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
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				$content .= '<a class="content_link" href="http://www.nhbs.com/index.html?ad_id=5" target="_blank"><img src="/images/gen_title_nhbs.jpg" border="0" height="89" width="200"></a>';
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
					if ($row["text"]){
						$content .= $resultsection->text($row["text"]);
					}
					if ($row["isbn"]){
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
			if ($count>0){
				$resultsection = new SectionContent;
				$content .= $resultsection->sectionStart($sectiontitle, $count);
				while ($row = mysql_fetch_assoc($result)){
					$content .= $resultsection->itemStart();
					if ($row["title"]){
						$content .= $resultsection->title($row["title"]);
					}
					if ($row["text"]){
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
			if ($count>0){
				while ($row = mysql_fetch_assoc($result)){
					$content .= "<p>";
					if ($row["title"]){
						$content .= "<h2>".decode( $row["title"])."</h2>";
					}
					if ($row["text"]){
						$content .= decode($row["text"]);
					}
					$content .= "</p>";
				}
			}
		break;
		case "NEWS":
			if($category == "default_home"){
				$news = new Storydisplay();
				$content .= $news->getDisplaySite();
			}
		break;
		case "ARTICLE":
			if($category == "news" && $id != ""){
				$news = new Storydisplay();
				$content .= $news->getArticle($id);
			}
		break;
		case "ARCHIVE":
			if($category == "news" && $article_id == ""){
				$main = "news_article_main";
				$text = "news_article_text";
				$status = "news_article_status";
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
						$content .= "<br>".auto_link( encode($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
						$content .= "<br>Get the full story here...</a>";
						$content .= "<br>";
						$content .= "<font size=\"1\" color=\"#404040\">published: ".$row["created"]."</font>";
						$content .= "</p>";
					}
				}
			}
		break;
		case "REVIEW":
			if($category == "default_home"){
				$main = "reviews_article_main";
				$text = "reviews_article_text";
				$status = "reviews_article_status";
				$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
				$select .= "AND $status.state = 'visible' ORDER BY $main.id DESC LIMIT 0,4";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0){
					$resultsection = new SectionContent;
					$content .= $resultsection->sectionStart("Reviews");
					while ($row = mysql_fetch_assoc($result)){
						$content .= $resultsection->newsitemStart();
						if($row["image"]){
							$content .= $resultsection->newsIcon($row["image"], "reviews");
						}else{
						   $content .= $resultsection->newsIcon("reviews1.gif", "reviews");
						}
						$content .= $resultsection->newsItemSection($row["section"]);
						$content .= $resultsection->newsItemTitle("reviews", $row["id"], $row["title"]);
						$content .= $resultsection->newsItemPreview($row["summary"]);
						$content .= $resultsection->newsItemDate($row["article_timestamp"]);
						$content .= $resultsection->newsitemEnd();
					}
					$content .= $resultsection->newsArchiveLink("reviews");
					$content .= $resultsection->sectionend();
				}
			}
		break;
		case "REVIEW_ARTICLE":
			if($category == "reviews" && $article_id != ""){
					$main = "reviews_article_main";
					$text = "reviews_article_text";
					$status = "reviews_article_status";
					$select = "SELECT $main.*, $text.* FROM $main, $text WHERE $main.article = $text.article ";
					$select .= "AND $main.article = '$article_id'";
					$result = mysql_query($select);
					$count = mysql_num_rows($result);
				if ($count>0){
					while ($row = mysql_fetch_assoc($result)){
						$title = $row["title"];
						$created =  $row["created"];
					}
				}
				$content .= "<p><h3>".$title."</h3></p>";
				$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0){
					while ($row = mysql_fetch_assoc($result)){
						$content .= "<p>".decode($row["text"])."</p>";
					}
				}
				if ($created <> ""){
					$content .= "<p><i>Created: ".$created."</i></p>";
				}
				$content .= "<p>&nbsp;</p>";
					/*if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						$title = auto_link( encode($row["title"]));
						$desc_1 = auto_link( encode($row["desc_1"]));
						$desc_2 = auto_link( encode($row["desc_2"]));
						$desc_3 = auto_link( encode($row["desc_3"]));
						$created =  $row["created"];
						}
					}*/
					/*if ($title){
					 $content .= "<p class=\"major_title\">".$title."</p>";
					}
					if ($desc_1){
					 $content .= "<p class=\"major_title\"><b>".$desc_1."</b></p>";
					}
					if ($desc_2){
					 $content .= "<p class=\"major_title\"><b>".$desc_2."</b></p>";
					}
					if ($desc_3){
					 $content .= "<p class=\"major_title\"><b>".$desc_3."</b></p>";
					}*/
					/*$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);
					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						 //$content .= "<p>".auto_link($row["text"])."</p>";
						 $content .= "<p>".decode($row["text"])."</p>";
						}
					}
					 if ($created <> ""){
					 $content .= "<p><i>Created: ".$created."</i></p>";
					 }
					 $content .= "<p>&nbsp;</p>";*/
			}
		break;
		case "REVIEW_ARCHIVE":
			if($category == "reviews" && $article_id == ""){
					//set table name variables
					$main = "reviews_article_main";
					$text = "reviews_article_text";
					$status = "reviews_article_status";
					$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
					$select .= "AND $status.state = 'archived' ORDER BY $main.id DESC";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);
					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
					   $content .= "<p>";
					   $content .= "<font size=\"4\"><i>".review_title($row["section"])."</i></font>";
					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = str_replace(array("-", " ", ":"), "", $row["article_timestamp"]);
					  /*
					  echo "<br>old : ".$old_news_date;
					  echo "<br>current : ".$current_news_date;
					  echo "<br>time stamp : ".$row["timestamp"];
					  */
					  if (($article_date > $old_news_date) && ($current_news_date > $article_date)){
					  	$content .= " <img src=\"".ROOT_URL."/images/new_icon.gif";
						$content .= "\" border=\"0\">";
					  }
					  // end add a new icon to the news summary if it was pla ed in the last 14 days
 				   	  //update 12-10-2005
					   $content .= "<br><b>".auto_link( encode($row["title"]))."</b>";
					   $content .= "<br>".auto_link( encode($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
					   $content .= "<br>click here for the full fatbirder review....</a>";
					   $content .= "<br>";
					   $content .= "<font size=\"1\" color=\"#404040\">published:".$row["created"]."</font>";
					   $content .= "</p>";
						}
					  }
					 $content .= "<p><br></p>";
					 }
		break;
		case "ANNOUNCEMENT":
			if($category == "default_home"){
				$main = "announcements_article_main";
				$text = "announcements_article_text";
				$status = "announcements_article_status";
				$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
				$select .= "AND $status.state = 'visible' ORDER BY $main.id DESC LIMIT 0,4";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0){
					$resultsection = new SectionContent;
					$content .= $resultsection->sectionStart("Announcements");
					while ($row = mysql_fetch_assoc($result)){
						$content .= $resultsection->newsitemStart();
						if($row["image"]){
							$content .= $resultsection->newsIcon($row["image"], "announcements");
						}else{
						   $content .= $resultsection->newsIcon("announcements.jpg", "announcements");
						}
						$content .= $resultsection->newsItemTitle("announcements", $row["id"], $row["title"]);
						$content .= $resultsection->newsItemPreview($row["summary"]);
						$content .= $resultsection->newsItemDate($row["article_timestamp"]);
						$content .= $resultsection->newsitemEnd();
					}
					$content .= $resultsection->newsArchiveLink("announcements");
					$content .= $resultsection->sectionend();
				}
			}
		break;
		case "ARTICLE_ANNOUNCEMENT":
			if($category == "announcements" && $article_id == ""){
				$main = "announcements_article_main";
				$text = "announcements_article_text";
				$status = "announcements_article_status";
				$select = "SELECT $main.*, $text.* FROM $main, $text WHERE $main.article = $text.article ";
				$select .= "AND $main.article = '$article_id'";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0){
					while ($row = mysql_fetch_assoc($result)){
						$title = $row["title"];
						$created =  $row["created"];
					}
				}
				$content .= "<p><h3>".$title."</font></h3></p>";
				$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0){
					while ($row = mysql_fetch_assoc($result)){
						$content .= "<p>".auto_link($row["text"])."</p>";
					}
				}
				if ($created <> ""){
					$content .= "<p><i>Created: ".$created."</i></p>";
				}
			}
		break;
		case "ARCHIVE_ANNOUNCEMENT":
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
		break;
		case "LAST_UPDATED":
			//output the last time the site was updated as a string message date format e.g. tuesday, 12th March 2004
			$content .= "This site was last updated on ".date (LONG_DATE).".";
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
function auto_link($link, $title=null) {
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
function cons_resize($old_width,$old_height,$max_nuwidth,$max_nuheight){
		//set nu width and nu height in case they dont need changing
		$new_width = $old_width;
		$new_height = $old_height;
		//check for images that are too wide - resize proportionally to max width
		if ($old_width > $max_nuwidth)
		{
			// now we check for over-sized images and pare them down to the dimensions we need for display purposes
			$ratio = ( $old_width > $max_nuwidth ) ? (real)($max_nuwidth / $old_width) : 1 ;
			$new_width = ((int)($old_width * $ratio));    //full-size width
			$new_height = ((int)($old_height * $ratio));    //full-size height
		}
		//check for images that are still too high after width resize - resize proportionally to max height
		if ($new_height > $max_nuheight)
		{
			$ratio = ( $new_height > $max_nuheight ) ? (real)($max_nuheight / $new_height) : 1 ;
			$new_width = ((int)($new_width * $ratio));    //mid-size width
			$new_height = ((int)($new_height * $ratio));    //mid-size height
		}
		//echo "<br>ratio:$ratio new_width:$new_width new_height $new_height";
		//return new width/height constraints in an array
		return array ($new_width,$new_height);
	}//end function cons_resize
	//$HTTP_POST_FILES[$userfile]['name'], $HTTP_POST_FILES[$userfile]['tmp_name'], $HTTP_POST_FILES[$userfile]['type'], $HTTP_POST_FILES[$userfile]['size'], max_nuwidth, max_nuheight
	//add function to add a specific variation_id, for a specific cookie_id
	function upload_image ($name, $tmp_name, $type, $size, $max_nuwidth, $max_nuheight, &$filename, &$thumbnail, $filefolder="", $rndm=1)
	{
		//echo "image size - ".$size;
		if ($name<>"")
		{
			if ($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || $size == 0)
			{
					//filesize of temp uploaded image - kick out for files greater thatn  > 1 meg= 1024800 bytes / 1mb
					if ($size > 1024800 || $size == 0)
					{
						return "image is too big. Max File Size = 1mb ";
					}
					else
					{
						$imageInfo = getimagesize($tmp_name);
						$width = $imageInfo[0];
						$height = $imageInfo[1];
						//
						//check that a random number prefix has been requested in the argument list
						if ($rndm==1){
						$token = md5(uniqid(rand(),1));
						}
						//set thumbnail naming variable
						$thumbnail_prefix = "";
						$max_thumbwidth = "65";
						$max_thumbheight = "85";
						//$_files dont work so use older $HTTP_POST_FILES.
						if (is_uploaded_file($tmp_name))
						{
							  // if the thumbnail parameter has been set to 1 then
							  // run the upload image funciton twice
							  // once to upload the main image
							  // again to create a thumbnail image
							  // otherwise just loop through once
							  if ($thumbnail == 1){
							  $loop_limit = 3;
							  }
							  else
							  {
							  $loop_limit = 2;
							  }
							  for ($i = 1; $i < $loop_limit; $i++)
							  {
									if ($i > 1){
									//create the thumbnail image on the second loop through
									list ($nu_width,$nu_height) = cons_resize($width,$height,$max_thumbwidth,$max_thumbheight);
									//populate thumbnail naming variable
									$thumbnail_prefix = "thumb_";
									}
									else
									{
									//create the thumbnail image on the second loop through
									list ($nu_width,$nu_height) = cons_resize($width,$height,$max_nuwidth,$max_nuheight);
									//populate thumbnail naming variable
									}
									//echo "width = $width , height = $height , nuWidth = $nu_width , nuheight = $nu_height max_nuwdith = $max_nuwidth , max_nuheight = $max_nuheight";
								$dstbigImg = imagecreatetruecolor($nu_width,$nu_height);
								/*
								case "image/pjpeg"://---- all type jpeg code
								case 'image/jpeg':
								case 'image/jpg':
								case "pjpeg":
								case "jpeg":
								case "jpg":
								*/
								if ($type == "image/pjpeg")
								{
										/*no need to re-sample and create the JPEG
										//$srcImg = imagecreatefromjpeg($tmp_name);
										//imagecopyresampled ($dstbigImg, $srcImg, 0, 0, 0, 0, $nu_width, $nu_height, $width, $height);
										//if (!(imagejpeg($dstbigImg, "$filefolder/" . $thumbnail_prefix . $token . $name)))
										*/
										if(!copy($tmp_name, "$filefolder/" . $thumbnail_prefix . $token . $name))
										{
												return "error creating JPEG";
										}
										else
										{
											$filename =  $token . $name;
										}
								}
								else  // gif current PHP distrib of GDLIB does not support GIF so cant resize
								{
										if ($width > $max_nuwidth || $height > $max_nuheight)
										{
											$ret_upload_image = " Image is too large, w: $width , h: $height , max width = $max_nuwidth pixels, max height = $max_nuheight pixels";
										}
										else
										{
												if(!copy($tmp_name, "$filefolder/" . $thumbnail_prefix . $token . $name))
												{
													return "error uploading file to server.";
												}
												else
												{
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
				}// end type check
				else
				{
					return " is of wrong file type. Please use images of .gif or .jpg format. type = $type";
				}//end gif/jpeg check
	}//end name check
	else // no image to upload
	{
		return "no_image";
	}
}// end upload_image function

function review_title($section){
switch($section){
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
class Map{

	private $mapcode;
	private $mapname;
	private $imagemap; //True / False
	private $usemapname;

	public function __construct(){

	}

	public function getMap($mapname, $imagemap, $usemapname){
		$this->mapname = $mapname;
		$this->imagemap = $imagemap;
		$this->usemapname = $usemapname;
		$this->createMapCode();
		return $this->mapcode;
	}

	private function createMapCode(){
		if ($this->imagemap<>""){
			$this->mapcode = '<img usemap="#' . $this->usemapname . '" src="' . SITE_ROOT . MAP_DIR . $this->mapname . '" />';
		}else{
			$this->mapcode = '<img src="' . MAP_DIR . $this->mapname . '" />';
		}
	}

}

function gettitle($input_title, $input_category=""){
	$input_title = ereg_replace ('_', ' ', $input_title);
	$input_title = ereg_replace (' and ', ' &amp; ', $input_title);
	$formated_title .= strtoupper($input_title{0}).substr($input_title, 1);
	switch($formated_title){
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
	case "Index":
		if ($input_category == "trip_reports"){
		$formated_title = "Trip Reports";
		}
	break;
	case "Homepages":
		if ($input_category == "fun"){
		$formated_title = "Miscellany";
		}
	break;
	}
	//set the first letter of each word to upper case
	$formated_title = ucwords($formated_title);
return $formated_title;
}
//(name of file, directory, type variable dictates the string format that is returned
function getfile($target, $directory, $type){
	if(is_dir($directory)){
		$direc = opendir($directory);
		while(false !== ($file = readdir($direc))){
			if($file !="." && $file != ".."){
				if(is_file($directory."/".$file)){
					if(preg_match("/$target/i", $file)){
						switch ($type){
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
				}else if(is_dir($directory."/".$file)){
                  // search($target,$directory."/".$file, $type);
				}
			}
		}
		closedir($direc);
	}
}

/**
 *
 */
class Adverts {


	private $data;
	private $num;
	private $side;
	private $table;
	private $stmt;
	private $maxheight = 500;
	private $maxwidth = 130;
	private $minwidth = 125;
	private $minheight = 90;

	public function __construct($side){
		if($side == "left"){
			$this->side = "left" ;
			$this->table = "adverts_left";
		}else if($side == "right"){
			$this->side = "right";
			$this->table = "adverts_right";
		}
	}

	public function addButton(){
		$code .= '<form id="newad" action="" method="POST">';
		$code .= '<input type="hidden" name="action" value="add" />';
		$code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
		$code .= '<input type="submit" value = "Add new Ad" />';
		$code .= '</form>';
		return $code;
	}
	public function displayAds(){
		$this->getMaxOrder();
		$numrow = $this->stmt->rowCount();
		if($numrow > 0){
			$row = $this->stmt->fetch(PDO::FETCH_ASSOC);
			$max = $row['ad_order'];
			for($x = 1; $x<=$max; $x++){
				$this->getDataByOrder($x);
				$this->num = $this->stmt->rowCount();
				if($this->num >0){
					if($this->num > 1){
						$rndmax = $this->num-1;
						$rnd = rand(0, $rndmax);
						$row = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
						$code .= '<a href="' . decode($row[$rnd]["target_url"]) . '"><img class="adimage" src="'. SITE_ROOT . ADIMGDIR . decode($row[$rnd]['image']) . '" width = "' . $row[$rnd]['width'] . '" height = "' . $row[$rnd]['height'] . '" alt="" /></a>';
					}else{
						$row = $this->stmt->fetch(PDO::FETCH_ASSOC);
						$code .= '<a href="' . decode($row["target_url"]) . '"><img class="adimage" src="'. SITE_ROOT . ADIMGDIR . decode($row['image']) . '" width = "' . $row['width'] . '" height = "' . $row['height'] . '" alt="" /></a>';
					}
				}

			}
		}
		return $code;
	}

	public function displayAdmin(){
		$this->getData();
		$this->num = $this->stmt->rowCount();
		if($this->num > 0){
			while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)){
				$code .= '<div class="aditem">';
				$code .= '<image class="adimage" src="'. SITE_ROOT . ADIMGDIR . decode($row['image']) . '" width = "' . $row['width'] . '" height = "' . $row['height'] . '" alt="" />';
				$code .= '<form id="ad' . $row['id'] . '" action="" method="POST" enctype="multipart/form-data">';
				$code .= '<input type="hidden" name="action" value="update" />';
				$code .= '<input type="hidden" name = "adid" value = "' . $row['id'] . '" />';
				$code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
				$code .= '<input type="file" name="adpic" accept="image/*" value="Update image" />';
				$code .= '<p><label>Filename<input type="text" name="filename" value="' . decode($row["image"]) . '" readonly /></label</p>';
				$code .= '<p><label>Advert Width<input type="number" min="' . $this->minwidth . '" max="' . $maxwidth . '" name="adwidth" value="' . $row["width"] . '" /></label></p>';
				$code .= '<p><label>Advert Height<input type="number" min="' . $this->minheight . '" max="' . $maxheight . '" name="adheight" value="' . $row["height"] . '" /></label></p>';
				$code .= '<p><label>Link url<input type="url" name="target_url" value="' . decode($row["target_url"]) . '" /></label></p>';
				$code .= '<p><label>Advert order<input type="number" min="1" name="ad_order" value="' . $row["ad_order"] . '" /></label></p>';
				$code .= '<p><label>#id<input type="text" name="css_id" value="' . decode($row["css_id"]) . '" /></label></p>';
				$code .= '<p><label>.class<input type="text" name="css_class" value="' . decode($row["css_class"]) . '" /></label></p>';
				$code .= '<p><input type="submit" value="Update" /></p>';
				$code .= '</form>';
				$code .= '<form id="delad" action="" method ="POST">';
				$code .= '<input type="hidden" name="action" value="del" />';
				$code .= '<input type="hidden" name = "adid" value = "' . $row['id'] . '" />';
				$code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
				$code .= '<input type="submit" value = "Delete this Ad" />';
				$code .= '</form></div>';
			}
		}
		return $code;
	}

	public function newAd(){
		$code .= '<div class="aditem">';
		$code .= '<form id="adnew" action="" method="POST" enctype="multipart/form-data">';
		$code .= '<input type="hidden" name="action" value="addnewad" />';
		$code .= '<input type="hidden" name="side" value="' . $this->side . '" />';
		$code .= '<input type="file" name="adpic" accept="image/*" />';
		$code .= '<p><label>Advert Width<input type="number" min="' . $this->minwidth . '" max="' . $maxwidth . '" name="adwidth" value="" required /></label></p>';
		$code .= '<p><label>Advert Height<input type="number" min="' . $this->minheight . '" max="' . $maxheight . '" name="adheight" value="" required /></label></p>';
		$code .= '<p><label>Link url<input type="url" name="target_url" value="http://" required /></label></p>';
		$code .= '<p><label>Advert order<input type="number" min="1" name="ad_order" value="" required /></label></p>';
		$code .= '<p><label>#id<input type="text" name="css_id" value="" /></label></p>';
		$code .= '<p><label>.class<input type="text" name="css_class" value="" /></label></p>';
		$code .= '<p><input type="submit" value="Submit" /></p>';
		$code .= '</form></div>';
		return $code;
	}

	public function updateAd($id){
		//upload class, validation,etc
		if($_POST['filename'] <> ""){
			$imageuploaded=0;
			$filename = encode(strip_tags($_POST['filename']));
		}
		$imagewidth = preg_replace("/[^0-9]/", "", $_POST['adwidth']);
		$imageheight = preg_replace("/[^0-9]/", "", $_POST['adheight']);
		$targeturl = encode(strip_tags($_POST['target_url']));
		$adorder = preg_replace("/[^0-9]/", "", $_POST['ad_order']);
		$cssid = encode(strip_tags($_POST['css_id']));
		$cssclass = encode(strip_tags($_POST['css_class']));
		$handle = new upload($_FILES['adpic']);
		if ($handle->uploaded){
			$name = strip_tags($handle->file_src_name_body);
			$handle->image_resize = true;
			$handle->image_x = $imagewidth;
			$handle->image_y = $imageheight;
			$handle->image_ratio = true;
			$handle->file_name_body_add = time();
			$handle->process(ROOT_DIR . ADIMGDIR);
			if ($handle->processed) {
				//get width and height after process to save to db
				$newwidth = $handle->image_dst_x;
				$newheight = $handle->image_dst_y;
				$newname = encode($handle->file_dst_name);
				$imageuploaded = 1;
				$handle->clean();
			} else {
				echo 'error : ' . $handle->error;
			}
		}
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		if(imageuploaded){
			$sth = $db->prepare('UPDATE ' . $this->table . '
				SET image=?, width=?, height=?, target_url=?, ad_order=?, css_id=?, css_class=?
    			WHERE id = ?');
			$sth->execute(array($newname, $newwidth, $newheight, $targeturl, $adorder, $cssid, $cssclass,  $_POST['adid']));
		}else{
			$sth = $db->prepare('UPDATE ' . $this->table . '
				SET image=?, width=?, height=?, target_url=?, ad_order=?, css_id=?, css_class=?
	    		WHERE id = ?');
			$sth->execute(array($filename, $imagewidth, $imageheight, $targeturl, $adorder, $cssid, $cssclass,  $_POST['adid']));
		}
	}

	public function addNewAd(){
		$imageuploaded = 0;
		$imagewidth = preg_replace("/[^0-9]/", "", $_POST['adwidth']);
		$imageheight = preg_replace("/[^0-9]/", "", $_POST['adheight']);
		$targeturl = encode(strip_tags($_POST['target_url']));
		$adorder = preg_replace("/[^0-9]/", "", $_POST['ad_order']);
		$cssid = encode(strip_tags($_POST['css_id']));
		$cssclass = encode(strip_tags($_POST['css_class']));
		$handle = new upload($_FILES['adpic']);
		if ($handle->uploaded){
			$name = strip_tags($handle->file_src_name_body);
			$handle->image_resize = true;
			$handle->image_x = $imagewidth;
			$handle->image_y = $imageheight;
			$handle->image_ratio = true;
			$handle->file_name_body_add = time();
			$handle->process(ROOT_DIR . ADIMGDIR);
			if ($handle->processed) {
				//get width and height after process to save to db
				$newwidth = $handle->image_dst_x;
				$newheight = $handle->image_dst_y;
				$newname = encode($handle->file_dst_name);
				$imageuploaded = 1;
				$handle->clean();
			} else {
				echo 'error : ' . $handle->error;
			}
		}
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$query = 'INSERT INTO ' . $this->table . ' (image, width, height, target_url, ad_order, css_id, css_class) VALUES (:image, :width, :height, :targeturl, :adorder, :cssid, :cssclass)';
		$sth = $db->prepare($query);
		$sth->execute(array(':image'=>$newname, ':width'=>$newwidth, ':height'=>$newheight, ':targeturl'=>$targeturl, ':adorder'=>$adorder, ':cssid'=>$cssid, ':cssclass'=>$cssclass));
	}

	public function deleteAd($id){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$query = 'DELETE FROM ' . $this->table . ' WHERE id=?';
		$sth = $db->prepare($query);
		$sth->execute(array($id));
	}

	private function getData($id=0){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$sql = 'SELECT * FROM ' . $this->table;
		if($id>0){
			$sql .= ' WHERE id = ?';
		}
		$sql .= ' ORDER BY ad_order ASC';
		$this->stmt = $db->prepare($sql);
		if($id>0){
			$this->stmt->execute($id);
		}else{
			$this->stmt->execute();
		}
		$dbcon = null;
	}

	private function getDataByOrder($order){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ad_order = ?';
		$this->stmt = $db->prepare($sql);
		if($order>0){
			$param = array($order);
			$this->stmt->execute($param);
		}
		$dbcon = null;
	}

	private function getMaxOrder(){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$sql = 'SELECT MAX(ad_order) as ad_order FROM ' . $this->table;
		$this->stmt = $db->prepare($sql);
		$this->stmt->execute();
		$dbcon = null;
	}
}


/**
 *
 */
class SectionContent extends ConvertInput{

	function __construct() {

	}

	public function intro($text, $defhome){
		if($defhome == "default_home"){
			$sectioncontent .= $this->decode($text);
		}else{
			$sectioncontent .= '<p class="text">' . $this->decode($text) . '</p>';
		}
		return $sectioncontent;
	}

	public function sectionStart($sectitle, $count=0){
		$sectioncontent = '<section class="resultbox">
							<div class="resulttitle">
								<h2>' . $sectitle . '</h2>
							</div>
							<div class="resultarea';
		if($count >BOX_SCROLL_LIMIT){
			$sectioncontent .= ' scrollable';
		}
		$sectioncontent .= '">';
		return $sectioncontent;
	}
	/*---------------------------------------------------------*/

	public function newsitemStart(){
		$sectioncontent .= '<div class="newsitem clearfix">';
		return $sectioncontent;
	}

	public function newsIcon($img, $dir){
		$sectioncontent .= '<div class="newsitemicon">';
		switch($dir){
			case "announcements":
				$icondir = ANNOUNCEMENTS_ICON_DIR;
			break;
			case "news":
				$icondir = NEWS_ICON_DIR;
			break;
			case "reviews":
				$icondir = REVIEWS_ICON_DIR;
			break;
		}
		$sectioncontent .= '<img src="' . SITE_ROOT . $icondir .  $img;
		$sectioncontent .= '" width="90" height="90" alt=""/>';
		$sectioncontent .='</div>';
		return $sectioncontent;
	}
	public function newsItemTextStart(){
		$sectioncontent .= '<div class="newsitemtext">';
		return $sectioncontent;
	}

	public function newsItemSection($section){
		$sectioncontent .= '<p class="newsitemtype">' . $this->decode($section) . '</p>';
		return $sectioncontent;
	}

	public function newsItemTitle($dir, $id, $title){
		$sectioncontent .= '<h3 class ="newsitemtitle"><a href="' . $dir . '/index.php?article=' . $id . '">' . $this->decode($title) . "</a></h3>";
		return $sectioncontent;
	}
	public function newsItemSummary($summ){
		$sectioncontent .= '<p class="newsitempreview">' . $this->decode($summ) . '</p>';
		return $sectioncontent;
	}
	public function newsItemDate($newstime){
		$sectioncontent .= '<p class="newsitemdate">' . $this->convertTimestampToDate($newstime) . '</p>';
		return $sectioncontent;
	}
	public function newsitemEnd(){
		$sectioncontent .= '</div>';
		return $sectioncontent;
	}

	public function newsArchiveLink($dir){
		$sectioncontent = '<p class="morelink"><a href="' . $dir . '/archive.php">More stories are available in the ' . ucfirst($dir) . ' archive...</a></p>';
		return $sectioncontent;
	}

	public function articleHead($title, $subhead1, $subhead2, $subhead3){
		$sectioncontent = '<h2>' . $this->decode($title) . '</h2>';
		$sectioncontent .= '<p class="articlesubhead">' . $this->decode($subhead1) . '</p>';
		$sectioncontent .= '<p class="articlesubhead">' . $this->decode($subhead2) . '</p>';
		$sectioncontent .= '<p class="articlesubhead">' . $this->decode($subhead3) . '</p>';
		return $sectioncontent;
	}

	public function articleBody($text){
		$sectioncontent = $this->decode($text);
		return $sectioncontent;
	}

	public function articleEnd($created){
		$sectioncontent = '<p class="articlecreated">' . $this->convertTimestampToDate($created) . '</p>';
		return $sectioncontent;
	}
	/*---------------------------------------------------*/

	public function itemStart(){
		$sectioncontent = '<div class="resultarea_item">';
		return $sectioncontent;
	}

	public function itemEnd(){
		$sectioncontent = '</div>';
		return $sectioncontent;
	}

	public function title($titletext){
		$sectionoutput = '<p class="resultarea_item_title">' . $this->decode($titletext) . '</p>';
		return $sectionoutput;
	}

	public function endemicsTitle($entitle){
		$sectioncontent .= '<p><span class="bold">Number of endemics: ' . $this->decode($entitle) . '</p>';
		return $sectioncontent;
	}

	public function speciesTitle($sptitle){
		$sectioncontent .= '<p><span class="bold">Number of bird species: ' . $this->decode($sptitle) . '</p>';
		return $sectioncontent;
	}

	public function url($link, $linktext=""){
		$link = $this->decode($link);
		if($linktext == ""){
			$linktext = $link;
		}else{
			$linktext = $this->decode($linktext);
		}
		$sectionoutput = '<p class="resultarea_item_url">
									<a href="' . $link . '">' . $linktext . '</a>
								</p>';
		return $sectionoutput;
	}

	public function email($link, $linkdesc=""){
		$link = $this->decode($link);
		$sectionoutput = '<p class="resultarea_item_url">';
		if($linkdesc <> ""){
			$sectionoutput .= '<span class="bold">' . $linkdesc . '</span>';
		}
		$sectionoutput .= '<a href="mailto:' . $link . '">' . $link . '</a></p>';
		return $sectionoutput;
	}

	public function text($anytext){
		$sectionoutput = '<p class="resultarea_item_text">' . $this->decode($anytext) . '</p>';
		return $sectionoutput;
	}

	public function sectionend(){
		$sectionoutput = '</div>
						</section>';
		return $sectionoutput;
	}
	public function tripReportDefaultLink(){
		$sectioncontent = $this->title("CloudBirders");
		$sectioncontent .= $this->url("http://www.cloudbirders.com","Trip Report Repository");
		$sectioncontent .= $this->text("CloudBirders was created by a group of Belgian world birding enthusiasts and went live on 21st of March 2013. They provide a large and growing database of birding trip reports, complemented with extensive search, voting and statistical features.");
		return $sectioncontent;
	}

	public function sectionSponsoredByBirdersTravel(){
		$sectioncontent = '<div class="content_link"><p>This section sponsored by: <a class="content_link" href="http://www.birderstravel.com" target="_blank"><img src="/images/sponsorbirderstravel.jpg" width="200" height="60"></a></p></div>';
		return $sectioncontent;
	}

	public function isbn($num){
		$sectioncontent = '<p class="resultarea_item_text">ISBN: ' . $num . '</p>';
		$sectioncontent .= '<a href="http://www.nhbs.com/book_isbn_' . $num . '_ca_5.html" target="_blank">Buy this book from NHBS.com</a>';
		return $sectioncontent;
	}
}

/**
 *
 */
class Crud{

	private $db;

	public function __construct(){
	}

	public function retrieve($query){
		$dbcon = new Dbconnection();
		$db = $dbcon->connect();
		$stmt = $db->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num > 0){
			return $stmt;
		}else{
			return FALSE;
		}
	}

	public function update(){

	}

	public function delete(){

	}

	public function __destruct(){
		$this->db = null;
	}

}

/**
 *
 */
class Dbconnection {

	private $host = "localhost";
	private $db_name = "fbiwadm_newsite";
	private $username = "fbiwadm_fbmysql";
	private $password = "X5h7W23g";

	public function __construct(){
	}

	public function connect(){
		try{
			$con = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
			return $con;
		}
		catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}
	}
}

/**
 *
 */
class Photo{

	private $photocode;

	public function getphoto($data){
		$this->createCode($data);
		return $this->photocode;
	}

	private function createCode($data){
		$this->photocode .= '<figure class="photo">';
      if ($data["filename"]){
        // $this->photocode .= '<img src="' . ROOT_DIR . '/photos/' . decode($data["filename"]) . '" />';
		  $this->photocode .= '<img src="/photos/' . decode($data['filename']) . '" height="' . $data['height'] . '" width = "' . $data['width'] . '" alt="' . $data['alt'] . '" />';
      }
      if (!$data["text"]){
         $this->photocode .= '<figcaption style="width:' . $data['width'] . 'px;">';
			if($data['common_name']){
				$this->photocode .= '<span class="bold">' . decode($data['common_name']) . '</span>';
			}
			if($data['sci_name']){
				$this->photocode .=' <span class="latinname">' . decode($data['sci_name']) . '</span>';
			}
			if($data['credit']){
				$this->photocode .= ' ' . decode("&#169") . decode($data['credit']);
			}
			if($data['url']){
				$this->photocode .= ' <a href="' . decode($data['url']) . '">Website</a>';
			}
			$this->photocode .= '</figcaption>';
      }else{
      	$this->photocode .= '<figcaption style="width:' . $data['width'] . 'px;">';
      	$this->photocode .= decode($data['text']);
			$this->photocode .= '</figcaption>';
      }
      $this->photocode .= "</figure>";
	}
}

class email{

	private $mailto = "bo@fatbirder.com";
	private $mail_subject = "Feedback from fatbirder.com";
	private $mail_body = "";
	private $mail_footer = "fatbirder.com";
	private $name = "";
	private $email = "";
	private $feedback = "";
	private $country;

	public function __construct($post){
		if($post['feedback_name']){
			$this->name = strip_tags(stripslashes($post['feedback_name']));
		}
		$this->email = strip_tags(stripslashes($post['feedback_email']));
		$this->feedback = strip_tags(stripslashes($post['feedback']));
		$this->country = $post['country'];
	}

	public function send(){
		if($this->country == ""){
			$this->mail_body = '<h3>Fatbirder Feedback Form</h3>';
			$this->mail_body .= '<p>From: ' . $this->name . '</p>';
			$this->mail_body .= '<p>Email: ' . $this->email . '</p>';
			$this->mail_body .= '<p>Message:<br>';
			$this->mail_body .= $this->feedback . '</p>';
			$mail = new PHPMailer();
			$mail->isHTML(true);
			$mail->From = $this->email;
			if($this->name != ""){
				$mail->FromName = $this->name;
			}else{
				$mail->FromName = "Feedback form";
			}
			$mail->Subject = $this->mail_subject;
			$mail->Body = $this->mail_body;
			$mail->AltBody = strip_tags($this->mail_body);
			$mail->AddAddress($this->mailto);
			if(!$mail->send()) {
				return $mail->ErrorInfo;
			} else {
				$mess = 'Thank you for completing the feedback form, if you have asked a question Fatbirder will reply to just as soon as possible but bear in mind that a lot of questions are posed so the reply may not be instant. If you made a comment that needs no reply be assured it will be read and is appreciated. If you were just saying "hi", or making some nice comment, thanks and good birding.';
				return $mess;
			}
		}
	}
}
?>