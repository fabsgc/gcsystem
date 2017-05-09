<?php

	/*\
	 | ------------------------------------------------------
	 | @file : index.php
	 | @author : Fabien Beaujean
	 | @description : Front controller of the application
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	session_start();

	require_once('vendor/autoload.php');

	use Gcs\Framework\Core\Config\Config;
	use Gcs\Framework\Core\Engine\Engine;

	$config = require_once('config.php');

	Config::instance($config);

	$engine = new Engine();
	$engine->init();
	$engine->run();

