<?php

	spl_autoload_register(function ($Class) {
		require_once dirname(__FILE__)."/../classes/$Class.php";
	});

	set_error_handler("ErrorHandler::handleError");
	set_exception_handler("ErrorHandler::handleException");

	Request::process();

?>
