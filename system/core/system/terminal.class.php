<?php
	/*\
	 | ------------------------------------------------------
	 | @file : terminal.class.php
	 | @author : fab@c++
	 | @description : terminal
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
	    class terminal{
			use error, facades, langInstance, resolve;

			protected $_argv = array();
			protected $_bdd           ;

			/**
			 * init terminall
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @since 3.0
 			 * @package system
			*/
			
			public function __construct(&$profiler, &$config, &$request, &$response, $lang){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_createLangInstance();

				$this->_parseArg($_SERVER['argv']);

				if(isset($this->_argv[0]))
					$this->_command();

				$this->_bdd = database::connect($GLOBALS['db']);
			}

			/**
			 * Parse terminal parameters to allow user to use spaces
			 * @access public
			 * @param $argv string
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			protected function _parseArg($argv){
				for($i = 0; $i < count($argv); $i++){
					if($argv[$i] != 'console'){
						if(!preg_match('#\[#', $argv[$i])){
							array_push($this->_argv, $argv[$i]);
						}
						else{
							$data = '';

							for($i; $i < count($argv); $i++){
								$data .= $argv[$i].' ';

								if(preg_match('#\]#', $argv[$i])){
									$data = str_replace(array('[', ']'), array('', ''), $data);
									array_push($this->_argv, trim($data));
									break;
								}
							}
						}
					}
				}
			}

			/**
			 * Terminal interpreter
			 * @access public
			 * @internal param string $argv
			 * @return void
			 * @since 3.0
			 * @package system
			 */

			protected function _command(){
				$class = '\system\terminal'.ucfirst($this->_argv[0]);

				if(isset($this->_argv[1])){
					$method = $this->_argv[1];
				}
				else if($this->_argv[0] == 'help'){
					$method = 'help';
				}
				else{
					$method = '';
				}

				if(method_exists($class, $method)){
					$instance = new $class($this->profiler, $this->config, $this->request, $this->response, $this->lang, $this->_bdd, $this->_argv);
					$instance->$method($this->_argv);
				}
				else{
					if(isset($this->_argv[1])){
						echo '[ERROR] unknow command "'.$this->_argv[0].' '.$this->_argv[1].'"';
					}
					else{
						echo '[ERROR] unknow command "'.$this->_argv[0].'"';
					}
				}
			}

			/**
			 * Remove directory content
			 * @access public
			 * @param $dir string : path
			 * @param $removeDir : remove subdirectories too
			 * @return void
			 * @since 3.0
 			 * @package system
			*/

			public static function rrmdir($dir, $removeDir = false) {
				if (is_dir($dir)) {
					$objects = scandir($dir);
						foreach ($objects as $object) {
							if ($object != "." && $object != "..") {
								if (filetype($dir."/".$object) == "dir"){
									terminal::rrmdir($dir."/".$object, $removeDir); 

									if($removeDir == true){
										rmdir($dir."/".$object.'/');
									}
								}
								else{
									unlink ($dir."/".$object);
								}
							}
						}
					reset($objects);
				}
			}

			/**
			 * Desctructor
			 * @access public
			 * @since 3.0
 			 * @package system
			*/
			
			public function __destruct(){
			}
		}

		class command{
			use error, facades, langInstance, resolve;

			/**
			 * pdo instance
			 * @var
			*/

			protected $_bdd;

			/**
			 * command content
			 * @var
			*/

			protected $_argv;

			/**
			 * init terminal command
			 * @access public
			 * @param &$profiler \system\profiler
			 * @param &$config \system\config
			 * @param &$request \system\request
			 * @param &$response \system\response
			 * @param $lang string
			 * @param $bdd
			 * @param $argv
			 * @since 3.0
			 * @package system
			*/

			public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd, $argv){
				$this->profiler = $profiler;
				$this->config   =   $config;
				$this->request  =  $request;
				$this->response = $response;
				$this->lang     =     $lang;
				$this->_createLangInstance();
				$this->_bdd = $bdd;
				$this->_argv = $argv;
			}
		}

		class terminalCreate extends command{
			public function module(){
				$src = '';
				$controllers = array();

				//chosse the module name
				while(1==1){
					echo ' - choose module name : ';
					$src = argvInput::get(STDIN);

					if(!file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/')){
						break;
					}
					else{
						echo "[ERROR] this module already exists\n";
					}
				}

				//chosse the number of controllers
				while(1==1){
					echo ' - add a controller (keep empty to stop) : ';
					$controller = argvInput::get(STDIN);
						
					if($controller != ''){
						if(!in_array($controller, $controllers)){
							array_push($controllers, $controller);
						}
						else{
							echo "[ERROR] you have already chosen this controller\n";
						}
					}
					else{
						if(count($controllers) > 0){
							break;
						}
						else{
							echo "[ERROR] you must add at least one controller\n";
						}
					}
				}

				//load all template to fill the new files
				$tpl['cron'] = $this->template('.app/system/module/cron', 'terminalCreateCron');
				$tpl['define'] = $this->template('.app/system/module/define', 'terminalCreateDefine');
				$tpl['lang'] = $this->template('.app/system/module/lang', 'terminalCreateLang');
				$tpl['library'] = $this->template('.app/system/module/library', 'terminalCreateLibrary');
				$tpl['route'] = $this->template('.app/system/module/route', 'terminalCreateRoute');
				$tpl['firewall'] = $this->template('.app/system/module/firewall', 'terminalCreateFirewall');
				$tpl['firewall']->assign('src', $src);

				//creation of directories and files
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_EVENT_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH);
				mkdir(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_TEMPLATE_PATH);

				mkdir(DOCUMENT_ROOT.WEB_PATH.$src);
				mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_CSS_PATH);
				mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_FILE_PATH);
				mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_IMAGE_PATH);
				mkdir(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_JS_PATH);

				file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_CSS_PATH.'/index.html', '');
				file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_FILE_PATH.'/index.html', '');
				file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_IMAGE_PATH.'/index.html', '');
				file_put_contents(DOCUMENT_ROOT.WEB_PATH.$src.'/'.WEB_JS_PATH.'/index.html', '');

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/.htaccess', 'Deny from all');
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_EVENT_PATH.'.htaccess', 'Deny from all');
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH.'.htaccess', 'Deny from all');
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LIBRARY_PATH.'.htaccess', 'Deny from all');
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_TEMPLATE_PATH.'.htaccess', 'Deny from all');

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'cron.xml', $tpl['cron']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'define.xml', $tpl['define']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'firewall.xml', $tpl['firewall']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'library.xml', $tpl['library']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', '');

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_LANG_PATH.'fr.xml', $tpl['lang']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));

				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_FUNCTION_PATH, '');

				$routeGroup = '';

				foreach ($controllers as $value) {
					$tpl['routeGroup'] = $this->template('.app/system/module/routeGroup', 'terminalCreateRouteGroup'.$value);
					$tpl['routeGroup']->assign(array('src' => $src, 'controller' => $value));
					$routeGroup .= $tpl['routeGroup']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING);

					$tpl['controller'] = $this->template('.app/system/module/controller', 'terminalCreateController'.$value);
					$tpl['controller']->assign(array('src' => $src, 'controller' => $value));
					$tpl['model'] = $this->template('.app/system/module/model', 'terminalCreateModel'.$value);
					$tpl['model']->assign(array('src' => $src, 'model' => $value));

					file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_CONTROLLER_PATH.$value.EXT_CONTROLLER.'.php', $tpl['controller']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
					file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_MODEL_PATH.$value.EXT_MODEL.'.php',  $tpl['model']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));
				}

				$tpl['route']->assign('route', $routeGroup);
				file_put_contents(DOCUMENT_ROOT.SRC_PATH.$src.'/'.SRC_RESOURCE_CONFIG_PATH.'route.xml', $tpl['route']->show(template::TPL_COMPILE_ALL, template::COMPILE_TO_STRING));

				$exist = false;
				$xml = simplexml_load_file(APP_CONFIG_SRC);
				$datas =  $xml->xpath('//src');

				foreach ($datas as $data) {
					if($data['name'] == $src)
						$exist = true;
				}

				if($exist == false){
					$node = $xml->addChild('src', null);
					$node->addAttribute('name', $src);

					$dom = new \DOMDocument("1.0");
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput = true;
					$dom->loadXML($xml->asXML());
					$dom->save(APP_CONFIG_SRC);
				}

				echo ' - the module has been successfully created';
			}

			public function controller(){
				
			}

			public function entity(){
				if(DATABASE){
					echo ' - choose a table (*) : ';
					$table = argvInput::get(STDIN);

					echo ' - the entity has been successfully created';
				}
				else{
					echo ' - you\'re not logged to any database';
				}
			}
		}

		class terminalClear extends command{
			public function log(){
				terminal::rrmdir(APP_LOG_PATH);
				echo ' - log files were successfully deleted';
			}

			public function cache(){
				terminal::rrmdir(APP_CACHE_PATH);
				echo ' - cache files were successfully deleted';
			}
		}

		class terminalDelete extends command{
			public function module(){
				//choose the module name
				while(1==1){
					echo ' - choose the module you want to delete : ';
					$src = argvInput::get(STDIN);

					if(file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/')){
						break;
					}
					else{
						echo "[ERROR] this module doesn't exist\n";
					}
				}

				$xml = simplexml_load_file(APP_CONFIG_SRC);
				$datas =  $xml->xpath('//src');

				foreach ($datas as $data) {
					if($data['name'] == $src){
						$dom = dom_import_simplexml($data);
        				$dom->parentNode->removeChild($dom);
					}
				}

				$xml->asXML(APP_CONFIG_SRC);
				$dom = new \DOMDocument("1.0");
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->load(APP_CONFIG_SRC);
				$dom->save(APP_CONFIG_SRC);

				terminal::rrmdir(SRC_PATH.$src, true);
				terminal::rrmdir(WEB_PATH.$src, true);
				rmdir(SRC_PATH.$src);
				rmdir(WEB_PATH.$src);

				echo ' - the module has been successfully delete';
			}

			public function controller(){

			}
		}

		class terminalHelp extends command{
			public function help(){
				echo " - create module\n";
				echo " - create controller\n";
				echo " - create entity\n";
				echo " - delete module\n";
				echo " - delete controller\n";
				echo " - clear cache\n";
				echo " - clear log";
			}
		}

		class argvInput{
			public static function get(){
				$data = fgets(STDIN);
				$data = substr($data, 0, -2);

				return $data;
			}
		}
	}