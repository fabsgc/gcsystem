<?php
	/*\
	 | ------------------------------------------------------
	 | @file : FacadeHelper.class.php
	 | @author : fab@c++
	 | @description : helper facades : permit to manipulate easily helpers class
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Facade;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\Exception\MissingHelperException;

	class FacadeHelper {
		use error, facades, langs;

		/** 
		 * list of the class alias and the real class behind
		 * @var array
		*/

		private $_alias = array(
		);

		/**
		 * Constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @since 3.0
		 * @package system\Facade
		*/

		final public function __construct(&$profiler, &$config, &$request, &$response, $lang){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
		}

		/**
		 * instantiate the good helper
		 * @access public
		 * @param $name string : helper class name
		 * @param $params array : helper class arguments
		 * @return object
		 * @throws \system\Exception\MissingHelperException when the helper doesn't exist
		 * @since 3.0
		 * @package system
		*/

		public function __call($name, $params){
			if(array_key_exists($name, $this->_alias)){
				$reflect  = new \ReflectionClass($this->_alias[$name]);
				return $reflect->newInstanceArgs($params);
			}
			else{
				$file = '';
				$line = '';
				$stack = debug_backtrace(0);
				$trace = $this->getStackTraceFacade($stack);

				foreach ($trace as $value) {
					if($value['function'] == $name){
						$file = $value['file'];
						$line = $value['line'];
						break;
					}
				}

				throw new MissingHelperException('undefined helper"'.$name.'" in "'.$file.'" line '.$line);
			}
		}

		/**
		 * @param $string
		 * @return mixed
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getStackTraceFacade($string){
			return $string;
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