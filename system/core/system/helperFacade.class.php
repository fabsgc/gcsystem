<?php
	/*\
	 | ------------------------------------------------------
	 | @file : entityMultiple.class.php
	 | @author : fab@c++
	 | @description : helper facade : permit to manipulate easily helpers class
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class helperFacade {
			use error, facades, langInstance;

			/** 
			 * list of the class alias and the real class behind
 			 * @var array
 			*/

			private $_alias = array(
			);

			/**
			 * Constructor
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @since 3.0
			*/

			final public function __construct(&$profiler, &$config, &$request, &$response, $lang){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
			}

			/**
			 * instanciate the good helper
			 * @access public
			 * @param $name string : helper class name
			 * @param $arguments array : helper class arguments
			 * @return object
			 * @since 3.0
			*/
			public function __call($name, $arguments){
				if(array_key_exists($name, self::$_alias)){
					$reflect  = new \ReflectionClass(self::$_alias[$name]);
					return $reflect->newInstanceArgs($params);
				}
				else{
					foreach ($stack as $key => $value) {
						if($value['function'] == $name){
							$file = $value['file'];
							$line = $value['line'];
							break;
						}
					}

					throw new exception('undefined helper"'.$name.'" in "'.$file.'" line '.$line, 1);
				}
			}

			/**
			 * Destructor
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			public function __destruct(){
			}
		}
	}