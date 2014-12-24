<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Orm.class.php
	 | @author : fab@c++
	 | @description : ORM of the GCsystem. it allows to :
	 | 	make simple queries (find***() count***())
	 | 	make complexe queries with the sql class or string + variables
	 | 	get data as entity or array
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Orm;

	use system\General\error;
	use system\General\facades;
	use system\General\facadesEntity;

	class Orm {
		use error, facades, facadesEntity;

		protected $_bdd;

		/**
		 * Constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $bdd \system\Pdo\Pdo
		 * @param $entity string
		 * @since 3.0
		 * @package system\Orm
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
		 * @package system\Orm
		*/

		public function __destruct(){
		}
	}