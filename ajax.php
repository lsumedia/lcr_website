<?php

require_once('config.php');
require_once('components/functions.php');

$action = $_GET['action'];

$include_string = 'pages/' . $action . '.php';

if(file_exists($include_string)){
    require($include_string);
}else{
    require('pages/error.php');
}