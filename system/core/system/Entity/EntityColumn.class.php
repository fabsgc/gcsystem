<?php
	/*\
	 | ------------------------------------------------------
	 | @file : EntityColumn.class.php
	 | @author : fab@c++
	 | @description : represent the column of a table for the entities
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Entity;

	use system\General\error;

	class EntityColumn {
		use error;

		protected $_name    =      '';
		protected $_options = array();
		protected $_value   =      '';

		/**
		 * Constructor
		 * @access public
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __construct() {
			$this->_options['autoincrement'] = false;
		}

		/**
		 * set the column name
		 * @param $name
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function setName($name) {
			$this->_name = $name;
		}

		/**
		 * set the column value
		 * @access public
		 * @param $value
		 * @return void
		 * @since 3.0
		 * @package system\Entity
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
		 * @package system\Entity
		*/

		public function setOptions($options) {
			foreach($options as $key => $value){
				$this->_options[''.$key.''] = $value;
			}
		}

		/**
		 * get value
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getValue() {
			return $this->_value;
		}

		/**
		 * get name
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getName() {
			return $this->_name;
		}

		/**
		 * get the options
		 * @access public
		 * @return array
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getOptions() {
			return $this->_options;
		}

		/**
		 * Destructor
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __destruct(){
		}
	}