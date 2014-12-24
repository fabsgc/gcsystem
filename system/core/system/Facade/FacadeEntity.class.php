<?php
	/*\
	 | ------------------------------------------------------
	 | @file : FacadeEntity.class.php
	 | @author : fab@c++
	 | @description : easier way to instanciate entities
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Facade;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\Exception\MissingEntityException;

	class FacadeEntity{
		use error, facades, langs;

		protected $bdd;

		/**
		 * Constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $bdd \system\Pdo\Pdo
		 * @since 3.0
		 * @package system\Facade
		*/

		final public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->bdd      =      $bdd;
		}

		/**
		 * instantiate the good entity
		 * @access public
		 * @param $name string
		 * @param $arguments array
		 * @throws \system\Exception\MissingEntityException
		 * @return object
		 * @since 3.0
		 * @package system\Facade
		 */
		
		public function __call($name, $arguments){
			if(file_exists(APP_RESOURCE_ENTITY_PATH.$name.EXT_ENTITY.'.php')){
				include_once(APP_RESOURCE_ENTITY_PATH.$name.EXT_ENTITY.'.php');

				$class = '\entity\\'.$name;

				$params = array(
					&$this->profiler, 
					&$this->config  , 
					&$this->request ,
					&$this->response ,
					$this->lang,
					$this->bdd
				);

				foreach ($arguments as $value) {
					array_push($params, $value);
				}

				$reflect  = new \ReflectionClass($class);
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

				throw new MissingEntityException('undefined entity "'.$name.'" in "'.$file.'" line '.$line);
			}
		}

		/**
		 * @param $string
		 * @return mixed
		 * @since 3.0
		 * @package system\Facade
		*/

		public function getStackTraceFacade($string){
			return $string;
		}

		/**
		 * Destructor
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Facade
		*/

		public function __destruct(){
		}
	}