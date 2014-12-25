<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Sql.class.php
	 | @author : fab@c++
	 | @description : sql better system
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Sql;

	use system\General\error;
	use system\General\facades;
	use system\General\facadesEntity;
	use system\Exception\Exception;
	use system\Exception\MissingSqlException;
	use system\Profiler\Profiler;

	class Sql{
		use error, facades,facadesEntity;

		protected $_var            = array();       //variable list
		protected $_query          = array();       //query list
		protected $_bdd            = array();       //Pdo instance
		protected $_time           = array();       //cache time
		protected $_cache                   ;       //cache instance
		protected $_content                 ;       //sql query
		protected $_data           = array();       //sql data
		protected $_nameQuery      =      '';       //name for caching file

		const PARAM_INT                 = 1;       //variable type, in relation with PDO_PARAM
		const PARAM_BOOL                = 5;
		const PARAM_NULL                = 0;
		const PARAM_STR                 = 2;
		const PARAM_FETCH               = 0;
		const PARAM_FETCHCOLUMN         = 1;
		const PARAM_FETCHINSERT         = 2;
		const PARAM_FETCHUPDATE         = 3;
		const PARAM_NORETURN            = 4;
		const PARAM_FETCHDELETE         = 5;

		/**
		 * constructor
		 * @access public
		 * @param &$profiler Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param $response
		 * @param $lang
		 * @param $bdd \system\Pdo\Pdo instance
		 * @since 3.0
		 * @package system\Sql
		*/

		public function __construct (&$profiler, &$config, &$request, &$response, $lang, $bdd){
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;
			$this->_bdd     =      $bdd;

			$this->entity = $this->entity($this->_bdd);

			$stack = debug_backtrace(0);
			$trace = $this->getStackTraceToString($stack);

			$this->_nameQuery = 'sql_'.$this->request->src.'_'.$this->request->controller.'_'.$this->request->action.'__'.$trace;
		}

		/**
		 * get the trace of execution. it's used to give an explicit name to the caching file
		 * @access protected
		 * @param $stack string
		 * @return string
		 * @since 3.0
		 * @package system\Sql
		*/

		private function getStackTraceToString($stack){
			$max = 0;
			$trace = '';

			for($i = 3; $i < count($stack) && $max < 4; $i++){
				if(isset($stack[$i]['file']) && preg_match('#('.preg_quote('system\Orm\Orm').')#isU', $stack[$i]['file'])){ //ORM
					$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'_'.$stack[$i-1]['line'].'__';
				}
				else{
					$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'__';
				}
			}

			return $trace;
		}

		/**
		 * Add a new query to the instance
		 * @access public
		 * @param $name string : the name of the query. If the name already exists, the old query will be erased
		 * @param $query string : the query with the Pdo syntax
		 * @param $time int : time cache
		 * @return void
		 * @since 3.0
		 * @package system\Sql
		*/
		
		public function query($name, $query, $time = 0){
			$this->_query[''.$name.''] = $query;
			$this->_time[''.$name.''] = $time;
		}

		/**
		 * add variables to the instance
		 * @access public
		 * @param $var  mixed : contain the list of the variable that will be used in the queries.
		 *  first syntax  : array('id' => array(31, Sql::PARAM_INT), 'pass' => array("fuck", sql::PARAM_STR))
		 *  second syntax : array('id' => 31, 'pass' => "fuck"). If you don't define the type of the variable, the class will assign itself the correct type
		 *  If you have only one variable to pass, you can use the 2/3 parameters form
		 *	first syntax  : ('id', 'value')
		 *  second syntax : ('id', 'value', Sql::PARAM_INT)
		 * @return void
		 * @since 3.0
		 * @package system\Sql
		*/
		
		public function setVar($var){
			if(is_array($var)){
				foreach($var as $key => $valeur){
					$this->_var[$key] = $valeur;
				}
			}
			else if(func_num_args() == 2){
				$args = func_get_args();
				$this->_var[$args[0]] = $args[1];
			}

			else if(func_num_args() == 3){
				$args = func_get_args();
				$this->_var[$args[0]] = array($args[1], $args[2]);
			}
		}

		/**
		 * Execute the query. This method returns the pdo object like the real execute() PDO method.
		 * It's useful if you want to use PDO method like lastInsertId()
		 * @access public
		 * @param string $name : name of the query you want to execute
		 * @throws \system\Exception\MissingSqlException
		 * @return Pdo
		 * @since 3.0
		 * @package system\Sql
		*/

		public function execute($name){
			try{
				$query = $this->_bdd->prepare(''.$this->_query[''.$name.''].'');
				
				foreach($this->_var as $key => $value){
					if(preg_match('`:'.$key.'[\s|,|\)|\(%]`', $this->_query[''.$name.''].' ')){
						if(is_array($value)){
							$query->bindValue($key, $value[0], $value[1]);
						}
						else{
							switch(gettype($value)){
								case 'boolean' :
									$query->bindValue(":$key", $value, self::PARAM_BOOL);
								break;
								
								case 'integer' :
									$query->bindValue(":$key", $value, self::PARAM_INT);
								break;
								
								case 'double' :
									$query->bindValue(":$key", $value, self::PARAM_STR);
								break;
								
								case 'string' :
									$query->bindValue(":$key", $value, self::PARAM_STR);
								break;
								
								case 'NULL' :
									$query->bindValue(":$key", $value, self::PARAM_NULL);
								break;
								
								default :
									$this->addError('SQL '.$name.'::'.$key.' unrecognized type', __LINE__, __FILE__, ERROR_INFORMATION, LOG_SQL);
								break;
							}
						}
					}
				}

				$query->execute();
				
				return $query;
			}
			catch (\PDOException $e){
				throw new MissingSqlException($e->getMessage().' / '.$e->getCode());
			}
		}

		/**
		 * Fetch a query. This method returns several values, depending on the fetching parameter
		 * @access public
		 * @param $name string : the name of the query you want to fetch
		 * @param $fetch int : type of fetch. 5 values available
		 *  sql::PARAM_FETCH         : correspond to the fetch of PDO. it's usefull for SELECT queries
		 *  sql::PARAM_FETCHCOLUMN   : correspond to the fetchcolumn of PDO. it's usefull for SELECT COUNT queries
		 *  sql::PARAM_FETCHINSERT   : useful for INSERT queries
		 *  sql::PARAM_FETCHUPDATE   : useful for UPDATE queries
		 *  sql::PARAM_FETCHDELETE   : useful for DELETE queries
		 *  default value : sql::PARAM_FETCH
		 * @return mixed
		 * @since 3.0
		 * @package system\Sql
		*/

		public function fetch($name, $fetch = self::PARAM_FETCH){
			if($this->_time[''.$name.''] > 0){
				$this->_cache = $this->cache($this->_nameQuery.$name.'.sql', "", $this->_time[''.$name.'']);
			}

			if((isset($this->_cache) && $this->_cache->isDie() && $this->_time[''.$name.''] > 0) ||
				$this->_time[''.$name.''] == 0 || $fetch == self::PARAM_FETCHINSERT ||
				$fetch == self::PARAM_FETCHUPDATE || $fetch == self::PARAM_FETCHDELETE){

				try {
					$query = $this->_bdd->prepare(''.$this->_query[''.$name.''].'');
					$this->profiler->addTime($this->_nameQuery.$name);
					$this->profiler->addSql($this->_nameQuery.$name, Profiler::SQL_START);
					
					foreach($this->_var as $key => $value){
						if(preg_match('`:'.$key.'[\s|,|\)|\(%]`', $this->_query[''.$name.''].' ')){
							if(is_array($value)){
								$query->bindValue($key, $value[0], $value[1]);
							}
							else{
								switch(gettype($value)){
									case 'boolean' :
										$query->bindValue(":$key", $value, self::PARAM_BOOL);
									break;
									
									case 'integer' :
										$query->bindValue(":$key", $value, self::PARAM_INT);
									break;
									
									case 'double' :
										$query->bindValue(":$key", $value, self::PARAM_STR);
									break;
									
									case 'string' :
										$query->bindValue(":$key", $value, self::PARAM_STR);
									break;
									
									case 'NULL' :
										$query->bindValue(":$key", $value, self::PARAM_NULL);
									break;
									
									default :
										$this->addError($name.'::'.$key.' unrecognized type', __LINE__, __FILE__, ERROR_INFORMATION, LOG_SQL);
									break;
								}
							}
						}
					}

					$query->execute();

					switch($fetch){
						case self::PARAM_FETCH : $this->_data = $query->fetchAll(); break;
						case self::PARAM_FETCHCOLUMN : $this->_data = $query->fetchColumn(); break;
						case self::PARAM_FETCHINSERT : $this->_data = true; break;
						case self::PARAM_FETCHUPDATE : $this->_data = true; break;
						case self::PARAM_FETCHDELETE : $this->_data = true; break;
						case self::PARAM_NORETURN : $this->_data = true; break;
						default : $this->addError('the execution constant '.$fetch.' doesn\'t exist', __LINE__, __FILE__, ERROR_INFORMATION, LOG_SQL); break;
					}

					$this->addError('['.$this->request->src.'] ['.$this->request->controller.'] ['.$this->request->action.'] ['.$name."] \n [".$query->debugQuery()."]", __LINE__, __FILE__, ERROR_INFORMATION, LOG_SQL);
					$this->profiler->addSql($this->_nameQuery.$name, Profiler::SQL_END, $query->debugQuery());
					$this->profiler->addTime($this->_nameQuery.$name, Profiler::USER_END);

					switch($fetch){
						case self::PARAM_FETCH :
							if(isset($this->_cache)){
								$this->_cache->setContent($this->_data);
								$this->_cache->setCache();
							}

							return $this->_data; break;
						break;

						case self::PARAM_FETCHCOLUMN :
							if(isset($this->_cache)){
								$this->_cache->setContent($this->_data);
								$this->_cache->setCache();
							}

							return $this->_data;
						break;

						case self::PARAM_FETCHINSERT : return true; break;
						case self::PARAM_FETCHUPDATE : return true; break;
						case self::PARAM_FETCHDELETE : return true; break;
						case self::PARAM_NORETURN : return true; break;
					}
				}
				catch (exception $e) {
					$this->addError($name.' '.$e->getMessage(), __FILE__, __LINE__, ERROR_FATAL);
					return false;
				}
			}
			else{
				if(isset($this->_cache)){
					return $this->_cache->getCache();
				}
				else{
					return false;
				}
			}
		}

		/**
		 * return data as an array of entities
		 * @access public
		 * @param $entity string
		 * @return array
		 * @since 3.0
		 * @package system\Sql
		*/

		public function data($entity = ''){
			$entities = array();

			foreach($this->_data as $value){
				if($entity != ''){
					$entityObject = $this->entity->$entity();

					foreach($value as $key => $value2){
						$entityObject->$key = $value2;
					}
				}
				else{
					$entityObject = $this->entityMultiple($value);
				}

				array_push($entities, $entityObject);
			}

			return $entities;
		}

		/**
		 * return data as an array
		 * @access public
		 * @return array
		 * @since 3.0
		 * @package system\Sql
		*/

		public function toArray(){
			return $this->_cache->getCache();
		}

		/**
		 * destructor
		 * @access public
		 * @since 3.0
		 * @package system\Sql
		*/

		public function __destruct(){
		}
	}