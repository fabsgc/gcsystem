<?php
	/*\
	 | ------------------------------------------------------
	 | @file : entityColumn.class.php
	 | @author : fab@c++
	 | @description : represent the column of a table for the entities
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class entityColumn {
			use error;

			protected $_name    =      '';
			protected $_options = array();
			protected $_value   =      '';

			/**
			 * Constructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/

			public function __construct() {
				$this->_options['autoincrement'] = false;
			}

			/**
			 * set the column name
			 * @param $options array
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function setName($name) {
				$this->_name = $name;
			}

			/**
			 * set the column value
			 * @param $options array
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function setValue($value) {
				$this->_value = $value;
			}

			/**
			 * set the column's options
			 * 		- autoincrement : true/false
			 * @param $options array
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function setOptions($options) {
				foreach($options as $key => $value){
					$this->_options[''.$key.''] = $value;
				}
			}

			/**
			 * get the value
			 * @access public
			 * @return string
			 * @since 3.0
 			 * @package system
			*/

			public function getValue() {
				return $this->_value;
			}

			/**
			 * get the name
			 * @access public
			 * @return string
			 * @since 3.0
 			 * @package system
			*/

			public function getName() {
				return $this->_name;
			}

			/**
			 * get the options
			 * @access public
			 * @return array
			 * @since 3.0
 			 * @package system
			*/

			public function getOptions() {
				return $this->_options;
			}

			/**
			 * Destructor
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function __destruct(){
			}
		}
	}