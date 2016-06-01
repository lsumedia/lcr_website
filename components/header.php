<!-- Meta fields -->
<meta charset="UTF-8" />
<?php $pagetitle = ($pagetitle)? $pagetitle : $config['site_title']; ?>
<title><?= $pagetitle ?></title>

<!-- Fonts -->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Favicon -->
<!-- <link rel='shortcut icon' href='res/favicon.ico' type='image/x-icon'/ > -->
<link rel="icon" href="res/lcr_web_notext.ico" sizes="16x16" type="image/vnd.microsoft.icon">
<!-- add transparency! 
<link rel="icon" type="image/png" sizes="32x32" href="res/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="res/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="res/favicon-16x16.png"> -->


<!-- Styles -->
<link href="css/materialize.css" rel="stylesheet" />
<link href="css/style_main.css" rel="stylesheet" />

<!-- Generic cripts -->
<script type="text/javascript" src="js/config.js.php"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/display.js"></script>

<!-- Audio player script & style -->
<script type="text/javascript" src="js/audio_player.js"></script>
<link rel="stylesheet" href="css/audio_player.css" />

<script type="text/javascript" src="js/dynamic_pages.js"></script>

<!-- OpenGraph sitewide tags -->
<meta property="og:title" content="<?= $pagetitle ?>" />
<meta property="og:site_name" content="LSUTV" />
<meta property="og:locale" content="en_GB" />

<!-- Theme colour for mobile users -->
<meta name="theme-color" content="#2486c7">
<meta name="msapplication-navbutton-color" content="#2486c7">
<meta name="apple-mobile-web-app-status-bar-style" content="#2486c7">

<meta name="viewport" content="width=device-width, user-scalable=no" />


