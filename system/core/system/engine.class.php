<?php
	/*\
 	 | ------------------------------------------------------
	 | @file : engine.class.php
	 | @author : fab@c++
	 | @description : class mère de l'application
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class engine{
			use error, langInstance, general, urlRegex;

			private $_output                        ;
			private $_configInstance                ;
			private $_cronInstance                  ;
			private $_routerInstance                ;
			private $_routeInstance                 ;
			
			private $_initInstance                  ;
			private $_devTool              = true   ;

			private $_cacheRoute           = 0      ;
			private $_cache                = null   ;

			/**
			 * Constructeur de la classe. Lancement du moteur
			 * @access	public
			 * @param $lang string
			 * @since 2.0
			*/
			public  function __construct($lang=""){
				if($lang == ""){ 
					$this->_lang=$this->getLangClient(); 
				} 
				else { 
					$this->_lang = $lang; 
				}

				$this->_createLangInstance();
			}

			/**
			 * Constructeur de la classe. Initialisation du moteur
			 * @access	public
			 * @return void
			 * @since 2.0
			*/

			public function init(){
				if($this->_initInstance == 0){
					$this->_checkConfigFile();
					$this->_setEventListeners();
					$this->_checkHeaderStream($this->getUri());
					$this->_checkEnvironment();
					$this->_checkError();
					$this->_checkFunctionGenerique();
					$this->_checkSecureVar();
					$this->_initRoute();
					$this->setErrorLog(LOG_HISTORY,'Page rewrite : http://'.$this->getHost().$this->getUri().' contrôleur : '.$this->getServerName().$this->getPhpSelf().'?'.$this->getQuery().' / origine : '.$this->getReferer().' / IP : '.$this->getIp());
					$this->_configInstance = new config();
					$this->_initInstance = 1;
					date_default_timezone_set(TIMEZONE);
				}
			}

			/**
			 * Récupération du controller à partir de l'url
			 * @access	public
			 * @return void
			 * @since 2.0
			*/

			private function _getController(){
				$GLOBALS['appDev']-> setTimeExecUser('gcs route');
				$this->_routerInstance = new router($this);

				$this->_addError('Le fichier de route " '.ROUTE.'" a bien été chargé', __FILE__, __LINE__, INFORMATION);

				$xml = simplexml_load_file(ROUTE);
				$result = $xml->xpath('//route');

				foreach ($result as $value) {
					foreach ($this->_routeAttribute as $attribute) {
						$name = $attribute['name'];

						if(is_object($value[$name]))
							$data[$name] = $value[$name]->__toString();
					}

					$data = $this->_groupGetParent($value, $data);

					$vars = explode(',', $data['vars']);
					$controller = explode('.', $data['action'])[0];
					$action = explode('.', $data['action'])[1];

					$this->_routerInstance->addRoute(new routeGc($data['url'], $controller, $action, $data['name'], $data['cache'], $vars));
				}

				if($matchedRoute = $this->_routerInstance->getRoute(preg_replace('`\?'.preg_quote($this->getQuery()).'`isU', '', $this->getUri()))){
					$_GET = array_merge($_GET, $matchedRoute->vars());
					$_GET['controller']  = $matchedRoute->controller();
					$_GET['action']    = $matchedRoute->action();
					$_GET['pageid']    = $matchedRoute->name();

					if(CACHE_ENABLED == true)
						$this->_cacheRoute = $matchedRoute->cache();
					else
						$this->_cacheRoute = 0;

					if($_GET['action'] == '')
						$_GET['action'] = 'default';
				}
				else{
					$_GET['controller'] = "";
				}
				
				$GLOBALS['appDev']-> setTimeExecUser('gcs route');
			}

			/**
			 * Routage et instanciation du controller
			 * @access	public
			 * @return void
			 * @since 2.0
			*/

			public function route(){
				$GLOBALS['appDev']-> setTimeExecUser('gcs controller');
				if(REWRITE == true){ $this->_getController(); }

				if(isset($_GET['controller']) && $_GET['controller'] != ''){
					$controller = $_GET['controller'];

					$helper = new helper();
					$this->_cronInstance  = new cron(); //les crons ont besoin des plugins

					if($this->_cacheRoute > 0){ //le cache de la page est supérieur à 0 secondes et le rewrite activé
						if($this->_setRubrique($controller) == true){  //on inclut les fichiers necéssaires à l'utilisation d'un contrôleur
							$class = new $controller($this->_lang);

							if(SECURITY == false || $class->setFirewall() == true){
								if(ANTISPAM == false || $class->setAntispam() == true){
									$class->loadModel();
									$this->_cache = new cache('page_'.preg_replace('#\/#isU', '-slash-', $this->getUri()), "", $this->_cacheRoute);

									if($this->_cache->isDie() == true){
										ob_start();
											$class->init();

											if(method_exists($class, 'action'.ucfirst($_GET['action']))){
												$action = 'action'.ucfirst($_GET['action']);

												try{
													$class->$action();
													$this->_addError('Appel de l\'action "action'.ucfirst($_GET['action']).'" du contrôleur "'.$controller.'" réussi', __FILE__, __LINE__, INFORMATION);
												}
												catch(Exception $e){
													$this->_addError($e->getMessage(), __FILE__, __LINE__, FATAL);
												}
											}
											else{
												$action = 'actionDefault';
												
												try{
													$class->$action();
													$this->_addError('Appel de l\'action "action'.ucfirst($_GET['action']).'" du contrôleur "'.$controller.'" réussi', __FILE__, __LINE__, WARNING);
												}
												catch(Exception $e){
													$this->_addError($e->getMessage(), __FILE__, __LINE__, FATAL);
												}	
											}

											$class->end();
										$output = ob_get_contents();
										ob_get_clean();

										$this->_cache->setVal($this->_output);
										$this->_cache->setCache();
									}
									else{
										$this->_output = $this->_cache->getCache();
									}
								}
								else{
									$this->_addError('L\'antispam semble avoir détecté une erreur', __FILE__, __LINE__, ERROR);
								}
							}
							else{
								$this->_addError(' Le parefeu semble avoir détecté une erreur', __FILE__, __LINE__, ERROR);
							}
						}
						else{
							$this->_addError('L\'instanciation du contrôleur "'.$controller.'" a échoué', __FILE__, __LINE__, FATAL);
							$this->_addError('Le contrôleur '.$_GET['controller'].' n\'a pas été trouvé', __FILE__,  __LINE__, FATAL);
							$this->redirect404();
						}
					}
					else{
						if($this->_setRubrique($controller) == true){
							$class = new $controller($this->_lang);

							if(SECURITY == false || $class->setFirewall() == true){
								if(ANTISPAM == false || $class->setAntispam() == true){
									$class->loadModel();

									ob_start();
										$class->init();

										if(method_exists($class, 'action'.ucfirst($_GET['action']))){
											$action = 'action'.ucfirst($_GET['action']);
											
											try{
												$class->$action();
												$this->_addError('Appel de l\'action "action'.ucfirst($_GET['action']).'" du contrôleur "'.$controller.'" réussi', __FILE__, __LINE__, INFORMATION);
											}
											catch(Exception $e){
												echo 'sd';
												$this->_addError($e->getMessage(), __FILE__, __LINE__, FATAL);
											}	
										}
										else{
											$action = 'actionDefault';

											try{
												$class->$action();
												$this->_addError('Appel de l\'action "action'.ucfirst($_GET['action']).'" du contrôleur "'.$controller.'" réussi', __FILE__, __LINE__, WARNING);
											}
											catch(Exception $e){
												$this->_addError($e->getMessage(), __FILE__, __LINE__, FATAL);
											}	
										}

										$class->end();
									$this->_output = ob_get_contents();
									ob_get_clean();
								}
								else{
									$this->_addError('L\'antispam semble avoir détecté une erreur', __FILE__, __LINE__, ERROR);
								}
							}
							else{
								$this->_addError('Le parefeu semble avoir détecté une erreur', __FILE__, __LINE__, ERROR);
							}
						}
						else{
							$this->_addError('L\'instanciation du contrôleur "'.$controller.'" a échoué', __FILE__, __LINE__, FATAL);
							$this->_addError('Le contrôleur '.$_GET['controller'].' n\'a pas été trouvé', __FILE__,  __LINE__, FATAL);
							$this->redirect404();
						}
					}
				}
				else{
					$this->_addError('Le contrôleur \'inconnue\' n\'a pas été instancié car le routage a echoué. Requête http : http://'.$this->getHost().$this->getUri(), __FILE__, __LINE__, FATAL);
					$this->_addError('Le contrôleur '.$_GET['controller'].' n\'a pas été trouvé car le routage a échoué. URL : http://'.$this->getHost().$this->getUri(), __FILE__,  __LINE__, FATAL);
					$this->redirect404();
				}
				$GLOBALS['appDev']-> setTimeExecUser('gcs controller');
			}

			/**
			 * Routage et instanciation du controller
			 * @access private
			 * @param $controller string : nom du controller
			 * @return bool
			 * @since 2.0
			*/

			private function _setRubrique($controller){
				if(file_exists(CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php')){
					if(file_exists(MODEL_PATH.$controller.MODEL_EXT.'.php')){
						require_once(MODEL_PATH.$controller.MODEL_EXT.'.php');
						$this->_addError('Chargement des fichiers "'.CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php" et "'.MODEL_PATH.$controller.MODEL_EXT.'.php"', __FILE__, __LINE__, INFORMATION);
					}
					require_once(CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php');
					return true;
				}
				else{ 
					$this->_addError($this->useLang('gc_controllernotfound', array('controller' => $controller)), __FILE__, __LINE__, FATAL);
					$this->_addError('Echec lors du chargement des fichiers "'.CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php" et "'.MODEL_PATH.$controller.MODEL_EXT.'.php"', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * Lance l'affichage de la page
			 * @accesspublic
			 * @return void
			 * @since 2.0
			*/
			
			public function run(){
				if(MINIFY_OUTPUT_HTML == true && $this->checkContentType() == true){
					$this->_output = $this->minifyHtml($this->_output);
				}

				echo $this->_output;
				$this->_addErrorHr();

				if($this->checkContentType() == false){
					$GLOBALS['appDev']->setShow(false);
				}
			}

			/**
			 * renvoie false si on a pas affaire à du html et si on a une directive content-type
			 * @access public
			 * @return boolean
			 * @since 2.0
			*/

			private function checkContentType(){
				$header = headers_list();

				if(in_array('Content-Type: text/html; charset=UTF-8', $header)){
					return true;
				}

				foreach ($header as $value) {
					if(preg_match('#content-type#', $value)){
						return false; //on a un content-type qui n'est pas html
					}
				}

				return false;
			}

			/**
			 * définis le type de contenu
			 * @access public
			 * @return void
			 * @since 2.0
			*/
			
			private function _checkHeaderStream($url){
				$extension = explode('.', $url);
				
				switch($extension[count($extension)-1]){
					case 'html':
						header('Content-Type: text/html; charset='.CHARSET.'');
						$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__, INFORMATION);
					break;
					
					case 'xml':
						header('Content-Type: text/xml; charset='.CHARSET.'');
						$GLOBALS['appDev']->setShow(false);
						$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__, INFORMATION);
					break;
					
					case 'json':
						header('Content-Type: application/json; charset='.CHARSET.'');
						$GLOBALS['appDev']->setShow(false);
						$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__, INFORMATION);
					break;
					
					default:
						header('Content-Type: text/html; charset='.CHARSET.'');
						$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__, INFORMATION);
					break;
				}
			}
				
			private function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			private function useLang($sentence, $var = array()){
				return $this->_langInstance->loadSentence($sentence, $var);
			}

			/**
			 * définis le type d'environnement : développement ou production
			 * @access public
			 * @return void
			 * @since 2.0
			*/
			
			private function _checkEnvironment(){
				switch(ENVIRONMENT){	
					case 'development' :		
						error_reporting(E_ALL | E_NOTICE);			
					break;

					case 'production' :	
						error_reporting(0);					
					break;					
				}
			}
			
			private function _checkError(){
				$c = new TestErrorHandling(); 
			}
			
			private function _checkFunctionGenerique(){
				require_once(FUNCTION_GENERIQUE);
			}

			/**
			 * Sécurise les entrées POST et GET
			 * @access public
			 * @return void
			 * @since 2.0
			*/
			
			private function _checkSecureVar(){
				if(SECUREGET == true && isset($_GET)){
					foreach($_GET as $cle => $val){
						$_GET[$cle] = htmlentities($val);
					}
				}
				
				if(SECUREPOST == true && isset($_POST)){
					foreach($_POST as $cle => $val){
						$_POST[$cle] = htmlentities($val);
					}
				}
			}

			/**
			 * Vérifie l'intégrité des fichiers de configuration
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			private function _checkConfigFile(){
				$dom = new \DomDocument('1.0', CHARSET);
				
				if(!$dom->loadXml(file_get_contents(ROUTE))){
					$this->_addError('Le fichier '.ROUTE.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(MODOCONFIG))){
					$this->_addError('Le fichier '.MODOCONFIG.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(APPCONFIG))){
					$this->_addError('Le fichier '.APPCONFIG.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(HELPER))){
					$this->_addError('Le fichier '.HELPER.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(FIREWALL))){
					$this->_addError('Le fichier '.FIREWALL.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(ASPAM))){
					$this->_addError('Le fichier '.ASPAM.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(ADDON))){
					$this->_addError('Le fichier '.ADDON.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(CRON))){
					$this->_addError('Le fichier '.CRON.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}

				if(!$dom->loadXml(file_get_contents(ERRORPERSO))){
					$this->_addError('Le fichier '.ERRORPERSO.' n\'a pas pu être ouvert', __FILE__, __LINE__, FATAL);
				}
			}

			public function setMaintenance(){
				$tpl = new template(GCSYSTEM_PATH.'maintenance', 'GCmaintenance', 0, $this->_lang);				
				$tpl->show();
			}

			private function _setEventListeners(){
				$dir = new \helper\dir(EVENT_PATH);
				$GLOBALS['eventListeners'] = array();
				
				foreach ($dir->getDirArbo() as $value) {
					$value = '\event\\'.preg_replace('#'.preg_quote(EVENT_PATH).'(.+)'.preg_quote(EVENT_EXT).preg_quote('.php').'#isU', '$1', $value);
					$value = preg_replace('#/#', '\\', $value);
					array_push($GLOBALS['eventListeners'], new $value());
				}
			}

			public  function __destruct(){
			}
		}
	}