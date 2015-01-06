<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Request.class.php
	 | @author : fab@c++
	 | @description : contain data and informations from http request and engine
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Request;

	use system\General\error;
	use system\Exception\AttributeNotAllowedException;

	class Request{
		use error;

		/** 
		 * parameters of each action
		 * @var array
		*/
			
		public $param = array(
			'name'       =>  '',
			'src'        =>  '',
			'controller' =>  '',
			'action'     =>  '',
			'cache'      =>   0,
			'logged'     => '*',
			'access'     => '*',
			'method'     => '*',
			'auth'       =>   ''
		);
		
		/**
		 * constructor
		 * @access public
		 * @since 3.0
		 * @package system\Request
		*/

		public function __construct (){
		}

		/**
		 * Magic get method allows access to parsed routing parameters directly on the object.
		 * @access public
		 * @param $name string : name of the attribute
		 * @return mixed
		 * @throws \system\Exception\AttributeNotAllowedException
		 * @since 3.0
		 * @package system\Request
		*/

		public function __get($name){
			if (isset($this->param[$name])) {
				return $this->param[$name];
			}
			else{
				throw new AttributeNotAllowedException("the attribute ".$name." doesn't exist");
			}
		}

		/**
		 * Magic get method allows access to parsed routing parameters directly on the object to modify it
		 * @access public
		 * @param $name string : name of the attribute
		 * @param $value string : new value
		 * @return void
		 * @throws \system\Exception\AttributeNotAllowedException
		 * @since 3.0
		 * @package system\Request
		*/

		public function __set($name, $value){
			if (isset($this->param[$name])) {
				$this->param[$name] = $value;
			}
			else{
				throw new AttributeNotAllowedException("the attribute ".$name." doesn't exist");
			}
		}

		/**
		 * get server data
		 * @access public
		 * @param $env
		 * @return boolean
		 * @since 3.0
		 * @package system\Request
		*/

		public function env($env){
			if(isset($_SERVER[$env])){
				return $_SERVER[$env];
			}
			else{
				return false;
			}
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Request
		*/

		public function __destruct(){
		}
	}