<?php

    $ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
    unset($ProjectPath[array_key_last($ProjectPath)]);
    $ProjectPath = implode("/", $ProjectPath);
    $ProjectPublicRoot = $_SERVER['DOCUMENT_ROOT'];
    $CurrentPage = '';
        
    include "$ProjectPath/settings/configuration_file.php";
    
    $Options = new Options();
?>