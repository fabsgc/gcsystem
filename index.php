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

	require_once('config.php');
	require_once('vendor/autoload.php');
	require_once(CLASS_AUTOLOAD);

	/** @var $db [] */

	$controller = new \System\Engine\Engine();
	$controller->init($db);
	$controller->run();