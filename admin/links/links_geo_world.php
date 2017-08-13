<html>

<head>
    <title></title>
    <base target="home">
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

        function viewStats() {
            top.home.location.href = 'pick_record_geo.php?category=world&subcategory=world&random=1115138622' + rand(255);
        }

        //-->
    </script>
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
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" onLoad="viewStats()">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;World-wide</font></td>
    </tr>
</table>
</body>
</html>


