<?php

const BASE_NAMESPACE_NAME = "application";
const BASE_APP_DIR_NAME = "web";
const FILE_EXTENSIONS = array(".class.php",".interface.php");

/**
 * Automatická registrace požadovaných tříd
 */
spl_autoload_register(function ($className){
    $className = str_replace(BASE_NAMESPACE_NAME,BASE_APP_DIR_NAME,$className);
    $fileName = dirname(__FILE__) . "\\" . $className;

    foreach(FILE_EXTENSIONS as $ext){
        if(file_exists($fileName . $ext)){
            $fileName .= $ext;
            break;
        }
    }

    require_once($fileName);
});


