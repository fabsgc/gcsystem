<?php
	/*\
	 | ------------------------------------------------------
	 | @file : model.class.php
	 | @author : fab@c++
	 | @description : abstract class. Mother class of all models
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		abstract class model{
			use error, langInstance, url, ormFunctions, facades, entityFacades, entityHelpers;

			public $bdd     ;
			public $event   ;

			/**
			 * Initialization of the model
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $bdd pdo
			 * @since 3.0
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
			*/
				
			public function init(){	
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