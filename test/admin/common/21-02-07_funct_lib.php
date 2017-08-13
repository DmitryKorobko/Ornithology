<?
	// db settings

	/*//remote*/
//	define ("DB_HOST", "localhost");
//	define ("DB", "fatbirder_net_-_fatbirder");
//	define ("USER", "fatbirder");
//	define ("PASS", "dO9BhD5F");
//	define ("ROOT_URL", "http://69.94.83.22");


	define ("DB_HOST", "ldb502.securepod.com");
	define ("DB", "fatbirder_net");
	define ("USER", "fbmysql");
	define ("PASS", "X5h7W23g");
	define ("ROOT_URL", "http://www.fatbirder.com");


	/*
	//local
	define ("DB_HOST", "localhost");
	define ("DB", "fatbirder");
	define ("USER", "root");
	define ("PASS", "bishbosh");
	define ("ROOT_URL", "http://localhost:8080/fatbirder");
	*/

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
	define ("LINKS_CONTRIBUTOR_FIELDS", "(`name`, `title`, `location`, `email`, `url`, `sub-category`, `category`)");
	define ("LINKS_RECORDER_FIELDS", "(`name`, `address`, `telephone`, `fax`, `email`, `sub-category`, `category` )");
	define ("LINKS_SPECIES_FIELDS", "(`title`, `text`, `sub-category`, `category`)");
	define ("LINKS_LINKS_FIELDS", "(`title`, `url`, `link_desc`, `sub-category`, `category`)");
	define ("LINKS_MAILING_FIELDS", "(`title`, `url`, `post_email`, `contact`, `sub_email`, `unsub_email`, `sub_message`, `body_message`, `text`, `sub-category`, `category`)");
	define ("LINKS_PHOTO_FIELDS", "(`country`, `filename`, `width`, `height`, `text`)");
	define ("LINKS_TOPSITES_FIELDS", "(`title`, `grid_reference`, `text`, `sub-category`, `category`)");
	define ("LINKS_USEFUL_FIELDS", "(`title`, `text`, `isbn`, `sub-category`, `category`)");
	define ("LINKS_USEFULINFO_FIELDS", "(`title`, `text`, `sub-category`, `category`)");
	define ("LINKS_MAP_FIELDS", "(`imagemap`, `regionlist`, `sub-category`, `category`)");
	define ("LINKS_BANNERS_FIELDS", "(`filename`, `url`, `sponsor`, `text`, `width`, `height`, `sub-category`, `category`)");
	define ("LINKS_SPONSOR_FIELDS", "(`title`, `url`, `link_desc`, `filename`, `section`)");
	define ("SUBLINKS", "(`sublinks`, `sub-category`, `category`)");

	//define FTP variables
	define ("chmod_ip", "66.232.130.161");
	define ("chmod_login", "fatbirder");
	define ("chmod_pass", "Se5k8Mn9");
	define ("chmod_file", "/home/www/fatbirder/");



	// global vars
	function session_authenticated()
	{
		session_start();

		if (session_is_registered("user_auth") && ($_SESSION ['user_auth'] == "user"))
			return true;
		else
			return false;
	}


	function connect_db()
	{
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


	function ftp_chmod($ip, $login, $pass, $file, $chmod_mode){
	$conn_id=ftp_connect($ip);
	$login_result=@ftp_login($conn_id, $login, $pass);

	$chmod_cmd="CHMOD ".$chmod_mode." ".$file;
	$chmod=ftp_site($conn_id, $chmod_cmd);

	if ($chmod == 1){
		//echo "ftp chmod of '$file' changed to $chmod_mode<br>";
	}else{
		echo "failed to change chmod of '$file' to $chmod_mode<br>";
	}
	ftp_quit($conn_id);
	}


	function createHTML($tmpl_dir="", $tmpl_name, $html_dir="./", $html_name, $category, $subcategory, $stream=0, $article_id="")
	{
	//perform quick variable validation loops
	if($tmpl_name == ""){
	$ConfMsg = "<LI>No template name was provided";
	return $ConfMsg;
	}else if ($html_name == ""){
	$ConfMsg = "<LI>No html filename was provided";
	return $ConfMsg;
	}else{

	#get the correct root directory from the scripts physical location
	// this rootdir lookup is wrong in hostway enviroment - they use a cgi php install - for F..ks sake!
//	$rootdir = substr_replace($_SERVER["SCRIPT_FILENAME"], '', strrpos($_SERVER["SCRIPT_FILENAME"], "/"));
// instead rely upon absolute path of template dir being submitted with a trailing /
	$rootdir = "";
//	echo "<br>script filename ".$_SERVER["SCRIPT_FILENAME"]."<br>";
//	echo "<br>rootfir $rootdir<br>";
//	$template_dir = $rootdir."/".$tmpl_dir;
	$template_name = $tmpl_name;
	$template_path = $template_dir.$template_name;
//	echo "template path ".$template_path;

	//open the template file and place a handle on it
	$template_handle = @fopen($template_path,"r");
	if(!$template_handle) {

				$ConfMsg = "<LI><B>$fileURL</B> not found on server";

	}else {

				/*********************************************
				************ meta tag variables *************/
				$fileDesc = "New Template";
				$fileMetaDesc = "New template description";
				$fileMetaKey = "New template keywords";
				/*********************************************
				************ end tag variables **************/


				/*********************************************
				********* UPDATE METATAGS IF REQUIRED *******/

				while(!feof($template_handle)) {
				$buffer = fgets($template_handle,4096);
					//Replace Title and Meta Tags useing case insensitive eregi function
					//if(eregi("<title",$buffer)) {
					//$text .= eregi_replace("title>.*</title>","<title>" . $fileDesc . "</title>\n",$buffer);
					//}
					//elseif(eregi("META name=\"description\"",$buffer)) {
					//$text .= eregi_replace("<META name=\"description\" CONTENT=.+","<META name=\"description\" CONTENT=\"" . $fileMetaDesc . "\">\n",$buffer);
					//}
					//elseif(eregi("META name=\"keywords\"",$buffer)) {
					//$text .= eregi_replace("<META name=\"keywords\" CONTENT=.+","<META name=\"keywords\" CONTENT=\"" . $fileMetaKey . "\">\n",$buffer);
					//}
					//else {
					$text .= $buffer;
					//}

				}

				/*********************************************
				************* END UPDATE METATAGS  **********/

				/*********************************************
				********* DEAL WITH TEMPLATE TOKENS *********/
				$input_string = $text;
				$output_string = "";
				$tok = strtok($input_string,"[]");
				$sql = $tmpl_sql;
				while ($tok) {
					//echo "Word=$tok<br>";
					//$output_string .= $tok;

					//then perform an action based on the flag within the tokened area
					switch ($tok){
					case "TITLE":
						//echo $tok;
						//$table = "map";
						$string = gettitle($subcategory, $category);
						$output_string .= html_entity_decode($string);
					break;
					case "PHOTO":
						$table = "photos";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "INTRO":
						//echo $tok;
						$table = "links_introduction";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "SUBLINKS":
						//echo $tok;
						$table = "sublinks";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "FAMILY_LINKS":
						//echo $tok;
						$table = "links_family_links";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "SPECIES_LINKS":
						//echo $tok;
						$table = "links_species_links";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "BANNER":
						//echo $tok;
						$table = "links_banners";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "TOPSITES":
						$table = "links_top_sites";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$topsitescheck = 1;
						}
					break;
					case "CONTRIBUTOR":
						//echo $tok;
						$table = "links_contributor";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$contributorcheck = 1;
						}
					break;
					case "RECORDER":
						//echo $tok;
						$table = "links_county_recorder";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$recordercheck = 1;
						}
					break;
					case "SPECIES":
						//echo $tok;
						$table = "links_number_species";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$speciescheck = 1;
						}
					break;
					case "ENDEMICS":
						//echo $tok;
						$table = "links_endemics";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$endemicscheck = 1;
						}
					break;
					case "READING":
						//echo $tok;
						$table = "links_useful_reading";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$readingcheck = 1;
						}
					break;
					case "USEFUL":
						//echo $tok;
						$table = "links_useful_information";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$usefulcheck = 1;
						}
					break;
					case "ORGANISATIONS":
						//echo $tok;
						$table = "links_organisations";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$organisationscheck = 1;
						}
					break;
					case "FESTIVALS":
						//echo $tok;
						$table = "links_festivals";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$festivalscheck = 1;
						}
					break;
					case "OBSERVATORIES":
						//echo $tok;
						$table = "links_observatories";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$observatoriescheck = 1;
						}
					break;
					case "MUSEUMS":
						//echo $tok;
						$table = "links_museums";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$museumscheck = 1;
						}
					break;
					case "RESERVES":
						//echo $tok;
						$table = "links_reserves";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$reservescheck = 1;
						}
					break;
					case "TRIPS":
						//echo $tok;
						$table = "links_trip_reports";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$tripscheck = 1;
						}
					break;
					case "GUIDES":
						//echo $tok;
						$table = "links_holiday_companies";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$guidescheck = 1;
						}
					break;
					case "PLACES":
						//echo $tok;
						$table = "links_places_to_stay";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$placescheck = 1;
						}
					break;
					case "MAILING":
						//echo $tok;
						$table = "links_mailing_lists";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$mailingcheck = 1;
						}
					break;
					case "LINKS":
						//echo $tok;
						$table = "links";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$linkscheck = 1;
						}
					break;
					case "ARTISTS":
						//echo $tok;
						$table = "links_artists_photographers";
						$string = getcontent($table, $category, $subcategory);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$artistscheck = 1;
						}
					break;
					//generic site wide last updated token
					//home page tokens
					case "LAST_UPDATED":
						$table = "LAST_UPDATED";
						$string = getcontent($table, "", "");
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$lastupdatedcheck = 1;
						}
					break;
					//home page tokens
					case "NEWS":
						$table = "NEWS";
						$string = getcontent($table, "", "");
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$newscheck = 1;
						}
					break;
					case "REVIEW":
						$table = "REVIEW";
						$string =getcontent($table, "", "");
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$reviewcheck = 1;
						}
					break;
					case "ARTICLE":
						$table = "ARTICLE";
						$string =getcontent($table, "", "", $article_id);
						$output_string .= html_entity_decode($string);
						//echo $article_id;
					break;
					case "ARCHIVE":
						$table = "ARCHIVE";
						$string =getcontent($table, "", "");
						$output_string .= html_entity_decode($string);
						//echo $article_id;
					break;
					case "ANNOUNCEMENT":
						$table = "ANNOUNCEMENT";
						$string =getcontent($table, "", "", $article_id);
						if ($string <> ""){
						$output_string .= html_entity_decode($string);
						$announcementcheck = 1;
						}
						//echo $article_id;
					break;
					case "ARTICLE_ANNOUNCEMENT":
						$table = "ARTICLE_ANNOUNCEMENT";
						$string =getcontent($table, "", "", $article_id);
						$output_string .= html_entity_decode($string);
						//echo $article_id;
					break;
					case "ARCHIVE_ANNOUNCEMENT":
						$table = "ARCHIVE_ANNOUNCEMENT";
						$string =getcontent($table, "", "", $article_id);
						$output_string .= html_entity_decode($string);
						//echo $article_id;
					break;
					case "REVIEW_ARTICLE":
						$table = "REVIEW_ARTICLE";
						$string =getcontent($table, "", "", $article_id);
						$output_string .= html_entity_decode($string);
					break;
					case "REVIEW_ARCHIVE":
						$table = "REVIEW_ARCHIVE";
						$string =getcontent($table, "", "");
						$output_string .= html_entity_decode($string);
						//echo $article_id;
					break;
					case "DEFAULT_HOME":
						//echo $tok;
						$table = "links_introduction";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "DEFAULT_ABOUT":
						//echo $tok;
						$table = "links_introduction";
						$string = getcontent($table, $category, $subcategory);
						$output_string .=  html_entity_decode($string);;
					break;
					case "SITE_MAP":
						//echo $tok;
						$table = "site_map";
						$string = getcontent($table, $category, $subcategory);
						$output_string .=  html_entity_decode($string);;
					break;
					case "MAP":
						//echo $tok;
						$table = "map";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					case "SUBLINKS":
						//echo $tok;
						$table = "sublinks";
						$string = getcontent($table, $category, $subcategory);
						$output_string .= html_entity_decode($string);
					break;
					default:
						$output_string .= $tok;
					}
					$tok = strtok("[]");
				}
				/*********************************************
				******* END DEAL WITH TEMPLATE TOKENS *******/

				/*********************************************
				******* DISPLAY SECTION HEAD FOOT ***********/
				//using the check vairables only output a
				//section header or footer if content is present
				//if the content is not present then remowe the header / footer html

				preg_match_all ("/<!-- fatbirder::([^:]+?)::start -->(.*?)<!-- fatbirder::\\1::end -->/s", $output_string, $matches);

				for ($i=0; $i< count($matches[0]); $i++) {
				  //echo "matched: ".$matches[0][$i]."\n";
				  //echo "part 1: ".$matches[1][$i]."\n";
				  switch($matches[1][$i]){
				  case "NEWSHEADER":
					$header = $matches[0][$i];
					if ($newscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "NEWSFOOTER":
					$footer = $matches[0][$i];
					if ($newscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "LAST_UPDATEDHEADER":
					$header = $matches[0][$i];
					if ($lastupdatedcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "LAST_UPDATEDFOOTER":
					$footer = $matches[0][$i];
					if ($lastupdatedcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "REVIEWHEADER":
					$header = $matches[0][$i];
					if ($reviewcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "REVIEWFOOTER":
					$footer = $matches[0][$i];
					if ($reviewcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "ANNOUNCEMENTHEADER":
					$header = $matches[0][$i];
					if ($announcementcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "ANNOUNCEMENTFOOTER":
					$footer = $matches[0][$i];
					if ($announcementcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "TOPSITESHEADER":
					$header = $matches[0][$i];
					if ($topsitescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "TOPSITESFOOTER":
					$footer = $matches[0][$i];
					if ($topsitescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "CONTRIBUTORHEADER":
					$header = $matches[0][$i];
					if ($contributorcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "CONTRIBUTORFOOTER":
					$footer = $matches[0][$i];
					if ($contributorcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "RECORDERHEADER":
					$header = $matches[0][$i];
					if ($recordercheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "RECORDERFOOTER":
					$footer = $matches[0][$i];
					if ($recordercheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "SPECIESHEADER":
					$header = $matches[0][$i];
					if ($speciescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "SPECIESFOOTER":
					$footer = $matches[0][$i];
					if ($speciescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
 				  case "ENDEMICSHEADER":
					$header = $matches[0][$i];
					if ($endemicscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "ENDEMICSFOOTER":
					$footer = $matches[0][$i];
					if ($endemicscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "READINGHEADER":
					$header = $matches[0][$i];
					if ($readingcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "READINGFOOTER":
					$footer = $matches[0][$i];
					if ($readingcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "USEFULHEADER":
					$header = $matches[0][$i];
					if ($usefulcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "USEFULFOOTER":
					$footer = $matches[0][$i];
					if ($usefulcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "ORGANISATIONSHEADER":
					$header = $matches[0][$i];
					if ($organisationscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "ORGANISATIONSFOOTER":
					$footer = $matches[0][$i];
					if ($organisationscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "FESTIVALSHEADER":
					$header = $matches[0][$i];
					if ($festivalscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "FESTIVALSFOOTER":
					$footer = $matches[0][$i];
					if ($festivalscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "OBSERVATORIESHEADER":
					$header = $matches[0][$i];
					if ($observatoriescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "OBSERVATORIESFOOTER":
					$footer = $matches[0][$i];
					if ($observatoriescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "MUSEUMSHEADER":
					$header = $matches[0][$i];
					if ($museumscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "MUSEUMSFOOTER":
					$footer = $matches[0][$i];
					if ($museumscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "RESERVESHEADER":
					$header = $matches[0][$i];
					if ($reservescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "RESERVESFOOTER":
					$footer = $matches[0][$i];
					if ($reservescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "TRIPSHEADER":
					$header = $matches[0][$i];
					if ($tripscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "TRIPSFOOTER":
					$footer = $matches[0][$i];
					if ($tripscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "GUIDESHEADER":
					$header = $matches[0][$i];
					if ($guidescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "GUIDESFOOTER":
					$footer = $matches[0][$i];
					if ($guidescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "PLACESHEADER":
					$header = $matches[0][$i];
					if ($placescheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "PLACESFOOTER":
					$footer = $matches[0][$i];
					if ($placescheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "MAILINGHEADER":
					$header = $matches[0][$i];
					if ($mailingcheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "MAILINGFOOTER":
					$footer = $matches[0][$i];
					if ($mailingcheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "LINKSHEADER":
					$header = $matches[0][$i];
					if ($linkscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "LINKSFOOTER":
					$footer = $matches[0][$i];
					if ($linkscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;
				  case "ARTISTSHEADER":
					$header = $matches[0][$i];
					if ($artistscheck <> 1){
					$output_string = str_replace($header, "", $output_string);
					}
				  break;
				  case "ARTISTSFOOTER":
					$footer = $matches[0][$i];
					if ($artistscheck <> 1){
					$output_string = str_replace($footer, "", $output_string);
					}
				  break;


				  }
				  //echo "part 2: ".$matches[3][$i]."\n";
				  //echo "part 3: ".$matches[4][$i]."\n\n";
				}
				//echo $newsfooter;

				/*********************************************
				******* DISPLAY SECTION HEAD FOOT ***********/


			   /*********************************************
				************* CREATE HTML FILE **************/
			   // place the new file in the correct directory using fopen to create if not existing
			   //create reference to new html path but strip out any local PHP folder notation i.e. (./ or ../)
			   $file_folder = $html_dir; //substr($html_dir, strpos($html_dir, "/"),strlen($html_dir));
			   $file_dir = $file_folder;
			   $file_name = $html_name;
			   $file_path = $file_dir.$file_name;
			   //echo $output_string;
			   if ($stream==0){
				   if ($output_string) {
					   //create directory for html file making sure to loop through dir passed in i.e. /new/sub/sub2/
					   //call makedirectory function create all required directorys
					   $dir_create = makedirectory($html_dir);
					   if($dir_create == 1){
					   $ConfMsg = "<LI>Folder <B>$filefolder</B> could not be created";
					   }

				   $BuildStatic = fopen($file_path, "w");
				   fputs($BuildStatic,$output_string);
				   fclose($BuildStatic);
				   $ConfMsg = "<LI><A HREF=\"". $html_dir . $file_name . "\" TARGET=\"_blank\">$file_name</A> created.";
				   }
				   unset($text);
				   unset($output_string);
				   unset($BuildStatic);
			   }else{
				   if ($output_string) {
				   		return $output_string;
				   }else{
				   		return "Could not create HTML";
				   }
			   }
				/*********************************************
				************ END CREATE HTML FILE ***********/

	// end check that template file can be found
	}
	//end if check for html file name and tempate name
	}
	return $ConfMsg;
	//end function
	}

	function makedirectory($html_dir){
	$tok  = strtok($html_dir,"/");
				while ($tok) {
				$path .= $tok."/";
				if(is_dir($path) == false){
						if(!mkdir($path , 0777)){
						return 0;
						}
				 }
				$tok = strtok("/");
				}
	}

	function getcontent($table, $category, $subcategory, $article_id=""){
	$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY title ASC";
	//echo $select."<br>";
	//echo $article_id;
	switch ($table){
		case "sublinks":
				$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
					$content .= "<p>".html_entity_decode($row["sublinks"])."</p>";
					}
					//echo "here";
					//echo $content;

				}
		break;
		case "photos":
				//quick hack to allow for gorgia europe and USA
				if ($subcategory == "georgia"){
					$country = $category.$subcategory;
				}else{
					$country = $subcategory;
				}

				$select = "SELECT * FROM $table WHERE `country` = '$country'";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<table align=\"right\" width=\"".$row["width"]."\">";
						if ($row["filename"]){
						$content .= "<tr><td nowrap align=\"right\">";
						$content .= "<img src=\"".ROOT_URL."/photos/".html_entity_decode($row["filename"])."\" width=\"".$row["width"]."\"  height=\"".$row["height"]."\">";
						$content .= "</td></tr>";
						}
						if ($row["text"]){
						$content .= "<tr><td align=\"right\" class=\"phototext\">";
						$content .= "<small>".auto_link( htmlentities($row["text"]) )."</small>";
						$content .= "</td></tr>";
						}
						$content .= "</table>";
					}

				}
		break;
		case "map":
				$select = "";
				$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						$target = "map_".$subcategory.".gif";
						$directory = "../../images";
						$content .= getmap($target, $directory, 1, $subcategory);
						if ($row["imagemap"]){
						$content .= "<p>";
						$content .= "\n".html_entity_decode($row["imagemap"])."\n";
						$content .= "</p>";
						}
						if ($row["regionlist"]){
						$content .= "<p class=\"imagemaplinks\">";
						$content .= "\n<br>".html_entity_decode($row["regionlist"])."\n";
						$content .= "</p>";
						}
						$content .= "</p>";
					}

				}
		break;
		case "site_map":
				$select = "";
				$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory'";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						$target = "map_".$subcategory.".gif";
						$directory = "../../images";
						$content .= getmap($target, $directory, 1, $subcategory);
						if ($row["imagemap"]){
						$content .= "<p>";
						$content .= "\n".html_entity_decode($row["imagemap"])."\n";
						$content .= "</p>";
						}
						if ($row["regionlist"]){
						$content .= "<p class=\"imagemaplinks\">";
						$content .= "\n<br>".html_entity_decode($row["regionlist"])."\n";
						$content .= "</p>";
						}
						$content .= "</p>";
					}

				}
		break;
		case "links":
		case "links_artists_photographers":
		case "links_festivals":
		case "links_museums":
		case "links_observatories":
		case "links_organisations":
		case "links_places_to_stay":
		case "links_reserves":
		case "links_trip_reports":
		case "links_holiday_companies":
		case "links_family_links":
		case "links_species_links":
		//echo $select."<br>";
				if ($table == "links_holiday_companies"){
				//include sponsorship link
				$select_sponsor = "";
				$select_sponsor = "SELECT * FROM sponsors WHERE `section` = '$table'";
				//echo $select_sponsor;
				$result = mysql_query($select_sponsor);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<b>".auto_link( htmlentities($row['title']))."</b><br>";
						}
						if ($row["url"]){
						$content .= "<small>".auto_link( htmlentities($row['url']))."</small><br>";
						}
						if ($row["link_desc"]){
						$content .= auto_link( htmlentities($row['link_desc']))."<br>";
						}
						if ($row["filename"]){
						$content .= "<img src=\"".ROOT_URL."/photos/".$row["filename"]."\" border=\"0\">";
						}
						$content .= "</p>";
					}
				}

				/*
				$content .= "<p><b>Anytime Tours1</b><br>";
				$content .= "<small><a href=\"http://www.anytimetours.co.uk/\" target=\"_blank\">http://www.anytimetours.co.uk</a></small><br>";
				$content .= "<b>Anytime Tours</b> offer a growing list of destinations for birders. We use local ground agents and guides so our prices are practically unbeatable! What is more we base those prices at just two people travelling together so can offer discounts to larger groups.</p>";
				*/


				}
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".html_entity_decode($row["title"])."</h2>";
						}
						if ($row["url"]){
						$content .= "<small>".auto_link( htmlentities($row["url"]))."</small>";
						}
						if ($row["link_desc"]){
						$content .= "<br>".auto_link( htmlentities($row["link_desc"]));
						$content .= "<br>";
						}
						$content .= "</p>";
					}

				}
		break;
		case "links_endemics":
		case "links_number_species":
		//echo $select."<br>";
				$title_cnt = 0;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						if ($table == "links_number_species"){
							if ($title_cnt == 0){
								if ($row["title"]){
								$content .= "<b>Number of bird species:</b>".auto_link( htmlentities($row["title"]));
								}
							}
						}else{
							if ($title_cnt == 0){
								if ($row["title"]){
								$content .= "<b>Number of endemics:</b>".auto_link( htmlentities($row["title"]));
								}
							}
						}
						if ($row["text"]){
						$content .= "<br>".auto_link( htmlentities($row["text"]))."<br><br>";
						}
						$content .= "</p>";

					$title_cnt ++;
					}

				}
		break;
		case "links_introduction":
		//echo $select."<br>";
				$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY paragraph";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
					$content .= "<p>".auto_link( htmlentities($row["text"]))."</p>";
					}

				}else{
				$select = "SELECT * FROM $table WHERE `category` = 'default_intro' AND `sub-category` = 'default_intro' ORDER BY paragraph";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
					while ($row = mysql_fetch_assoc($result))
					{
					$content .= "<p>".auto_link( htmlentities($row["text"]))."</p>";
					}

				}
		break;
		case "links_banners":
		//echo $select."<br>";
				$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
					$content .= "<p>";
					$content .= "<small>This page brought to you in association with:</small><br>";
					if ($row["sponsor"]){
					$content .= "<b>".auto_link( htmlentities($row["sponsor"]))."</b>";
					}
					$content .= "</p><p>";
				    if ($row["url"]){
					$content .= "<a href=".$row["url"]." target=\"_blank\"><img src=\"".ROOT_URL."/adverts/".$row["filename"]."\" width=\"".$row["width"]."\" height=\"".$row["height"]."\" border=\"0\" align=\"left\" hspace=\"10\"></a>";
					}
					if ($row["text"]){
					$content .= auto_link( htmlentities($row["text"]))."<br clear=\"all\">";
					}
					$content .= "&nbsp;</p>";
					$content .= "<p>&nbsp;</p>";
					}

				}
		break;
		case "links_contributor":
		$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id DESC";
		//echo $select."<br>";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						if ($row["name"]){
						$content .= "<p><b>".auto_link( htmlentities($row["name"]))."</b>";
						}
						if ($row["title"]){
						$content .= "<br>".auto_link( htmlentities($row["title"]));
						}
						if ($row["location"]){
						$content .= "<br><b>(".auto_link( htmlentities($row["location"])).")</b>";
						}
						if ($row["email"]){
						$content .= "<br>".auto_link( htmlentities($row["email"]));
						}
						if ($row["url"]){
						$content .= "<br>".auto_link( htmlentities($row["url"]));
						}
						$content .= "</<p>";
					}

				}
		break;
		case "links_county_recorder":
		$select = "SELECT * FROM $table WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY id DESC";
		//echo $select."<br>";
				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						if ($row["name"]){
						$content .= "<p><b>".auto_link( htmlentities($row["name"]))."</b><br>";
						}
						if ($row["address"]){
						$content .= auto_link( htmlentities($row["address"]))."<br>";
						}
						if ($row["telephone"]){
						$content .= auto_link( htmlentities($row["telephone"]))."<br>";
						}
						if ($row["fax"]){
						$content .= auto_link( htmlentities($row["fax"]))."<br>";
						}
						if ($row["email"]){
						$content .= auto_link( htmlentities($row["email"]));
						}
						$content .= "</<p>";
					}
				}
		break;
		case "links_mailing_lists":
		//echo $select."<br>";

				//include sponsorship link
				$select_sponsor = "";
				$select_sponsor = "SELECT * FROM sponsors WHERE `section` = '$table'";
				//echo $select_sponsor;
				$result = mysql_query($select_sponsor);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<b>".auto_link( htmlentities($row['title']))."</b><br>";
						}
						if ($row["url"]){
						$content .= "<small>".auto_link( htmlentities($row['url']))."</small><br>";
						}
						if ($row["link_desc"]){
						$content .= auto_link( htmlentities($row['link_desc']))."<br>";
						}
						if ($row["filename"]){
						$content .= "<img src=\"".ROOT_URL."/photos/".$row["filename"]."\" border=\"0\">";
						}
						$content .= "</p>";
					}
				}

				$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".auto_link( htmlentities($row["title"]))."</h2>";
						}
						if ($row["url"]){
						$content .= "<small>".auto_link( htmlentities($row["url"]))."</small>";
						$content .= "<br>";
						}
						if ($row["post_email"]){
						$content .= "<b>To post to list:</b>";
						$content .= auto_link( htmlentities($row["post_email"]));
						$content .= "<br>";
						}
						if ($row["contact"]){
						$content .= "<b>List contact:</b>";
						$content .= auto_link( htmlentities($row["contact"]));
						$content .= "<br>";
						}
						if ($row["sub_email"]){
						$content .= "<b>To subscribe to list:</b>";
						$content .= auto_link( htmlentities($row["sub_email"]));
						$content .= "<br>";
						}
						if ($row["sub_message"]){
						$content .= "<b>To unsubscribe:</b>";
						$content .= auto_link( htmlentities($row["sub_message"]));
						$content .= "<br>";
						}
						if ($row["body_message"]){
						$content .= auto_link( htmlentities($row["body_message"]));
						$content .= "<br>";
						}
						if ($row["text"]){
						$content .= auto_link( htmlentities($row["text"]));
						$content .= "<br>";
						}
						$content .= "</p>";
					}

				}
		break;
		case "links_top_sites":
			//echo $select;
			$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".auto_link( htmlentities($row["title"]))."</h2>";
						}
						if ($row["grid_reference"]){
						$content .= "(<a href=\"javascript:popupMap('".auto_link( htmlentities($row["grid_reference"]))."')\">".auto_link( htmlentities($row["grid_reference"]))."</a>)";
						}
						if ($row["text"]){
						$content .= auto_link( htmlentities($row["text"]));
						}
						$content .= "</<p>";
					}

				}

		break;
		case "links_useful_reading":
			//echo $select;
			$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".auto_link( htmlentities($row["title"]))."</h2>";
						}
						if ($row["text"]){
						$content .= auto_link( htmlentities($row["text"]))."<br>";
						}
						if ($row["isbn"]){
						$content .= "ISBN: ".auto_link( htmlentities($row["isbn"]))."<br>";
						//$content .= "<a href=\"http://www.amazon.co.uk/exec/obidos/ASIN/".$row["isbn"]."/fatbirder-21\" target=\"_blank\">Buy this book from Amazon.co.uk</a>";

						$content .= "<a href=\"http://www.nhbs.com/book_isbn_".$row["isbn"]."_ca_5.html\" target=\"_blank\">Buy this book from NHBS.com</a>";

						}
						// http://www.nhbs.com/book_isbn_0565091441_ca_5.html
						$content .= "</<p>";
					}

				}
		break;
		case "links_useful_information":
			//echo $select;
			$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".auto_link( htmlentities($row["title"]))."</h2><br>";
						}
						if ($row["text"]){
						$content .= auto_link( htmlentities($row["text"]));
						}
						$content .= "</<p>";
					}

				}
		break;
		case "default_about":
			//echo $select;
			$result = mysql_query($select);
				$count = mysql_num_rows($result);
				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$content .= "<p>";
						if ($row["title"]){
						$content .= "<h2>".auto_link( htmlentities( $row["title"]))."</h2><br>";
						}
						if ($row["text"]){
						$content .= auto_link( htmlentities($row["text"]));
						}
						$content .= "</<p>";
					}

				}
		break;
		case "NEWS":
		//echo $select."<br>";
				//set table name variables
				$main = "news_article_main";
				$text = "news_article_text";
				$status = "news_article_status";

				$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
				$select .= "AND $status.state = 'visible' ORDER BY $main.id DESC LIMIT 0,4";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);

				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
					  $content .= "<p>";
					  $content .= "<a href=\"news/index.php?article=".$row["id"]."\">";
					  $content .= "<img src=\"".ROOT_URL."/admin/news/images/";
						  if($row["image"]){
						  $content .= $row["image"];
						  }else{
						  $content .= "news1.gif";
						  }
					  $content .= "\" border=\"0\" width=\"65\" height=\"85\" align=\"left\">";
					  $content .= "</a>";
					  $content .= "<font size=\"4\"><i>".auto_link( htmlentities($row["title"]))."</i></font>";

					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
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

					  $content .= "<br>".auto_link( htmlentities($row["summary"]));
					  $content .= "<a href=\"news/index.php?article=".$row["id"]."\">";
					  $content .= "<br>Get the full story here...</a>";
					  $content .= "<br clear=\"left\">";
					  $content .= "</p>";
					}
				  }
		break;
		case "REVIEW":
		//echo $select."<br>";
				//set table name variables
				$main = "reviews_article_main";
				$text = "reviews_article_text";
				$status = "reviews_article_status";

				$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
				$select .= "AND $status.state = 'visible' ORDER BY $main.id DESC LIMIT 0,4";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);

				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{
					  $content .= "<p>";
					  $content .= "<a href=\"reviews/index.php?article=".$row["id"]."\">";
					  $content .= "<img src=\"".ROOT_URL."/admin/reviews/images/";
						  if($row["image"]){
						  $content .= $row["image"];
						  }else{
						  $content .= "reviews1.gif";
						  }
					  $content .= "\" border=\"0\" width=\"65\" height=\"85\" align=\"left\">";
					  $content .= "</a>";
					  $content .= "<font size=\"4\"><i>".auto_link( htmlentities($row["section"]))."</i></font>";

					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
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

					  $content .= "<br><font size=\"4\"><i>".auto_link( htmlentities($row["title"]))."</i></font>";
					  $content .= "<br>".auto_link( htmlentities($row["summary"]));
					  $content .= "<a href=\"reviews/index.php?article=".$row["id"]."\">";
					  $content .= "<br>Click here for the full fatbirder review....</a>";
					  $content .= "<br clear=\"left\">";
					  $content .= "</p>";

					}
				  }
		break;
		case "ARTICLE":
					//set table name variables
					$main = "news_article_main";
					$text = "news_article_text";
					$status = "news_article_status";

					$select = "SELECT $main.*, $text.* FROM $main, $text WHERE $main.article = $text.article ";
					$select .= "AND $main.article = '$article_id'";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						$title = $row["title"];
						$created =  $row["created"];
						}
					}

					$content .= "<p><h3>".$title."</font></h3></p>";

					$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";

					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{

					$content .= "<p>".auto_link($row["text"])."</p>";

						}
					}
					if ($created <> ""){
					$content .= "<p><i>Created: ".$created."</i></p>";
					}
					$content .= "<p>&nbsp;</p>";
		break;
		case "ARCHIVE":
					//set table name variables
					$main = "news_article_main";
					$text = "news_article_text";
					$status = "news_article_status";

					$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
					$select .= "AND $status.state = 'archived' ORDER BY $main.id ASC";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{

					  $content .= "<p>";
					  $content .= "<font size=\"4\"><i>".$row["title"]."</i></font>";

					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
					  /*
					  echo "<br>old : ".$old_news_date;
					  echo "<br>current : ".$current_news_date;
					  echo "<br>time stamp : ".$row["timestamp"];
					  */
					  if (($article_date > $old_news_date) && ($current_news_date > $article_date)){
					  	$content .= " <img src=\"".ROOT_URL."/images/new_icon.gif";
						$content .= "\" border=\"0\">";
					  }

					  $content .= "<br>".auto_link( htmlentities($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
					  $content .= "<br>Get the full story here...</a>";
					  $content .= "<br>";
					  $content .= "<font size=\"1\" color=\"#404040\">published: ".$row["created"]."</font>";
					  $content .= "</p>";
						}
					}
		break;
		case "ANNOUNCEMENT":
		//echo $select."<br>";
				//set table name variables
				$main = "announcements_article_main";
				$text = "announcements_article_text";
				$status = "announcements_article_status";

				$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
				$select .= "AND $status.state = 'visible' ORDER BY $main.id DESC LIMIT 0,4";
				//echo $select;
				$result = mysql_query($select);
				$count = mysql_num_rows($result);

				if ($count>0)
				{
					while ($row = mysql_fetch_assoc($result))
					{

   					  $content .= "<p>";
					  $content .= "<font size=\"4\"><i>".auto_link( htmlentities($row["title"]))."</i></font>";

					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
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


					  $content .= "<br>".auto_link( htmlentities($row["summary"]));
					  $content .= "<a href=\"announcements/index.php?article=".$row["id"]."\">";
					  $content .= "<br>Click here for full details....</a>";
					  $content .= "<br clear=\"left\">";
					  $content .= "</p>";
					}
				  }
		break;
		case "ARTICLE_ANNOUNCEMENT":
					//set table name variables
					$main = "announcements_article_main";
					$text = "announcements_article_text";
					$status = "announcements_article_status";

					$select = "SELECT $main.*, $text.* FROM $main, $text WHERE $main.article = $text.article ";
					$select .= "AND $main.article = '$article_id'";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						$title = $row["title"];
						$created =  $row["created"];
						}
					}

					$content .= "<p><h3>".$title."</font></h3></p>";

					$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";

					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{

					$content .= "<p>".auto_link($row["text"])."</p>";

						}
					}

					 if ($created <> ""){
					 $content .= "<p><i>Created: ".$created."</i></p>";
					 }
		break;
		case "ARCHIVE_ANNOUNCEMENT":
					//set table name variables
					$main = "announcements_article_main";
					$text = "announcements_article_text";
					$status = "announcements_article_status";

					$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
					$select .= "AND $status.state = 'archived' ORDER BY $main.id ASC";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{

					  $content .= "<p>";
					  $content .= "<font size=\"4\"><i>".$row["title"]."</i></font>";

					  //update 12-10-2005
					  //add a new icon to the news summary if it was pla ed in the last 14 days
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
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

					  $content .= "<br>".auto_link( htmlentities($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
					  $content .= "<br>Get full details here...</a>";
					  $content .= "<br>";
					  $content .= "<font size=\"1\" color=\"#404040\">published: ".$row["created"]."</font>";
					  $content .= "</p>";
						}
					}
		break;
		case "REVIEW_ARTICLE":
					//set table name variables
					$main = "reviews_article_main";
					$text = "reviews_article_text";
					$status = "reviews_article_status";

					$select = "SELECT $main.*, $text.* FROM $main, $text WHERE $main.article = $text.article ";
					$select .= "AND $main.article = '$article_id'";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						$title = auto_link( htmlentities($row["title"]));
						$desc_1 = auto_link( htmlentities($row["desc_1"]));
						$desc_2 = auto_link( htmlentities($row["desc_2"]));
						$desc_3 = auto_link( htmlentities($row["desc_3"]));

						$created =  $row["created"];
						}
					}

					if ($title){
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
					}

					$select = "SELECT * FROM $text WHERE article = '$article_id' ORDER BY paragraph";
					//echo $select;
					$result = mysql_query($select);
					$count = mysql_num_rows($result);

					if ($count>0)
					{
						while ($row = mysql_fetch_assoc($result))
						{
						 //$content .= "<p>".auto_link($row["text"])."</p>";
						 $content .= "<p>".html_entity_decode($row["text"],ENT_QUOTES)."</p>";
						}
					}
					 if ($created <> ""){
					 $content .= "<p><i>Created: ".$created."</i></p>";
					 }
					 $content .= "<p>&nbsp;</p>";
		break;
		case "REVIEW_ARCHIVE":
					//set table name variables
					$main = "reviews_article_main";
					$text = "reviews_article_text";
					$status = "reviews_article_status";

					$select = "SELECT $main.*, $main.timestamp AS article_timestamp, $status.* FROM $main, $status WHERE $main.article = $status.article ";
					$select .= "AND $status.state = 'archived' ORDER BY $main.id ASC";
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
					  $old_news_date = date ("YmdHms", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-14,date("Y")));
					  $current_news_date = date ("YmdHms");
					  $article_date = $row["article_timestamp"];
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

					   $content .= "<br><b>".auto_link( htmlentities($row["title"]))."</b>";
					   $content .= "<br>".auto_link( htmlentities($row["summary"]))."<a href=\"index.php?article=".$row["id"]."\">";
					   $content .= "<br>click here for the full fatbirder review....</a>";
					   $content .= "<br>";
					   $content .= "<font size=\"1\" color=\"#404040\">published:".$row["created"]."</font>";
					   $content .= "</p>";

						}
					  }
					 $content .= "<p><br></p>";
		break;
		case "LAST_UPDATED":
					//output the last time the site was updated as a string message date format e.g. tuesday, 12th March 2004
					 $content .= "This site was last updated on ".date ("l, jS F Y").".";
		break;
	//end switch
	}
	return $content;
	//end function
	}

	function makeALTDIR($category, $subcategory){
	switch($category){
		case "ornithology":
			switch($subcategory){
				case "Accipitridae":
				case "Aegothelidae":
				case "Alcedinidae":
				case "Alcidae":
				case "Anatidae":
				case "Anhimidae":
				case "Anhingidae":
				case "Anseranatidae":
				case "Apodidae":
				case "Apterygidae":
				case "Aramidae":
				case "Ardeidae":
				case "Balaenicipitidae":
				case "Brachypteraciidae":
				case "Bucconidae":
				case "Bucerotidae":
				case "Bucorvidae":
				case "Burhinidae":
				case "Cacatuidae":
				case "Capitonidae":
				case "Caprimulgidae":
				case "Cariamidae":
				case "Casuariidae":
				case "Cathartidae":
				case "Centropodidae":
				case "Charadriidae":
				case "Chionidae":
				case "Ciconiidae":
				case "Coccyzidae":
				case "Coliidae":
				case "Columbidae":
				case "Coraciidae":
				case "Cracidae":
				case "Crotophagidae":
				case "Coculidae":
				case "Dendrocygnidae":
				case "Diomedeidae":
				case "Dromadidae":
				case "Dromaiidae":
				case "Eurostopodidae":
				case "Eurypygidae":
				case "Falconidae":
				case "Formicariidae":
				case "Fregatidae":
				case "Galbulidae":
				case "Gaviidae":
				case "Glareolidae":
				case "Formicariidae":
				case "Fregatidae":
				case "Galbulidae":
				case "Gaviidae":
				case "Glareolidae":
				case "Gruidae":
				case "Haematopodidae":
				case "Heliornithidae":
				case "Hemiprocnidae":
				case "Hydrobatidae":
				case "Gruidae":
				case "Haematopodidae":
				case "Heliornithidae":
				case "Hemiprocnidae":
				case "Hydrobatidae":
				case "Ibidorhynchidae":
				case "Indicatoridae":
				case "Jacanidae":
				case "Laridae":
				case "Leptosomatidae":
				case "Ibidorhynchidae":
				case "Indicatoridae":
				case "Jacanidae":
				case "Laridae":
				case "Leptosomatidae":
				case "Loriidae":
				case "Lybiidae":
				case "Megalimidae":
				case "Megapodiidae":
				case "Meleagrididae":
				case "Meropidae":
				case "Mesitornithidae":
				case "Momotidae":
				case "Musophagidae":
				case "Neomorphidae":
				case "Numididae":
				case "Nyctibiidae":
				case "Odontophoridae":
				case "Opisthocomidae":
				case "Otididae":
				case "Pandionidae":
				case "Pedionomidae":
				case "Pelecanidae":
				case "Pelecanoididae":
				case "Phaethontidae":
				case "Phalacrocoracidae":
				case "Phasianidae":
				case "Phoenicopteridae":
				case "Phoeniculidae":
				case "Picidae":
				case "Podargidae":
				case "Podicipedidae":
				case "Procellariidae":
				case "Psittacidae":
				case "Psophiidae":
				case "Podargidae":
				case "Podicipedidae":
				case "Procellariidae":
				case "Psittacidae":
				case "Psophiidae":
				case "Pteroclidae":
				case "Rallidae":
				case "Ramphastidae":
				case "Solitaire":
				case "Recurvirostridae":
				case "Rheidae":
				case "Rhynochetidae":
				case "Rostratulidae":
				case "Rynchopidae":
				case "Sagittariidae":
				case "Scolopacidae":
				case "Scopidae":
				case "Spheniscidae":
				case "Steatornithidae":
				case "Stercorariidae":
				case "Sternidae":
				case "Strigidae":
				case "Struthionidae":
				case "Sulidae":
				case "Tetraonidae":
				case "Thinocoridae":
				case "Threskiornithidae":
				case "Tinamidae":
				case "Todidae":
				case "Trochilidae":
				case "Trogonidae":
				case "Turnicidae":
				case "Tytonidae":
				case "Upupidae":
					$alt_dir = "species_and_families/non_passerines/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
				break;
				case "species_and_families_homepage_non_passerines":
					$alt_dir = "species_and_families/non_passerines/";
					$alt_file = "index.html";
					return array ($alt_dir, $alt_file);
				break;
				case "acanthizidae":
				case "aegithalidae":
				case "aegithinidae":
				case "Alaudidae":
				case "artamidae":
				case "atrichornithidae":
				case "bombycillidae":
				case "callaeidae":
				case "campephagidae":
				case "cardinalidae":
				case "certhiidae":
				case "chloropseidae":
				case "cinclidae":
				case "cinclosomatidae":
				case "cisticolidae":
				case "climacteridae":
				case "Coerebidae":
				case "conopophagidae":
				case "corcoracidae":
				case "corvidae":
				case "cotingidae":
				case "cracticidae":
				case "dendrocolaptidae":
				case "dicaeidae":
				case "dicruridae":
				case "drepanididae":
				case "dulidae":
				case "emberizidae":
				case "eopsaltriidae":
				case "epthianuridae":
				case "estrildidae":
				case "eurylaimidae":
				case "formicariidae":
				case "fringillidae":
				case "furnariidae":
				case "grallinidae":
				case "hirundinidae":
				case "hypocoliidae":
				case "icteridae":
				case "irenidae":
				case "laniidae":
				case "malaconotidae":
				case "maluridae":
				case "melanocharitidae":
				case "meliphagidae":
				case "menuridae":
				case "mimidae":
				case "monarchidae":
				case "motacillidae":
				case "muscicapidae":
				case "nectariniidae":
				case "neosittidae":
				case "oriolidae":
				case "orthonychidae":
				case "oxyruncidae":
				case "pachycephalidae":
				case "paradisaeidae":
				case "paradoxornithidae":
				case "paramythiidae":
				case "pardalotidae":
				case "paridae":
				case "parulidae":
				case "passeridae":
				case "philepittidae":
				case "phytotomidae":
				case "picathartidae":
				case "pipridae":
				case "pittidae":
				case "pityriaseidae":
				case "platysteiridae":
				case "ploceidae":
				case "polioptilidae":
				case "pomatostomidae":
				case "prionopidae":
				case "promeropidae":
				case "prunellidae":
				case "ptilogonatidae":
				case "ptilonorhynchidae":
				case "pycnonotidae":
				case "regulidae":
				case "remizidae":
				case "rhabdornithidae":
				case "rhinocryptidae":
				case "rhipiduridae":
				case "Saxicoliidae":
				case "sittidae":
				case "sturnidae":
				case "sylviidae":
				case "thamnophilidae":
				case "thraupidae":
				case "tichodromidae":
				case "timaliidae":
				case "troglodytidae":
				case "turdidae":
				case "tyrannidae":
				case "vangidae":
				case "viduidae":
				case "vireonidae":
				case "xenicidae":
				case "zosteropidae":
					$alt_dir = "species_and_families/passerines/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
				break;
				case "species_and_families_homepage_passerines":
					$alt_dir = "species_and_families/passerines/";
					$alt_file = "index.html";
					return array ($alt_dir, $alt_file);
				break;
				case "pelagics":
					$alt_dir = "links/travel/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
				break;
				default:
					$alt_dir = "links/$category/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
				break;
			}
			break;
			case "africa":
				switch ($subcategory){
					case "africa":
						$alt_dir = "links_geo/$category/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "egypt":
						$alt_dir = "links_geo/middle_east/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
				}
			break;
			case "antarctica":
				$alt_dir = "links_geo/antartica/";
				$alt_file = "index.html";
				return array ($alt_dir, $alt_file);
			break;
			case "asia":
					switch ($subcategory){
					case "asia":
						$alt_dir = "links_geo/$category/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "indonesia_sumatera":
						$alt_dir = "links_geo/$category/";
						$alt_file = "indonesia_sumatra.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					}
			break;
			case "australasia":
				switch($subcategory){
					case "australia_northern_territories":
						$alt_dir = "links_geo/$category/";
						$alt_file = "australia_northern_territory.html";
						return array ($alt_dir, $alt_file);
					break;
					case "australia":
						$alt_dir = "links_geo/$category/";
						$alt_file = "australia_general.html";
						return array ($alt_dir, $alt_file);
					break;
					case "australasia":
						$alt_dir = "links_geo/$category/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "christmas_island":
						$alt_dir = "links_geo/$category/";
						$alt_file = "australia_christmas_island.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
				}
			break;
			case "europe":
				switch($subcategory){
					case "germany_deutschland_baden-württemberg":
						$alt_dir = "links_geo/$category/";
						$alt_file = "germany_deutschland_baden-wuerttemberg.html";
						return array ($alt_dir, $alt_file);
					break;
					case "siberia":
						$alt_dir = "links_geo/asia/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
					case "turkey":
						$alt_dir = "links_geo/middle_east/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
					case "gibraltar":
						$alt_dir = "links_geo/$category/";
						$alt_file = "gibralta.html";
						return array ($alt_dir, $alt_file);
					break;
					case "irish_republic":
						$alt_dir = "links_geo/$category/";
						$alt_file = "ireland.html";
						return array ($alt_dir, $alt_file);
					break;
					case "spain_castilla-la mancha":
						$alt_dir = "links_geo/$category/";
						$alt_file = "spain_castilla-la_mancha.html";
						return array ($alt_dir, $alt_file);
					break;
					case "spain_catalonia":
						$alt_dir = "links_geo/$category/";
						$alt_file = "spain_cataluna.html";
						return array ($alt_dir, $alt_file);
					break;
					case "england_general":
						$alt_dir = "links_geo/$category/";
						$alt_file = "england.html";
						return array ($alt_dir, $alt_file);
					break;
					case "england_leicestershire":
						$alt_dir = "links_geo/$category/";
						$alt_file = "england_leicester.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_antrim":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_antrim.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_armagh":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_armagh.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_down":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_down.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_fermanagh":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_fermanagh.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_londonderry":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_londonderry.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_tyrone":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland_tyrone.html";
						return array ($alt_dir, $alt_file);
					break;
					case "ulster_general":
					$alt_dir = "links_geo/$category/";
						$alt_file = "northern_ireland.html";
						return array ($alt_dir, $alt_file);
					break;
					case "scotland_argyll_bute":
					$alt_dir = "links_geo/$category/";
						$alt_file = "scotland_argyll_and_bute.html";
						return array ($alt_dir, $alt_file);
					break;
					case "scotland_angus":
					$alt_dir = "links_geo/$category/";
						$alt_file = "scotland_angus_and_dundee.html";
						return array ($alt_dir, $alt_file);
					break;
					case "scotland_moray":
					$alt_dir = "links_geo/$category/";
						$alt_file = "scotland_moray_and_nairn.html";
						return array ($alt_dir, $alt_file);
					break;
					case "scotland_perth_kinross":
					$alt_dir = "links_geo/$category/";
						$alt_file = "scotland_perth_and_kinross.html";
						return array ($alt_dir, $alt_file);
					break;
					case "scotland_general":
					$alt_dir = "links_geo/$category/";
						$alt_file = "scotland.html";
						return array ($alt_dir, $alt_file);
					break;
					case "wales_general":
					$alt_dir = "links_geo/$category/";
						$alt_file = "wales.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_channel_islands":
					$alt_dir = "links_geo/$category/";
						$alt_file = "england_channel_islands.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_isle_of_man":
					$alt_dir = "links_geo/$category/";
						$alt_file = "england_isle_of_man.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_isle_of_wight":
					$alt_dir = "links_geo/$category/";
						$alt_file = "england_isle_of_wight.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_isle_of_wight":
					$alt_dir = "links_geo/$category/";
						$alt_file = "england_isle_of_wight.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_scilly_isles":
					$alt_dir = "links_geo/$category/";
						$alt_file = "england_scilly_isles.html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_kingdom_general":
					$alt_dir = "links_geo/$category/";
						$alt_file = "uk.html";
						return array ($alt_dir, $alt_file);
					break;
					case "europe_general":
					$alt_dir = "links_geo/$category/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
				}
			break;
			case "middle_east":
					switch ($subcategory){
					case "middle_east":
						$alt_dir = "links_geo/$category/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					}
			break;
			case "n_america":
					switch ($subcategory){
					case "n_america":
						$alt_dir = "links_geo/america_north/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "mexico":
						$alt_dir = "links_geo/america_north/";
						$alt_file = "mexico.html";
						return array ($alt_dir, $alt_file);
					break;
					case "canada":
						$alt_dir = "links_geo/america_canada/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "alberta":
					case "british_columbia":
					case "manitoba":
					case "new_brunswick":
					case "newfoundland":
					case "nova_scotia":
					case "nunavut":
					case "nw_territory":
					case "ontario":
					case "prince_edward_island":
					case "quebec":
					case "saskatchewan":
					case "yukon":
					$alt_dir = "links_geo/america_canada/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
					case "alberta":
					case "alabama":
					case "alaska":
					case "arizona":
					case "arkansas":
					case "california":
					case "colorado":
					case "connecticut":
					case "delaware":
					case "district_columbia":
					case "florida":
					case "georgia":
					case "hawaii":
					case "idaho":
					case "illinois":
					case "indiana":
					case "iowa":
					case "kansas":
					case "kentucky":
					case "louisianna":
					case "maine":
					case "maryland":
					case "massachusetts":
					case "michigan":
					case "minnesota":
					case "mississippi":
					case "missouri":
					case "montana":
					case "nebraska":
					case "nevada":
					case "new_hampshire":
					case "new_jersey":
					case "new_mexico":
					case "new_york":
					case "north_carolina":
					case "north_dakota":
					case "ohio":
					case "oklahoma":
					case "oregon":
					case "pennsylvania":
					case "rhode_island":
					case "south_carolina":
					case "south_dakota":
					case "tennesse":
					case "texas":
					case "utah":
					case "vermont":
					case "virginia":
					case "washington":
					case "west_virginia":
					case "wisconsin":
					case "wyoming":
					$alt_dir = "links_geo/america_united_states/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					break;
					case "united_states":
						$alt_dir = "links_geo/america_united_states/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/america_north/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					}
			break;
			case "c_america":
				switch ($subcategory){
					case "c_america":
						$alt_dir = "links_geo/america_central/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					default:
						$alt_dir = "links_geo/america_central/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					}
			break;
			case "s_america":
				switch ($subcategory){
					case "s_america":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "index.html";
						return array ($alt_dir, $alt_file);
					break;
					case "acre":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_acre.html";
						return array ($alt_dir, $alt_file);
					case "amapa":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_amapa.html";
						return array ($alt_dir, $alt_file);
					case "amazonas":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_amazonas.html";
						return array ($alt_dir, $alt_file);
					case "columbia":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "colombia.html";
						return array ($alt_dir, $alt_file);
					case "equador_galapagos":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "ecuador_galapagos_islands.html";
						return array ($alt_dir, $alt_file);
					case "para":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_para.html";
						return array ($alt_dir, $alt_file);
					case "rondonia":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_rondonia.html";
						return array ($alt_dir, $alt_file);
					case "roraima":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_roraima.html";
						return array ($alt_dir, $alt_file);
					case "tocantins":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_tocantins.html";
						return array ($alt_dir, $alt_file);
					case "alagoas":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_alagoas.html";
						return array ($alt_dir, $alt_file);
					case "bahia":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_bahia.html";
						return array ($alt_dir, $alt_file);
					case "ceara":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_ceara.html";
						return array ($alt_dir, $alt_file);
					case "maranhao":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_maranhao.html";
						return array ($alt_dir, $alt_file);
					case "paraiba":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_paraiba.html";
						return array ($alt_dir, $alt_file);
					case "pernambuco":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_pernambuco.html";
						return array ($alt_dir, $alt_file);
					case "piaui":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_piaui.html";
						return array ($alt_dir, $alt_file);
					case "rio_grande_do_norte":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_rio_grande_do_norte.html";
						return array ($alt_dir, $alt_file);
					case "sergipe":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_sergipe.html";
						return array ($alt_dir, $alt_file);
					case "federal_district_inc_brasilia":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_federal_district_inc_brasilia.html";
						return array ($alt_dir, $alt_file);
					case "goias":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_goias.html";
						return array ($alt_dir, $alt_file);
					case "mato_grosso":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_mato_grosso.html";
						return array ($alt_dir, $alt_file);
					case "mato_grosso_do_sul":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_mato_grosso_do_sul.html";
						return array ($alt_dir, $alt_file);
					case "parana":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_parana.html";
						return array ($alt_dir, $alt_file);
					case "rio_grande_do_sul":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_rio_grande_do_sul.html";
						return array ($alt_dir, $alt_file);
					case "santa_catarina":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_santa_catarina.html";
						return array ($alt_dir, $alt_file);
					case "espirito_santo":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_espirito_santo.html";
						return array ($alt_dir, $alt_file);
					case "minas_gerais":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_minas_gerais.html";
						return array ($alt_dir, $alt_file);
					case "rio_de_janeiro":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_rio_de_janeiro.html";
						return array ($alt_dir, $alt_file);
					case "sao_paulo":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil_sao_paulo.html";
						return array ($alt_dir, $alt_file);
					case "brazil":
						$alt_dir = "links_geo/america_south/";
						$alt_file = "brazil.html";
						return array ($alt_dir, $alt_file);
					default:
						$alt_dir = "links_geo/america_south/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					}
			break;
			case "commerce":
				switch ($subcategory){
					case "holiday_companies":
						$alt_dir = "links/travel/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
					default:
						$alt_dir = "links/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
				}
			break;
			case "fun":
				switch ($subcategory){
					case "homepages":
						$alt_dir = "links/$category/";
						$alt_file = "misc.html";
						return array ($alt_dir, $alt_file);
					case "top_tens":
						$alt_dir = "links/$category/";
						$alt_file = "top_ten.html";
						return array ($alt_dir, $alt_file);
					default:
						$alt_dir = "links/$category/";
						$alt_file = strtolower($subcategory).".html";
						return array ($alt_dir, $alt_file);
				}
			break;
			case "world":
					$alt_dir = "links_geo/";
					$alt_file = "index.html";
					return array ($alt_dir, $alt_file);
			break;
			case "default_about":
					$alt_dir = "about/";
					$alt_file = "index.html";
					return array ($alt_dir, $alt_file);
			break;
			case "signpost":
					$alt_dir = "links/signpost_and_discussion/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
			break;
			//still needs to create a directory based on category for geo graphical links
			default:
					$alt_dir = "links/$category/";
					$alt_file = strtolower($subcategory).".html";
					return array ($alt_dir, $alt_file);
			//end cat case
			break;
	}
}

	function auto_link($string) {


		$link_reg = '´\b((?:https?|ftp|file)://[-\w+&@#/%?=~|!:,.;]*[-\w+&@#/%=~|])´';
		//$link_reg = "/(((ht|f)tp(s?))\:\/\/)|([a-zA-Z0-9\-]|www\.)([a-zA-Z0-9\-\.^@]+\.[a-zA-Z]{2,6})(\:[0-9]+)*((\/($|[a-zA-Z0-9\.\,\;\?\'\\\+&%\$#\=~_\-]+))*)/i";
		$email_reg = "/([a-zA-Z0-9\.\-]+)(@)([a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,6})/i";


        $link_repl = '<a href="\1" target=\'_blank\'>\1</a>';
		//$link_repl = "<a href='http://\\5\\6\\8' target='_blank'>\\5\\6\\8</a>";
        $email_repl = "<a href='mailto:\\1\\2\\3'>\\1\\2\\3</a>";

        $string = preg_replace($link_reg, $link_repl, $string);
        $string = preg_replace($email_reg, $email_repl, $string);

		return html_entity_decode($string);


}


	//proportionally generate resize constraints within $max_nuwidth and $max_nuheight
	function cons_resize($old_width,$old_height,$max_nuwidth,$max_nuheight)
	{
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
			if ($type == "image/gif" || $type == "image/pjpeg" || $size == 0)
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
						//check that a random number prefix has been requested in teh argument list
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
										/*no noeed to re-sample and create the JPEG
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
function getmap($target, $directory, $image, $map=""){

   if(is_dir($directory)){
       $direc = opendir($directory);
       while(false !== ($file = readdir($direc))){

           if($file !="." && $file != ".."){

               if(is_file($directory."/".$file)){
                   if(preg_match("/$target/i", $file)){
                                           if ($image==1){
										   	if ($map<>""){
											   $image = "<img usemap=\"#".$map."\" src=\"$directory/$file\" border=\"0\"><br>";
											 }else{
											   $image = "<img src=\"$directory/$file\" border=\"0\"><br>";
											 }
										   return $image;
										   }else{
										   $image = "<a href=\"$directory/$file\">$file</a><br>";
										   return $image;
										   }
                                       }
               }else if(is_dir($directory."/".$file)){
                   search($target,$directory."/".$file, $image);

               }

           }
       }
       closedir($direc);
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
                   search($target,$directory."/".$file, $type);

               }

           }
       }
       closedir($direc);
   }
}
?>