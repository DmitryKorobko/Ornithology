<?php
include("../includes/config.inc.php");
if ($_POST['action']) {
    $side = $_POST['side'];
    if ($side == "left") {
        $otherside = "right";
    } else {
        $otherside = "left";
    }
    switch ($_POST['action']) {
        case 'add':
            $ads = new Adverts($side);
            $$side .= $ads->addButton();
            $$side .= $ads->displayAdmin();
            $$side .= $ads->newAd();
            $ads = null;
            $ads = new Adverts($otherside);
            $$otherside .= $ads->addButton();
            $$otherside .= $ads->displayAdmin();
            $ads = null;
            break;
        case 'del':
            $ads = new Adverts($side);
            $ads->deleteAd($_POST['adid']);
            $$side .= $ads->addButton();
            $$side .= $ads->displayAdmin();
            $ads = null;
            $ads = new Adverts($otherside);
            $$otherside .= $ads->addButton();
            $$otherside .= $ads->displayAdmin();
            $ads = null;
            break;
        case 'update':
            $ads = new Adverts($side);
            $ads->updateAd();
            $$side .= $ads->addButton();
            $$side .= $ads->displayAdmin();
            $ads = null;
            $ads = new Adverts($otherside);
            $$otherside .= $ads->addButton();
            $$otherside .= $ads->displayAdmin();
            $ads = null;
            break;
        case 'addnewad':
            $ads = new Adverts($side);
            $ads->addNewAd();
            $$side .= $ads->addButton();
            $$side .= $ads->displayAdmin();
            $ads = null;
            $ads = new Adverts($otherside);
            $$otherside .= $ads->addButton();
            $$otherside .= $ads->displayAdmin();
            $ads = null;
            break;
    }
} else {
    $leftads = new Adverts("left");
    $left .= $leftads->addButton();
    $left .= $leftads->displayAdmin();

    $rightads = new Adverts("right");
    $right .= $rightads->addButton();
    $right .= $rightads->displayAdmin();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Adverts</title>
    <style>
        article {
            width: 95%;
        }

        section.left {
            width: 48%;
            float: left;
        }

        section.right {
            width: 48%;
            float: right;
        }

        .aditem {
            float: left;
            margin-top: 25px;
            width: 96%;
            padding: 2%;
            border: 1px solid #999;
        }

        p {
            float: left;
            margin: 3px 0;
            width: 100%;
        }

        input[type='text'], input[type='number'], input[type='url'] {
            margin-left: 5px;
        }

        input[type='url'] {
            width: 500px;
        }

        input[readonly] {
            border: none;
            width: 500px;
        }
    </style>
</head>
<body>
<h1>Adverts</h1>
<article>
    <p>If ads are given the same number for ad order, they will be displayed at random in the same spot</p>
    <section class="left">
        <h2>Left</h2>
        <?php
        echo $left;
        ?>
    </section>
    <section class="right">
        <h2>Right</h2>
        <?php
        echo $right;
        ?>
    </section>
</article>
</body>
</html>