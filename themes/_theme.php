<!doctype html>
<html lang="pt-br">
	<head>
        <title><?= $title; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="<?= url("themes/assets/img/icon.png"); ?>" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="<?= url("themes/assets/fonts/icomoon/style.css"); ?>">
        <link rel="stylesheet" href="<?= url("themes/assets/css/bootstrap.min.css"); ?>">
        <link rel="stylesheet" href="<?= url("themes/assets/css/main.css"); ?>">
        <link rel="stylesheet" href="<?= url("themes/assets/css/aos.css"); ?>">
        <link rel="stylesheet" href="<?= url("themes/assets/css/style.css"); ?>">

        <?= $v->section("css") ?>
	</head>

    <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300" class="fade-in">
		<?php $v->insert('_nav-bar'); ?>

        <div id="loader-div" class="loader-div">
            <div class="loader-spin"></div>
        </div>

        <div class="themeContent">
		    <?= $v->section("content"); ?>
        </div>

        <?php $v->insert('_chat'); ?>

        <script src="<?= url("themes/assets/js/jquery-3.3.1.min.js"); ?>"></script>
        <script src="<?= url("themes/assets/js/bootstrap.min.js"); ?>"></script>
        <script src="<?= url("themes/assets/js/aos.js"); ?>"></script>
        <script src="<?= url("themes/assets/js/main.js"); ?>"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"
                crossorigin="anonymous"></script>
        <?php $v->insert('_websocket'); ?>
        <?= $v->section("scripts") ?>
        <script>
            $(document).ready(function () {
                $("#loader-div").hide();
            });
        </script>
	</body>
</html>
