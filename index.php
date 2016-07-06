<?php

	/*\
	 | ------------------------------------------------------
	 | @file : index.php
	 | @author : fab@c++
	 | @description : central controller of the application
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	use System\Config\Config;

	session_start();

	/** @var [] $config */
	$config = require_once('config.php');
	require_once(VENDOR_PATH . 'autoload.php');
	require_once(CLASS_AUTOLOAD);

	Config::instance($config);
	$engine = new \System\Engine\Engine();
	$engine->init();
	$engine->run();