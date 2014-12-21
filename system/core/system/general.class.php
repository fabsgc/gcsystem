<?php
	/*\
	 | ------------------------------------------------------
	 | @file : general.class.php
	 | @author : fab@c++
	 | @description : functions used everywhere
	 | @version : 3.0
	 | ------------------------------------------------------
	\*/

	namespace system{
		trait resolve{
			/**
			 * when you want to use a lang, route, image, template, this method is used to resolve the right path
			 * the method use the instance of \system\config
			 * @access public
			 * @param $type string : type of the config
			 * @param $data string : ".gcs.lang" ".gcs/template/" "template"
			 * @return mixed
 			 * @since 3.0
			*/

			protected function resolve($type, $data){
				if($type == RESOLVE_ROUTE || $type == RESOLVE_LANG){
					if(preg_match('#^((\.)([a-zA-Z0-9_-]+)(\.)(.+))#', $data, $matches)){
						$src = $matches[3];
						$data = preg_replace('#^(\.)('.preg_quote($src).')(\.)#isU', '', $data);
					}
					else{
						$src = $this->request->src;
					}

					return array($this->config->config[$type][$src], $data);
				}
				else{
					if(preg_match('#^((\.)([^(\/)]+)([(\/)]*)(.*))#', $data, $matches)){
						$src = $matches[3];
						$data = $matches[5];
					}
					else{
						if($this->request->src != '') {
							$src = $this->request->src;
						}
						else{
							$src = 'app';
						}
					}

					return $this->config->config[$type][$src].$data;
				}
			}

			/**
			 * when you want to use an image, file only, this method is used to resolve the right path
			 * the method override resolve()
			 * @access public
			 * @param $type string : type of the config
			 * @param $data string : ".gcs/template/" "template"
			 * @param $php boolean : because method return path, the framework wants to know if you want the html path or the php path
			 * @return string
 			 * @since 3.0
			*/

			protected function path($type, $data = '', $php = false){
				if($php == false){
					return $this->resolve($type, $data);
				}
				else{
					return FOLDER.$this->resolve($type, $data);
				}
			}
		}

		trait facades{
			protected $profiler;
			protected $config  ;
			protected $request ;
			protected $response ;

			/**
			 * when you want to use a core or helper class, you can use the system of facade. It allow you tu instantiate
			 * @access public
			 * @param $name string : name of alias
			 * @param $arguments array
			 * @return object
 			 * @since 3.0
			*/

			public function __call($name, $arguments = array()){
				$stack = debug_backtrace(0);
				$trace = $this->getStackTraceFacade($stack);

				$params = array(
					&$this->profiler, 
					&$this->config  , 
					&$this->request ,
					&$this->response ,
					$this->lang
				);

				foreach ($arguments as $value) {
					array_push($params, $value);
				}

				return facade::load($name, $params, $trace);
			}

			public function getStackTraceFacade($string){
				return $string;
			}
		}

		trait entityFacades{
			protected $entity;
		}

		trait entityHelpers{
			protected $helper;
		}

		trait error{
			/**
			 * add an erorr in the log
			 * @access public
			 * @param $error string : error
			 * @param $file string : file with error
			 * @param $line int : line with error
			 * @param $type string : type of error
			 * @param $log string : log file
			 * @return void
			 * @since 3.0
			 */

			public function addError($error, $file = __FILE__, $line = __LINE__, $type = ERROR_INFORMATION, $log = LOG_SYSTEM){
				if($log != LOG_HISTORY && $log != LOG_CRONS && $log != LOG_EVENT){
					if(LOG_ENABLED == true){
						$data = date("d/m/Y H:i:s : ",time()).'['.$type.'] file '.$file.' / line '.$line.' / '.$error;
						file_put_contents(APP_LOG_PATH.$log.EXT_LOG, $data."\n", FILE_APPEND | LOCK_EX);

						if((DISPLAY_ERROR_FATAL == true && $type == ERROR_FATAL) || 
						(DISPLAY_ERROR_EXCEPTION == true && $type == ERROR_EXCEPTION) ||
						(DISPLAY_ERROR_ERROR == true && $type == ERROR_ERROR)){
							if(CONSOLE_ENABLED == MODE_HTTP)
								echo $data."\n<br />";
							else
								echo $data."\n";
						}

						if(PROFILER == true){

						}
					}
				}
				else{
					file_put_contents(APP_LOG_PATH.$log.EXT_LOG, $error."\n", FILE_APPEND | LOCK_EX);
				}
			}

			/**
			 * add an hr line in the log
			 * @access public
			 * @param $log string : log file
			 * @return void
			 * @since 3.0
			*/
			public function addErrorHr($log = LOG_SYSTEM){
				if(LOG_ENABLED == true){
					file_put_contents(APP_LOG_PATH.$log.EXT_LOG, "#################### END OF EXECUTION OF http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']." ####################\n", FILE_APPEND | LOCK_EX);
				}
			}
		}

		trait ormFunctions{
			/**
			 * transform sql data in entity
			 * @access public
			 * @param $data array
			 * @param $entity string
			 * @return array
			 * @since 2.4
			 */
			final public function ormToEntity($data = array(), $entity = ''){
				$entities = array();

				foreach($data as $value){
					if($entity != ''){
						$entityName = '\entity\\'.$entity;
						$entityObject = new $entityName($this->bdd);

						foreach($value as $key => $value2){
							$entityObject->$key = $value2;
						}
					}
					else{
						$entityObject = $this->entityMultiple($data);
					}

					array_push($entities, $entityObject);
				}

				return $entities;
			}
		}
		
		trait langInstance{
			protected $lang  = LANG;
			protected $langInstance;
			
			/**
			 * get the client language
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function getLangClient(){
				if(!array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) || !$_SERVER['HTTP_ACCEPT_LANGUAGE'] ) { return LANG; }
				else{
					$langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
					$langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
					$langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
					$langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
					return $langcode['0'];
				}
			}

			/**
			 * set lang
			 * @access public
			 * @param string lang
			 * @return void
			 * @since 3.0
			*/

			public function setLang($lang = ''){
				$this->lang = $lang;
				$this->langInstance->setLang($this->lang);
			}

			/**
			 * get lang
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function getLang(){
				return $this->lang;
			}

			/**
			 * use \system\lang
			 * @access public
			 * @param $lang string : sentence name
			 * @param $vars array : vars
			 * @param $template : use template syntax or not
			 * @return string
			 * @since 3.0
			*/
			
			final public function useLang($lang, $vars = array(), $template = lang::USE_NOT_TPL){
				return $this->langInstance->lang($lang, $vars, $template);
			}

			/**
			 * create new instance of \system\lang
			 * @access public
			 * @return void
			 * @since 3.0
			*/
				
			final protected function _createLangInstance(){
				$this->langInstance = $this->lang();
			}
		}

		trait url{
			use resolve;
			
			private $_routeAttribute = array();

			/**
			 * get an url
			 * @access public
			 * @param $name string : name of the url. With .app. before, it use the default route file. Width .x., it use the module x
			 * @param array $var
			 * @param $absolute boolean : add absolute link
			 * @internal param array $vars
			 * @return string
			 * @since 3.0
			 */
			public function getUrl($name, $var = array(), $absolute = false){
				$routes = $this->resolve(RESOLVE_ROUTE, $name);

				if(isset($routes[0][''.$routes[1].''])){
					$route = $routes[0][''.$routes[1].''];

					$url = preg_replace('#\((.*)\)#isU', '<($1)>',  $route['url']);
					$urls = explode('<', $url);
					$result = '';
					$i=0;
							
					foreach($urls as $url){
						if(preg_match('#\)>#', $url)){
							if(count($var) > 0){
								$result.= preg_replace('#\((.*)\)>#U', $var[$i], $url);
								$i++;
							}
						}
						else{
							$result.=$url;
						}
					}

					$result = preg_replace('#\\\.#U', '.', $result);

					if($absolute == false)
						return FOLDER.$result;
					else
						return 'http://'.$_SERVER['HTTP_HOST'].FOLDER.$result;
				}
			}
		}
		
		abstract class constMime{
			const EXT_ZIP                   = 'application/gzip'                         ;
			const EXT_GZ                    = 'application/x-gzip'                       ;
			const EXT_GZ_COMPRESSED         = 'application/x-zip-compressed'             ;
			const EXT_PDF                   = 'application/pdf'                          ;
			const EXT_DEFAULT               = 'application/force-download'               ;
			const EXT_PNG                   = 'image/png'                                ;
			const EXT_GIF                   = 'image/gif'                                ;
			const EXT_JPG                   = 'image/jpeg'                               ;
			const EXT_ICO                   = 'image/vnd.microsoft.icon'                 ;
			const EXT_SVG                   = 'image/svg+xml'                            ;
			const EXT_JPEG                  = 'image/jpeg'                               ;
			const EXT_TXT                   = 'text/plain'                               ;
			const EXT_HTML                  = 'text/html'                                ;
			CONST TAR                       = 'application/x-tar'                        ;
			CONST TGZ                       = 'application/x-tar'                        ;
		}

		interface eventListener {
			public function implementedEvents();
		}
	}