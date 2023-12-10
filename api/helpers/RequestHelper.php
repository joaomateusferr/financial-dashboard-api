<?php

class RequestHelper {

    public static function process() : void {

        require_once dirname(__FILE__)."/../../config/routes.php";

        $Tokens = array_values(array_filter(explode("?", $_SERVER["REQUEST_URI"]))); //Ignore get parameters
        $Tokens = array_values(array_filter(explode("/", $Tokens[0])));

        if(empty($Tokens))
            self::prepareResponse(400, ["ErrorMessage" => "Please use one endpoint!"]);

        if(!isset($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]])){
            self::prepareResponse(404);
        }

        $Request = [];

        if($_SERVER["REQUEST_METHOD"] == 'GET'){

            $Request['Parameters'] = $_GET;

        }

        if(!empty($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Arguments'])){

            if(count($Tokens) -1 != count($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Arguments']))
                self::prepareResponse(400, ["ErrorMessage" => "The request sent does not match the arguments endpoint structure!"]);

            $RequestArguments = $Tokens;
            unset($RequestArguments[0]);
            $RequestArguments = array_values($RequestArguments);

            foreach($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Arguments'] as $Index => $Arguments){
                $Request['Arguments'][$Arguments] = $RequestArguments[$Index];
            }

        }

        if(!empty($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Midwares'])){

            foreach($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Midwares'] as $Midware){

                if (!class_exists($Midware['Class']))
                    throw new Exception('Midware class does not exist!');

                if (!method_exists($Midware['Class'], $Midware['Method']))
                    throw new Exception('Midware method does not exist in controller class!');

                $MidwareArgs = isset($Midware['RequireRequest']) && $Midware['RequireRequest'] ? [&$Request] : [];

                call_user_func_array([$Midware['Class'], $Midware['Method']], $MidwareArgs);

            }

        }

        if (!class_exists($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller']))
            throw new Exception('Controller class does not exist!');

        if (!method_exists($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller'], $Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Method']))
            throw new Exception('Controller method does not exist in controller class!');

        $Args = isset($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['RequireRequest']) && $Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['RequireRequest'] ? [$Request] : [];

        $Result = call_user_func_array([$Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Controller'], $Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]]['Method']], $Args);

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