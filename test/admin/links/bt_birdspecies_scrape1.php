<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables

$table_families = "bt_bird_families";
$table_species = "bt_bird_species";

$category = $_REQUEST["category"];
$subcategory = $_REQUEST["subcategory"];

$action = $_REQUEST["action"];
$speciestext = $_REQUEST["convertedtext"];



?>
<html>
<head>
    <title>Data Scrape Bird Species</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <style type="text/css">
        <!--
        p {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
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

        .title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 14pt;
            color: #000000;
        }

        .major_title {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
        }

        .footer {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #000000;
            text-decoration: none
        }

        .txtarea {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000000;
            text-decoration: none
        }

        -->
    </style>
    <script language="JavaScript">
        <!--

        function leftTrim(sString) {
            while (sString.substring(0, 1) == ' ') {
                sString = sString.substring(1, sString.length);
            }
            return sString;
        }

        function rightTrim(sString) {
            while (sString.substring(sString.length - 1, sString.length) == ' ') {
                sString = sString.substring(0, sString.length - 1);
            }
            return sString;
        }

        function trimAll(sString) {
            sString = leftTrim(sString);
            sString = rightTrim(sString);
            return sString;
        }

        function scrapeText() {
            var worktext = new String();
            var worktext1 = new String();
            var worktext_array = new Array();
            var commonname = new String();
            var latinname = new String();
            var splitline = new Array();

            var RE_brbr = /<br><br>/gi;
            var RE_br = /<br>/gi;
            var RE_multispace = /\s{1,}/gi;
            var RE_lineend = /\n/gi
            var RE_italicstart = /<i>/gi;
            var RE_italicend = /<\/i>/gi;
            worktext = document.rawtext.unabridgedtext.value;

            worktext = worktext.replace(RE_brbr, "<br>");
            worktext = worktext.replace(RE_multispace, " ");
            worktext = worktext.replace(RE_br, "\n");

            worktext_array = worktext.split(RE_lineend);

// split out by italic start and trim start/trail space.
// trim out italic stop, and trim start/trail space.

            for (i = 0; i < worktext_array.length; i++) {
                splitline = worktext_array[i].split(RE_italicstart);
                commonname = splitline[0];
                commonname = trimAll(commonname);
                latinname = splitline[1].replace(RE_italicend, "");
                latinname = trimAll(latinname);
                if (document.scraper.convertedtext.value == "") {
                    document.scraper.convertedtext.value = commonname + "~" + latinname;
                } else {
                    document.scraper.convertedtext.value = document.scraper.convertedtext.value + "\n" + commonname + "~" + latinname;
                }
            }

//	alert(worktext_array.length);

        }

        //-->
    </script>

</head>

<body>

<!-- START Commit1 --><?php if ($action == "Commit1") { ?>
<p class="title">Bird Species Data Scrape - Stage 2

<p class="major_title">Bird Family&nbsp;<?PHP echo $subcategory; ?></p>

<P>Look at the list below. These are all the bird species extracted from the previous page.
    <BR><B>Check to make sure the data looks/is correct and all there</B>!
    <BR>If there is a problem, click the browser back button now and correct the data.
    <BR>If all is OK, click on the &quot;Commit2&quot; button at the bottom of the page to put this data into the
    database.

<P>

    <?php $species = explode("\n", $convertedtext); ?>
    From Stage1, I have received <?PHP echo count($species); ?> bird species for bird family <?PHP echo $subcategory; ?>

<P>They are:
<TABLE CELLPADDING="5" CELLSPACING="1" BORDER="1" class="footer">
    <TR align="left">
        <TH>Count</TH>
        <TH>Bird Family</TH>
        <TH>Species Common Name</TH>
        <TH>Species Latin Name</TH>
        <TH>DB Entry Status</TH>
    </TR>

    <?php

    $rec_cnt = 0;
    $species_in_db = array();
    $species_in_db_lrn = array();
    $passarray = array();

    $sql = "SELECT `speciesid`,`specieslatinname` FROM bt_bird_species WHERE `familylatinname` = '" . $subcategory . "' ORDER BY `specieslatinname` ASC";
    echo "\n<!-- $sql -->";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
        $species_in_db[$rec_cnt] = strtolower($row["specieslatinname"]);
        $species_in_db_lrn[$rec_cnt] = $row["speciesid"];
        $rec_cnt++;
    }

    for ($i = 0; $i < count($species); $i++) {
        list($speciescommonname, $specieslatinname) = explode("~", $species[$i]);
        $speciescommonname = ltrim(rtrim($speciescommonname));
        $specieslatinname = ltrim(rtrim($specieslatinname));
        ?>
        <TR align="left">
            <TD><?PHP echo $i + 1; ?></TD>
            <TD><?PHP echo $subcategory; ?></TD>
            <TD><?PHP echo $speciescommonname; ?></TD>
            <TD><?PHP echo $specieslatinname; ?></TD>
            <TD><?php

                if (!(in_array(strtolower($specieslatinname), $species_in_db))) {
                    echo '<font color="red"><b>New to DB. Will be inserted.</b></font>';
                    $passarray[$i] = $speciescommonname . "~" . $specieslatinname . "~a~";
                } else {
                    echo '<font color="blue"><b>Exists in DB. Will be updated.</b></font>';
                    $key = array_search(strtolower($specieslatinname), $species_in_db);
                    $passarray[$i] = $speciescommonname . "~" . $specieslatinname . "~u~" . $species_in_db_lrn[$key];
                }

                ?></TD>
        </TR>
    <?php } ?>
