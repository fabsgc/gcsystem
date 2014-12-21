<?php
	/*\
	 | ------------------------------------------------------
	 | @file : profiler.class.php
	 | @author : fab@c++
	 | @description : profiler
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class profiler{
			use error, facades, langInstance;

			protected $_sql              = array() ; //sql queries
			protected $_template         = array() ; //templates
			protected $_error            = array() ; //errors
			protected $_enabled          = PROFILER; //profiler activated ?

			protected $_time                       ;
			protected $_timeUser         = array() ;

			const SQL_START = 0;
			const SQL_END   = 1;
			const SQL_ROWS  = 2;

			const TEMPLATE_START = 0;
			const TEMPLATE_END   = 1;
			
			const USER_START = 0;
			const USER_END   = 1;

			/**
			 * constructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/

			public function __construct (){
				$this->_time = microtime(true);
			}

			/**
			 * at the end, put data in cache
			 * @access public
			 * @param $request
			 * @param $response
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function profiler($request, $response){
				$this->_stopTime();

				$this->request = &$request;
				$this->response = &$response;
				$this->profiler = &$this;

				if($this->_enabled == true){
					$dataProfiler = array();

					$dataProfiler['time'] = round($this->_time,2);
					$dataProfiler['timeUser'] = $this->_timeUser;
					$dataProfiler['controller'] = get_included_files();
					$dataProfiler['template'] = $this->_template;
					$dataProfiler['request'] = serialize($request);
					$dataProfiler['response'] = serialize($response);
					$dataProfiler['sql'] = $this->_sql;
					$dataProfiler['get'] = $_GET;
					$dataProfiler['post'] = $_POST;
					$dataProfiler['session'] = $_SESSION;
					$dataProfiler['cookie'] = $_COOKIE;
					$dataProfiler['files'] = $_FILES;
					$dataProfiler['server'] = $_SERVER;
					$dataProfiler['url'] = $_SERVER['REQUEST_URI'];

					$cache = $this->cache('gcsProfiler', 0);
					$cache->setContent($dataProfiler);
					$cache->setCache();

					$cacheId = $this->cache('gcsProfiler_'.$this->request->src.'.'.$this->request->controller.'.'.$this->request->action, 0);
					$cacheId->setContent($dataProfiler);
					$cacheId->setCache();
				}
			}

			/**
			 * add an error
			 * @access public
			 * @param $error string
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function addError($error){
				array_push($this->_error, $error);
			}

			/**
			 * add a template
			 * @access public
			 * @param $name
			 * @param $type
			 * @param $file
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function addTemplate($name, $type = self::TEMPLATE_START, $file){
				if($this->_enabled == true){
					switch ($type) {
						case self::TEMPLATE_START:
							$this->_template[$file] = array();
							$this->_template[$file]['name'] = $name;
							$this->_template[$file]['time'] = microtime(true);
						break;
						
						case self::TEMPLATE_END:
							$this->_template[$file]['time'] = round((microtime(true) - $this->_template[$file]['time'])*1000, 4);
						break;
					}
				}
			}

			/**
			 * add a sql query
			 * @access public
			 * * @param $name
			 * @param $type
			 * @param $value
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function addSql($name, $type = self::SQL_START, $value = ''){
				if($this->_enabled == true){
					switch ($type) {
						case self::SQL_START:
							$this->_sql[$name] = array();
							$this->_sql[$name]['time'] = microtime(true);
						break;
						
						case self::SQL_END:
							$this->_sql[$name]['time'] = round((microtime(true) - $this->_sql[$name]['time'])*1000, 4);
							$this->_sql[$name]['query'] = $value;
						break;
					}
				}
			}

			/**
			 * add time to timer
			 * @access public
			 * @param $name
			 * @param $type
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function addTime($name, $type = self::USER_START){
				if($this->_enabled == true){
					switch ($type) {
						case self::USER_START:
							$this->_timeUser[$name] = 0;
							$this->_timeUser[$name] = microtime(true);
						break;
						
						case self::USER_END:
							$this->_timeUser[$name] = round((microtime(true) - $this->_timeUser[$name])*1000, 4);
						break;
					}
				}
			}

			/**
			 * enable or disable the profiler
			 * @access public
			 * @param $enabled boolean
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public function enable($enabled = true){
				$this->_enabled = $enabled;
			}

			/**
			 * stop the timer
			 * @access protected
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			protected function _stopTime(){
				$this->_time = (microtime(true) - $this->_time) * 1000;
			}

			/**
			 * destructor
			 * @access public
			 * @return string
			 * @since 3.0
 			 * @package system
			*/

			public function __destruct(){
			}
		}
	}