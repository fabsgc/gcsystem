<?php
	/*\
	 | ------------------------------------------------------
	 | @file : engine.class.php
	 | @author : fab@c++
	 | @description : engine
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class engine{
			use error, langInstance, facades, resolve;

			protected $_controller;
			protected $_route = false;
			
			/**
			 * constructor
			 * @access public
			 * @since 3.0
			*/

			public function __construct ($mode = MODE_HTTP){
				if (!defined('CONSOLE_ENABLED'))
					define('CONSOLE_ENABLED', $mode);

				$this->_setErrorHandler();
				$this->request  = new request();
				$this->response = new response();
				$this->profiler = new profiler();
				$this->config   = new config();
				$this->lang = LANG;
			}

			/**
			 * initialization of the engine
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			public function init(){
				if(MAINTENANCE == false){
					date_default_timezone_set(TIMEZONE);
					$this->_setEnvironment();
					$this->_route();

					if($this->_route == true){
						$this->_setSecure();
						$this->_setCron();
						$this->_setDefine();
						$this->_setLibrary();
						$this->_setEvent();
						$this->_setFunction();
						$this->_setFunction($this->request->src);
						$this->_setCron($this->request->src);
						$this->_setDefine($this->request->src);
						$this->_setLibrary($this->request->src);
						$this->_setEvent($this->request->src);
					}		
				}
			}

			/**
			 * initialization of the engine for cron
			 * @access public
			 * @param $src string
			 * @param $controller string
			 * @param $action string
			 * @return void
			 * @since 3.0
			*/

			public function initCron($src, $controller, $action){
				if(MAINTENANCE == false){
					$this->_routeCron($src, $controller, $action);

					if($this->_route == true){
						$this->_setFunction($src);
						$this->_setEvent();
						$this->_setCron($this->request->src);
						$this->_setDefine($this->request->src);
						$this->_setLibrary($this->request->src);
						$this->_setEvent($this->request->src);
					}		
				}
			}

			/**
			 * initialization of the console
			 * @access public
			 * @return void
			 * @since 3.0
			*/
			public function console(){
				$this->terminal();
			}

			/**
			 * routing
			 * @access private
			 * @return this
			 * @since 3.0
			*/

			private function _route(){
				$this->profiler->addTime('route');

				$router = new router($this);

				foreach ($this->config->config['route'] as $key => $value) {
					foreach ($value as $data) {
						$vars = explode(',', $data['vars']);
						$controller = explode('.', $data['action'])[0];
						$action = explode('.', $data['action'])[1];

						$router->addRoute(new route($data['url'], $controller, $action, $data['name'], $data['cache'], $vars, $key, $data['logged'], $data['access']));
					}
				}

				if($matchedRoute = $router->getRoute(preg_replace('`\?'.preg_quote($_SERVER['QUERY_STRING']).'`isU', '', $_SERVER['REQUEST_URI']))){
					$_GET = array_merge($_GET, $matchedRoute->vars());

					$this->request->name = $matchedRoute->name();
					$this->request->src = $matchedRoute->src();
					$this->request->controller = $matchedRoute->controller();
					$this->request->action = $matchedRoute->action();
					$this->request->logged = $matchedRoute->logged();
					$this->request->access = $matchedRoute->access();

					if(CACHE_ENABLED == true && $matchedRoute->cache() != '')
						$this->request->cache = $matchedRoute->cache();
					else
						$this->request->cache = 0;

					if($this->request->action == '')
						$this->request->action = 'default';

					$this->_route = true;
				}

				$this->profiler->addTime('route', profiler::USER_END);

				return $this;
			}

			/**
			 * routing with cron
			 * @access private
			 * @param $src string
			 * @param $controller string
			 * @param $action string
			 * @return void
			 * @since 3.0
			*/

			private function _routeCron($src, $controller, $action){
				$this->profiler->addTime('route cron : '.$src.'/'.$controller.'/'.$action);

				$this->request->name = '-'.$src.'_'.$controller.'_'.$action;
				$this->request->src = $src;
				$this->request->controller = $controller;
				$this->request->action = $action;
				$this->_route = true;

				$this->profiler->addTime('route cron : '.$src.'/'.$controller.'/'.$action, profiler::USER_END);
			}

			/**
			 * init controller
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			protected function _controller(){
				if($this->_setControllerFile($this->request->src, $this->request->controller) == true){
					$className = "\\".$this->request->src."\\".$this->request->controller;
					$class = new $className($this->profiler, $this->config, $this->request, $this->response, $this->lang);

					if(SECURITY == false || ($this->request->logged == '*' && $this->request->access == '*') || $class->setFirewall() == true){
						if(SPAM == false || $class->setSpam() == true){
							if($this->request->cache > 0){
								$cache = $this->cache('page_'.preg_replace('#\/#isU', '-slash-', $this->request->env('REQUEST_URI')), $this->request->cache);

								if($cache->isDie() == true){
									$class->model();

									$output = $this->_action($class);
									$this->response->page($output);

									$cache->setContent($output);
									$cache->setCache();
								}
								else{
									$this->response->page($cache->getCache());
								}
							}
							else{
								$class->model();
								$output = $this->_action($class);
								$this->response->page($output);
							}
						}
						else{
							$this->addError('The spam filter has detected an error', __FILE__, __LINE__, ERROR_ERROR);
						}
					}
					else{
						$this->addError('The firewall has detected an error', __FILE__, __LINE__, ERROR_ERROR);
					}
				}
				else{
					throw new exception("Can't include controller and model from module ".$this->request->src, 1);
				}
			}

			/**
			 * call action from controller
			 * @param &$class : controller instance reference
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function _action(&$class){
				ob_start();
					$class->init();

					if(method_exists($class, 'action'.ucfirst($this->request->action))){
						$action = 'action'.ucfirst($this->request->action);
						$output = $class->$action();
						$this->addError('Action "action'.ucfirst($this->request->action).'" from "'.$this->request->controller.'" called successfully', __FILE__, __LINE__, ERROR_INFORMATION);
					}
					else{
						throw new exception('The requested action "'.$this->request->action.'" from "'.$this->request->controller.'" doesn\'t exist', 1);
					}
	
					$class->end();
					$output = ob_get_contents().$output;
				ob_get_clean();

				return $output;
			}

			/**
			 * include the module
			 * @access protected
			 * @param $src string
			 * @param $controller string
			 * @return boolean
			 * @since 3.0
			*/

			protected function _setControllerFile($src, $controller){
				$controllerPath = SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.$controller.EXT_CONTROLLER.'.php';
				$modelPath = SRC_PATH.$src.'/'.SRC_MODEL_PATH.$controller.EXT_MODEL.'.php';

				if(file_exists($controllerPath) && file_exists($modelPath)){
					require_once($controllerPath);
					require_once($modelPath);

					return true;
				}
				else{
					return false;
				}
			}

			/**
			 * display the page
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			public function run(){
				$lang = $this->lang();

				if(MAINTENANCE == false){
					if($this->_route == false){
						$this->response->status(404);
						$this->addError('routing failed : http://'.$this->request->env('HTTP_HOST').$this->request->env('REQUEST_URI'), __FILE__, __LINE__, ERROR_WARNING);
					}
					else{
						$this->_controller();
					}
					
					$this->response->run($this->profiler, $this->config, $this->response);
					$this->addErrorHr(LOG_ERROR);
					$this->addErrorHr(LOG_SYSTEM);
					$this->_setHistory('');

					if(MINIFY_OUTPUT_HTML == true && preg_match('#text/html#isU', $this->response->contentType()))
						$this->response->page($this->_minifyHtml($this->response->page()));

					if(ENVIRONMENT == 'development' && PROFILER == true)
						$this->profiler->profiler($this->request, $this->response);
				}
				else{
					$this->response->page($this->maintenance());
				}

				echo $this->response->page();
			}

			/**
			 * display the page for a cron
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			public function runCron(){
				$lang = $this->lang();

				if(MAINTENANCE == false){
					$this->_controller();
					$this->_setHistory('CRON');

					if(ENVIRONMENT == 'development' && PROFILER == true)
						$this->profiler->profiler($this->request, $this->response);
				}

				echo $this->response->page();
			}

			/**
			 * get maintenance template
			 * @access public
			 * @return void
			 * @since 3.0
			*/

			private function maintenance(){
				$tpl = $this->template('.app/system/maintenance', 'maintenance');
				return $tpl->show();
			}

			/**
			 * set error environment
			 * @access private
			 * @return void
			 * @since 3.0
			*/
			
			private function _setEnvironment(){
				switch(ENVIRONMENT){	
					case 'development' :		
						error_reporting(E_ALL | E_NOTICE);
					break;

					case 'production' :	
						error_reporting(0);	
					break;					
				}
			}

			/**
			 * enable error handling
			 * @access private
			 * @return void
			 * @since 3.0
			*/

			private function _setErrorHandler(){
				$error = new errorHlander(); 
			}

			/**
			 * set cron
			 * @access private
			 * @param $src string 
			 * @return void
			 * @since 3.0
			*/

			private function _setCron($src = null){
				if($src == null){
					$cron = new \system\cron($this->profiler, $this->config, $this->request, $this->lang, APP_CONFIG_CRON);
				}
				else{
					$cron = new \system\cron($this->profiler, $this->config, $this->request, $this->lang, SRC_PATH.$src.'/'.SRC_CONFIG_CRON);
				}
			}

			/**
			 * set define
			 * @access private
			 * @param $src string 
			 * @return void
			 * @since 3.0
			*/

			private function _setDefine($src = null){
				if($src == null){
					$define = $this->define('app');
				}
				else{
					$define = $this->define($src);
				}
			}

			/**
			 * set library
			 * @access private
			 * @param $src string 
			 * @return void
			 * @since 3.0
			*/

			private function _setLibrary($src = null){
				if($src == null){
					$library = $this->library('app');
				}
				else{
					$library = $this->library($src);
				}
			}

			/**
			 * escape GET and POST (htmlentities)
			 * @access private
			 * @return void
			 * @since 3.0
			*/

			private function _setSecure(){
				if(SECURE_GET == true && isset($_GET)){
					$_GET = $this->_setSecureArray($_GET);
				}
				
				if(SECURE_POST == true && isset($_POST)){
					$_POST = $this->_setSecureArray($_POST);
				}
			}

			/**
			 * escape array (htmlentities)
			 * @access private
			 * @return mixed
			 * @since 3.0
			*/

			private function _setSecureArray($var){
				if(is_array($var)){
					foreach ($var as $key => $value) {
						$var[''.$key.''] = $this->_setSecureArray($value);
					}
				}
				else{
					$var = htmlentities($var);
				}

				return $var;
			}

			/**
			 * set event
			 * @access private
			 * @return void
			 * @since 3.0
			*/
			
			private function _setEvent($src = null){
				if(empty($GLOBALS['eventListeners'])){
					$GLOBALS['eventListeners'] = array();
				}

				if($src != null){
					$path = SRC_PATH.$src.'/'.SRC_RESOURCE_EVENT_PATH;
				}
				else{
					$path = APP_RESOURCE_EVENT_PATH;
				}

				if ($handle = opendir($path)) {
					while (false !== ($entry = readdir($handle))) {
						if(preg_match('#(\.php$)$#isU', $entry)){
							if(!array_key_exists($path.$entry, $GLOBALS['eventListeners'])){
								include_once($path.$entry);

								$event = '\event\\'.preg_replace('#(.+)'.preg_quote(EXT_EVENT.'.php').'#', '$1', $entry);
								$event = preg_replace('#'.preg_quote('/').'#', '\\', $event);

								if($src == null)
									$event = '\app'.$event;
								else
									$event = '\\'.$src.$event;


								$GLOBALS['eventListeners'][''.$path.$entry.''] = new $event();
							}
						}
					}

					closedir($handle);
				}
			}

			/**
			 * set function.php
			 * @access private
			 * @param $src string
			 * @return void
			 * @since 3.0
			*/

			private function _setFunction($src = null){
				if($src == null){
					require_once(APP_FUNCTION);
				}
				else{
					require_once(SRC_PATH.$src.'/'.SRC_CONTROLLER_FUNCTION_PATH);
				}
			}

			/**
			 * log request in history
			 * @access private
			 * @param $message string
			 * @return void
			 * @since 3.0
			*/

			private function _setHistory($message){
				$this->addError('URL : http://'.$this->request->env('HTTP_HOST').$this->request->env('REQUEST_URI').' ('.$this->response->status().
					') / SRC "'.$this->request->src.'" / CONTROLLER "'.$this->request->controller.
					'" / ACTION "'.$this->request->action.'" / CACHE "'.$this->request->cache.
					'" / ORIGIN : '.$this->request->env('HTTP_REFERER').' / IP : '.$this->request->env('REMOTE_ADDR'). ' / '.$message, 0, 0, 0, LOG_HISTORY);
			}

			/**
			 * minify html
			 * @access private
			 * @param string buffer
			 * @return void
			 * @since 3.0
			*/

			private function _minifyHtml($buffer) {
				$search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/\>(\s)+/s', '/(\s)+\</s');
				$replace = array('> ', ' <', '> ', ' <');
				$buffer = preg_replace($search, $replace, $buffer);

				return $buffer;
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