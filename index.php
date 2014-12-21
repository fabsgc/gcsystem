<?php
	session_start();

	/*\
	 | ------------------------------------------------------
	 | @file : index.php
	 | @author : fab@c++
	 | @description : central controller of the application
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	require_once('web.config.php');
	require_once(CLASS_AUTOLOAD);

	$controller = new system\engine();
	$controller->init();
	$controller->run();