</TABLE>
<?php
//Serialize and encode the passarray to make it a simple string.
$MyArray = urlencode(serialize($passarray));
?>

<center>
    <FORM name="scraper" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <INPUT type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">
        <INPUT type="hidden" name="speciesstr" value="<?php echo $MyArray; ?>">
        <INPUT type="submit" name="action" value="Commit2">
    </FORM>
</center>
</body>
</html>

    <!-- END Commit1 --><?php } ?>

<!-- START Commit2 --><?php if ($action == "Commit2") { ?>
<p class="title">Bird Species Data Scrape - Stage 3
<p class="major_title">Bird Family&nbsp;<?PHP echo $subcategory; ?></p>
<P>Storing species data for family : <?PHP echo $subcategory; ?> to the database...
<P>
    <?php
    $species = unserialize(urldecode($_REQUEST["speciesstr"]));
    $donewitherrors = 0;
    for ($i = 0; $i < count($species); $i++) {
        list($speciescommonname, $specieslatinname, $dbaction, $key) = explode("~", $species[$i]);
        if ($dbaction == "a") { //adding a new record
            echo "\n<BR>Adding $specieslatinname / $speciescommonname - ";
            $sql = "INSERT INTO bt_bird_species (speciesid, familylatinname, specieslatinname, speciescommonname) VALUES ('','$subcategory', '$specieslatinname', '$speciescommonname')";
            $result = mysql_query($sql);
        } else { //update an existing record
            echo "\n<BR>Updating $key / $specieslatinname / $speciescommonname - ";
            $sql = "UPDATE `bt_bird_species` SET `familylatinname` = '$subcategory', `specieslatinname` = '$specieslatinname', `speciescommonname` = '$speciescommonname' WHERE `speciesid` = $key LIMIT 1";
            $result = mysql_query($sql);
            // echo "\n <!-- $sql --> \n";
        };
        if ($result) {
            echo "Ok";
        } else {
            echo "<font color='red'>Failed</FONT>";
            $donewitherrors = 1;
        };
    }
    if ($donewitherrors == 1) {
        echo "<P><font color='red'><B>Done but with errors, please check!</B></FONT>";
    } else {
        echo "<P><font color='blue'><B>Species Data Stored Sucessfully!</B></FONT>";
    };
    ?>
    </body>
    </html>

    <!-- END Commit2 --><?php }; ?>

    <!-- START default --><?php if ($action == "") { ?>
<p class="title">Bird Species Data Scrape - Stage 2

<P>This program can extract bird species names from bird family page introdutions.
<P>You need to follow the instructions below. It is a multi-stage process.
<P>First, remove unwanted text from the &quot;Unabridged Text&quot; textbox.
    <BR>Second, click the button to do the data scrape.
    <BR>Third, check the contents of the &quot;Data to Post" list and ensure it is right.
    <BR>Forth, click the &quot;Commit&quot; button to save the extracted data.


<p class="major_title"><?PHP echo $subcategory; ?></p>

<TABLE ALIGN="left" BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH="100%">
    <TR ALIGN="left" VALIGN="middle">
        <TD><B>Unabridged Text</B><span class="footer"> - intro text with species names.</span>
            <br><span class="footer">Delete all text up to the first bird name.</span>
            <span class="footer">Delete any blank lines.</span></TD>
        <TD>&nbsp;</TD>
        <TD><B>Data to Post</B> - <span class="footer">Should be format of commonname~latinname</span>
            <BR><span class="footer">One species name for each line.</span>
            <BR><span class="footer">Hand correct any mistakes. Remove trailing blank lines</span></TD>
    </TR>
    <TR ALIGN="left" VALIGN="middle">
        <FORM name="rawtext">
            <TD><TEXTAREA name="unabridgedtext" rows="25" cols="40" class="txtarea">
                    <?PHP
                    $select = "SELECT * FROM links_introduction WHERE `category` = '$category' AND `sub-category` = '$subcategory' ORDER BY paragraph ASC";
                    //echo $select;
                    $result = mysql_query($select);
                    $count = mysql_num_rows($result);
                    while ($row = mysql_fetch_assoc($result)) {
                        echo "<br>" . $row["text"];
                    }
                    ?>
                </TEXTAREA></TD>
        </FORM>
        <TD align="center" valign="top"
        "><INPUT type="button" name="btn_scrape" value="Scrape" onClick="scrapeText()">
        <BR><BR>

        <FORM name="scraper" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <INPUT type="hidden" name="subcategory" value="<?php echo $subcategory; ?>">

            <INPUT type="submit" name="action" value="Commit1"></TD>
            <TD><TEXTAREA name="convertedtext" rows="25" cols="70" class="txtarea" WRAP="hard"></TEXTAREA></TD>
        </FORM>
    </TR>

</TABLE>
<P>

    </body>
    </html>

    <!-- END Default --><?php }; ?>


