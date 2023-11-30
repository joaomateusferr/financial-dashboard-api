<?php

	require_once dirname(__FILE__)."/../config/routes.php";

	header("Content-type: application/json; charset=UTF-8");

	$Tokens = explode("/", $_SERVER["REQUEST_URI"]);

	if(!isset($Routes[$_SERVER["REQUEST_METHOD"]][$Tokens[0]])){
		http_response_code(404);
    	exit;
	}

	var_dump($Tokens);exit;


	var_dump($_SERVER["REQUEST_METHOD"]);exit;
	var_dump($Routes);exit;
	//header("Location: components/pages/home.php");
?>
