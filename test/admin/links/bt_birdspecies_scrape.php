<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
$table = "bt_bird_families";

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
            if (subcategory == 'Select a category') {
                document.category.subcategory.focus();
            } else {
                if (msie) window.document.frames[0].location.href = 'what.htm';

                else {
                    reloading = true;
                    showlinks();
                }
                return false;
            }
        }

        function load(object) {
            if (object.options[object.selectedIndex].value == 'Select a bird family') {
                document.region.names.focus();
            }
            else {
                parent.home.location.href = 'bt_birdspecies_scrape1.php?category=ornithology&subcategory=' + object.options[object.selectedIndex].value + '&random=1114618710' + rand(255);
                return false;
            }
        }

        function quickload(place) {
            parent.home.location.href = 'bt_birdspecies_scrape1.php?category=ornithology&subcategory=' + place + '&random=1114618710' + rand(255);
        }

        function showlinks() {
            if (subcategory == 'passerines') {
                document.region.names.length = 0;
                opt('', 'Select a bird family');
                for (bird_family in passerines) {
                    opt(bird_family, bird_family + " - " + passerines[bird_family]);
                }
            }
            if (subcategory == 'non_passerines') {
                document.region.names.length = 0;
                opt('', 'Select a bird family');
                for (bird_family in nonpasserines) {
                    opt(bird_family, bird_family + " - " + nonpasserines[bird_family]);
                }
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
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Ornithology></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a category</option>
                    <option value="passerines">Passerines</option>
                    <option value="non_passerines">Non Passerines</option>
                </select>
            </form>
        </td>
        <td valign="top">&gt;</td>
        <td>
            <script language="JavaScript">
                <!--
                var reloading = false;
                var subcategory = document.category.subcategory.options[0].text;

                var passerines = new Object();
                var nonpasserines = new Object();

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
            <?php
            echo '<script language="JavaScript"><!--' . "\n";

            $sql = "SELECT DISTINCT * FROM " . $table . " WHERE `familytype` = 'passerines' ORDER BY `familylatinname` ASC";
            //echo "<!-- $sql -->";

            $result = mysql_query($sql);

            while ($row = mysql_fetch_assoc($result)) {

                echo "passerines['" . $row["familylatinname"] . "'] = '" . $row["familycommonname"] . "';";
            }
            echo "\n";

            $sql = "SELECT DISTINCT * FROM " . $table . " WHERE `familytype` = 'non_passerines' ORDER BY `familylatinname` ASC";
            //echo "<!-- $sql -->";

            $result = mysql_query($sql);

            while ($row = mysql_fetch_assoc($result)) {

                echo "nonpasserines['" . $row["familylatinname"] . "'] = '" . $row["familycommonname"] . "';";
            }
            echo "\n";
            echo '//--></script>';
            ?>


        </td>
    </tr>
</table>

</body>
</html>