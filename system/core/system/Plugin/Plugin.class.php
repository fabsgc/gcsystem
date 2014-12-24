<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Plugin.class.php
	 | @author : fab@c++
	 | @description : plugin installation
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Plugin;

	use system\General\error;

	class Plugin {
		use error;

		/**
		 * Constructor
		 * @access public
		 * @param $bdd \system\Pdo\Pdo
		 * @since 3.0
		 * @package system\Plugin
		*/

		public function __construct($bdd){

		}

		/**
		 * Destructor
		 * @access public
		 * @since 3.0
		 * @package system\Plugin
		*/

		public function __destruct(){
		}
	}