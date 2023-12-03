<?php

	$GLOBALS['Debug'] = true;

	$ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
	unset($ProjectPath[array_key_last($ProjectPath)]);
	$ProjectPath = implode("/", $ProjectPath);

	spl_autoload_register(

		function ($Class) {

			if(str_ends_with($Class, 'Controller'))
				require_once dirname(__FILE__)."/../api/controllers/$Class.php";
			elseif(str_ends_with($Class, 'Midware'))
				require_once dirname(__FILE__)."/../api/midwares/$Class.php";
			elseif(str_ends_with($Class, 'Helper'))
				require_once dirname(__FILE__)."/../api/helpers/$Class.php";
			else
				require_once dirname(__FILE__)."/../services/$Class.php";

		}

	);

	set_error_handler("ErrorHandlerHelper::handleError");
	set_exception_handler("ErrorHandlerHelper::handleException");
