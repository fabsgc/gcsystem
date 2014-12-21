<?php
	/*\
	 | ------------------------------------------------------
	 | @file : controller.class.php
	 | @author : fab@c++
	 | @description : abstract class. Mother class of all controllers
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		abstract class controller{
			use error, langInstance, url, ormFunctions, facades, entityFacades, entityHelpers;
			
			public $model; //instance model
			public $bdd  ; //instance PDO
			public $event; //instance event
			
			/**
			 * Initialization of the application
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @since 3.0
 			 * @package system
			*/

			final public function __construct(&$profiler, &$config, &$request, &$response, $lang){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_createLangInstance();

				if(DATABASE == true)
					$this->bdd = database::connect($GLOBALS['db']);
				
				$this->entity = $this->entity($this->bdd);
				$this->helper = $this->helper();

				$this->event = new eventManager();
			}
			
			/**
			 * You can override this method. She is called before the action
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function init(){	
			}

			/**
			 * You can override this method. She is called after the action
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function end(){	
			}

			/**
			 * check firewall
			 * @access public
			 * @return bool
			 * @since 3.0
 			 * @package system
			*/
			
			final public function setFirewall(){
				$firewall = $this->firewall();
				
				if($firewall->check())
					return true;
				else
					return false;
			}

			/**
			 * check spam
			 * @access public
			 * @return bool
			 * @since 3.0
 			 * @package system
			*/

			final public function setSpam(){
				$spam = $this->spam();
				
				if($spam->check())
					return true;
				else
					return false;
			}

			/**
			 * load model
			 * @access public
			 * @return void
			 * @since 3.0
 			 * @package system
			*/
			
			final public function model(){
				$class = "\\".$this->request->src."\\".'manager'.ucfirst($this->request->controller);
				
				if(class_exists($class)){
					$this->model = new $class($this->profiler, $this->config, $this->request, $this->response, $this->lang, $this->bdd, $this->entity, $this->helper);
					$this->model->init();
				}
				else{
					$this->addError('can\'t load model "'.$this->request->controller.'"', __FILE__, __LINE__, ERROR_FATAL);
				}
			}

			/**
			 * display a default template
			 * @access public
			 * @return string
			 * @since 3.0
			 * @package system
			 */

			final public function showDefault(){
				$t = $this->template('.app/system/default', 'systemDefault');
				$t->assign(array('action' => $this->request->src.'::'.$this->request->controller.'::'.$this->request->action));
				return $t->show();
			}

			/**
			 * destructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/

			public function __desctuct(){
			}
		}
	}