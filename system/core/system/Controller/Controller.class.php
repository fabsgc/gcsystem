<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Controller.class.php
	 | @author : fab@c++
	 | @description : abstract class. Mother class of all controllers
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Controller;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\General\resolve;
	use system\General\url;
	use system\General\ormFunctions;
	use system\General\facadesEntity;
	use system\General\facadesHelper;
	use system\Database\Database;
	use system\Event\EventManager;
	use system\Exception\MissingModelException;

	abstract class Controller{
		use error, langs, url, resolve, ormFunctions, facades, facadesEntity, facadesHelper;
		
		public $model; //instance model
		public $bdd  ; //instance PDO
		public $event; //instance event
		
		/**
		 * Initialization of the application
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @since 3.0
		 * @package system\Controller
		*/

		final public function __construct(&$profiler, &$config, &$request, &$response, $lang){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_createlang();

			if(DATABASE == true)
				$this->bdd = Database::connect($GLOBALS['db']);
			
			$this->entity = $this->entity($this->bdd);
			$this->helper = $this->helper();

			$this->event = new EventManager();
		}
		
		/**
		 * You can override this method. She is called before the action
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Controller
		*/

		public function init(){	
		}

		/**
		 * You can override this method. She is called after the action
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Controller
		*/

		public function end(){	
		}

		/**
		 * check firewall
		 * @access public
		 * @return bool
		 * @since 3.0
		 * @package system\Controller
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
		 * @package system\Controller
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
		 * @throws \system\Exception\MissingModelException
		 * @package system\Controller
		*/
		
		final public function model(){
			$class = "\\".$this->request->src."\\".'Manager'.ucfirst($this->request->controller);
			
			if(class_exists($class)){
				$this->model = new $class($this->profiler, $this->config, $this->request, $this->response, $this->lang, $this->bdd, $this->entity, $this->helper);
				$this->model->init();
			}
			else{
				throw new MissingModelException('can\'t load model "'.$this->request->controller.'"');
			}
		}

		/**
		 * display a default template
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Controller
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
		 * @package system\Controller
		*/

		public function __desctuct(){
		}
	}