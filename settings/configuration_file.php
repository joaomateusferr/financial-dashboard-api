<?php

	$ProjectPath = $_SERVER['DOCUMENT_ROOT'].'/dev-toolkit';

	foreach (glob("$ProjectPath/classes/*.php") as $FileName)
		include $FileName;
	