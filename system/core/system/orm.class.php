<?php
	/*\
	 | ------------------------------------------------------
	 | @file : orm.class.php
	 | @author : fab@c++
	 | @description : ORM of the GCsystem. it allows to :
	 | 	make simple queries (find***() count***())
	 | 	make complexe queries with the sql class or string + variables
	 | 	get data as entity or array
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class orm {
			use error, facades, entityFacades;

			protected $_bdd;

			/**
			 * Constructor
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $bdd pdo
			 * @param $entity string
			 * @since 3.0
 			 * @package system
			 */

			public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd, $entity = ''){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_bdd     =      $bdd;

				if($entity != '')
					$this->entity  =   $this->entity->$entity();
			}

			/**
			 * Destructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/

			public function __destruct(){
			}
		}
	}