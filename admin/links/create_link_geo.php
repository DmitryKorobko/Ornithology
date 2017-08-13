<html>

<head>
    <title></title>
    <base target="home">
    <script>

        function viewAdmin() {
            top.home.location.href = 'create_record_geo1.php?category=<?php echo $_GET["category"]; ?>';
        }

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

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" onLoad="viewAdmin()">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Create
                Page <?php echo "for : " . $_GET["category"] . " section"; ?></font></td>
    </tr>
</table>
</body>
</html>


