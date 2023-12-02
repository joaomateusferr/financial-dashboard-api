<?php

	require_once dirname(__FILE__)."/../api/helpers/ErrorHandlerHelper.php";

	if(empty($ProjectPath)){
		$ProjectPath = explode("/", dirname(__FILE__));
		unset($ProjectPath[array_key_last($ProjectPath)]);
		$ProjectPath = implode("/", $ProjectPath);
	}

	spl_autoload_register(

		function ($Class) {
			require_once dirname(__FILE__)."/../classes/$Class.php";
		}

	);
