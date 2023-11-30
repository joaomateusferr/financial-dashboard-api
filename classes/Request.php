<?php

class Request {

    public static function process() {

        require_once dirname(__FILE__)."/../config/routes.php";

        $Tokens = array_values(array_filter(explode("/", $_SERVER["REQUEST_URI"])));

        if(!isset($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]])){
            self::prepareResponse(404);
        }

        if(!empty($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Midwares'])){

            foreach($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Midwares'] as $Midware){

            }

        }

        if (!class_exists($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller']))
            throw new Exception('Controller class does not exist!');

        if (!method_exists($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller'], $Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Method']))
            throw new Exception('Method does not exist in controller class!');

        $Result = call_user_func([$Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller'], $Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Method']]);

        self::prepareResponse(200, $Result);

    }

    public static function prepareResponse(int $Code, array $Response = []) : void {

        http_response_code($Code);
        header("Content-type: application/json; charset=UTF-8");

        if(!empty($Response))
            echo json_encode($Response);

        exit;

    }

}