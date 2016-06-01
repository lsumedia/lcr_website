<?php


function encode_get_string(){
    $string = $_GET['action'];
    
    foreach($_GET as $key => $var){
        if($key != 'action'){
            $string .= ('&' . $key . '=' . $var);
        }
    }
    return $string;
}