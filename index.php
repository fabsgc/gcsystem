<?php

	/*\
	 | ------------------------------------------------------
	 | @file : index.php
	 | @author : fab@c++
	 | @description : front controller of the application
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	session_start();

	use System\Config\Config;
	use System\Engine\Engine;

	$config = require_once('config.php');
	require_once(VENDOR_PATH . 'autoload.php');
	require_once(CLASS_AUTOLOAD);

	Config::instance($config);
	$engine = new Engine();
	$engine->init();
	$engine->run();