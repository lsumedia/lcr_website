<?php

require_once('config.php');

$action = $_GET['action'];

$include_string = 'pages/' . $action . '.php';

require($include_string);