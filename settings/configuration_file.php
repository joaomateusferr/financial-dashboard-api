<?php

	$ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
	unset($ProjectPath[array_key_last($ProjectPath)]);
	$ProjectPath = implode("/", $ProjectPath);

	foreach (glob("$ProjectPath/classes/*.php") as $FileName)
		include $FileName;
	