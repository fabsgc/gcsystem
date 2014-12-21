<?php
	/*\
	 | ------------------------------------------------------
	 | @file : request.class.php
	 | @author : fab@c++
	 | @description : contain datas and informations from http request andengine
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class request{
			use error;

			/** 
			 * parameters of each action
 			 * @var array
 			*/
			public $param = array(
				'name' => '',
				'src' => '',
				'controller' => '',
				'action' => '',
				'cache' => 0,
				'logged' => '*',
				'access' => '*'
			);
			
			/**
			 * constructor
			 * @access public
			 * @since 3.0
			*/

			public function __construct (){
			}

			/**
			 * Magic get method allows access to parsed routing parameters directly on the object.
			 * @access public
			 * @param $name string : name of the attribute
			 * @return mixed
			 * @since 3.0
			*/

			public function __get($name){
				if (isset($this->param[$name])) {
					return $this->param[$name];
				}
				else{
					throw new exception("the attribute ".$name." doesn't exist", 1);
				}
			}

			/**
			 * Magic get method allows access to parsed routing parameters directly on the object to modify it
			 * @access public
			 * @param $name string : name of the attribute
			 * @param $value string : new value
			 * @return void
			 * @since 3.0
			*/

			public function __set($name, $value){
				if (isset($this->param[$name])) {
					$this->param[$name] = $value;
				}
				else{
					throw new exception("the attribute ".$name." doesn't exist", 1);
				}
			}

			/**
			 * get server data
			 * @access public
			 * @param $name env : name
			 * @return mixed
			 * @since 3.0
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
			*/

			public function __destruct(){
			}
		}
	}