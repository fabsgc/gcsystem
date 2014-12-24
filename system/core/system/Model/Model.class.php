<?php
	/*\
	 | ------------------------------------------------------
	 | @file : model.class.php
	 | @author : fab@c++
	 | @description : abstract class. Mother class of all models
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Model;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\General\resolve;
	use system\General\url;
	use system\General\ormFunctions;
	use system\General\facadesEntity;
	use system\General\facadesHelper;
	use system\Event\EventManager;

	abstract class Model{
		use error, langs, url, resolve, ormFunctions, facades, facadesEntity, facadesHelper;

		public $bdd  ;
		public $event;

		/**
		 * Initialization of the model
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $bdd \system\Pdo\Pdo
		 * @param $entity \system\General\facadesEntity
		 * @param $helper \system\General\facadesHelper
		 * @since 3.0
		 * @package system
		*/
		
		final public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd, $entity, $helper){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->bdd      =      $bdd;
			$this->entity   =   $entity;
			$this->helper   =   $helper;

			$this->event = new eventManager();
		}

		/**
		 * You can override this method. She is called when the controller instantiate the class
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system
		*/
			
		public function init(){	
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system
		*/
		
		public function __destruct(){
		}
	}