<?php
	/*\
	 | ------------------------------------------------------
	 | @file : EntityMultiple.class.php
	 | @author : fab@c++
	 | @description : entity multiple : permit to manipulate data from query over several tables
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Entity;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;

	class EntityMultiple {
		use error, facades, langs;

		protected $_data   = array();

		/**
		 * constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param $response \system\response
		 * @param $lang string
		 * @param $data array
		 * @since 3.0
		 * @package system
		*/

		public function __construct(&$profiler, &$config, &$request, &$response, $lang, $data = array()) {
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_data = $data;
		}

		/**
		 * edit the value of a column
		 * @access public
		 * @param string $key
		 * @param array $value
		 * @return void
		 * @since 3.0
		 * @package system
		*/

		public function __set($key, $value) {
			if(array_key_exists($key, $this->_data)){
				$this->_data[''.$key.''] = $value;
			}
		}

		/**
		 * edit the value of a column
		 * @access public
		 * @param string $key
		 * @param array $value
		 * @return void
		 * @since 3.0
		 * @package system
		*/

		public function set($key, $value) {
			if(array_key_exists($key, $this->_data)){
				$this->_data[''.$key.''] = $value;
			}
		}

		/**
		 * get a column
		 * @access public
		 * @param $key string
		 * @return string
		 * @since 3.0
		 * @package system
		*/

		public function get($key) {
			if(array_key_exists($key, $this->_data)){
				return $this->_data[''.$key.''];
			}
		}

		/**
		 * get a column
		 * @access public
		 * @param $key string
		 * @return string
		 * @since 3.0
		 * @package system
		*/

		public function __get($key) {
			if(array_key_exists($key, $this->_data)){
				return $this->_data[''.$key.''];
			}
		}

		/**
		 * return data
		 * @access public
		 * @return array
		 * @since 3.0
		 * @package system
		*/

		public function toArray(){
			return $this->_data;
		}

		/**
		 * set the data
		 * @access public
		 * @param array $data
		 * @return void
		 * @since 3.0
		 * @package system
		*/

		public function setData($data = array()){
			$this->_data = $data;
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