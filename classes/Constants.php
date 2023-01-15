<?php

class Constants {

    public static function getProjectPath() {

        $ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
	    unset($ProjectPath[array_key_last($ProjectPath)]);
	    $ProjectPath = implode("/", $ProjectPath);
        return $ProjectPath;

    }
    
    
}