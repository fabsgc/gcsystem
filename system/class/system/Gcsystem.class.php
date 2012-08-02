<?php
	/**
	 * @file : rubrique.class.php
	 * @author : fab@c++
	 * @description : class mère de l'application
	 * @version : 2.0 bêta
	*/
	
	class Gcsystem{
		use errorGc, langInstance, generalGc, urlRegex, domGc;                            //trait
		/* --- infos d'en tete -- */

		protected $_output                     ;
		protected $_configInstance             ;
		protected $_routerInstance             ;
		protected $_routeInstance              ;
		
		protected $_initInstance               ;
		protected $_devTool              = true;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct($lang=""){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
			$this->_configInstance = new configGc();
		}
		
		public function setDevTool($set){
			$this->_devTool = $set;
		}
		
		public function getDevTool(){
			return $this->_devTool;
		}
		
		public function init(){
			if($this->_initInstance == 0){
				$this->checkHeaderStream($this->getUri());
			
				switch(ENVIRONMENT){	
					case 'development' :		
						error_reporting(E_ALL | E_NOTICE);			
					break;

					case 'production' :	
						error_reporting(0);					
					break;					
				}
				
				$c = new TestErrorHandling(); 
				require_once(FUNCTION_GENERIQUE);
				
				if(SECUREGET == true && isset($_GET)){
					foreach($_GET as $cle => $val){
						$_GET[$cle] = htmlentities($val);
					}
				}
				else{
					if(isset($_GET['rubrique'])) { $_GET['rubrique']=htmlentities($_GET['rubrique']); }
					if(isset($_GET['action'])) { $_GET['action']=htmlentities($_GET['action']); }
					if(isset($_GET['id'])) { $_GET['id']=intval(htmlentities($_GET['id'])); }
					if(isset($_GET['page'])) { $_GET['page']=intval(htmlentities($_GET['page'])); }
					if(isset($_GET['token'])) { $_GET['token']=htmlentities($_GET['token']); }
				}
				
				if(SECUREPOST == true && isset($_POST)){
					foreach($_POST as $cle => $val){
						$_POST[$cle] = htmlentities($val);
					}
				}
				
				$this->setErrorLog('history','Page rewrite : http://'.$this->getHost().$this->getUri().' rubrique : '.$this->getServerName().$this->getPhpSelf().'?'.$this->getQuery().' / origine : '.$this->getReferer().' / IP : '.$this->getIp());
				$this->_initInstance = 1;
			}
		}
		
		public function addHeader($header){
            header($header);
        }
		
		public function redirect404(){
			$this->addHeader('HTTP/1.0 404 Not Found');
			$t= new templateGC(ERRORDUOCUMENT_PATH.'404', '404', '0', $this->_lang);
			$t->assign(array(
				'url' => substr($this->getUri(), strlen(FOLDER), strlen($this->getUri()))
			));
			$t->show();
        }
		
		protected function _getRubrique(){
			$this->_routerInstance = new routerGc($this);
			$this->_domXml = new DomDocument('1.0', CHARSET);
			
			if($this->_domXml->load(ROUTE)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
				$routes = $this->_nodeXml->getElementsByTagName('route');
				
				foreach($routes as $route){
					$vars = array();
					
					if ($route->hasAttribute('vars')){
						$vars = explode(',', $route->getAttribute('vars'));
					}
					
					$this->_routerInstance->addRoute(new routeGc($route->getAttribute('url'), $route->getAttribute('rubrique'), $route->getAttribute('action'), $vars));
				}
				
				if($matchedRoute = $this->_routerInstance->getRoute(preg_replace('`\?'.preg_quote($this->getQuery()).'`isU', '', $this->getUri()))){
					$_GET = array_merge($_GET, $matchedRoute->vars());
					$_GET['rubrique'] = $matchedRoute->module();
					$_GET['action'] = $matchedRoute->action();
				}
				else{
					$_GET['rubrique'] = "";
				}
			}
			else{
				$this->_addError('Le routage a échoué car le fichier "'.ROUTE.'" n\' pas pu être chargé', __FILE__, __LINE__);
			}
		}
		
		public function route(){
			if(REWRITE == true){ $this->_getRubrique(); }
			
			if(isset($_GET['rubrique'])){
				$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
				
				if($this->_domXml->load(ROUTE)){
					$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
					$this->_markupXml = $this->_nodeXml->getElementsByTagName('route');
					
					$rubrique = ""; //on initialise la variable
					
					foreach($this->_markupXml as $sentence){
						if ($sentence->getAttribute('rubrique') == $_GET['rubrique']){
							$rubrique =  $sentence->getAttribute('rubrique');
						}
					}
					
					if($rubrique!=""){
						$plugin = new pluginGc();
						
						if($this->_setRubrique($rubrique) == true){
							ob_start ();
								$class = new $rubrique($this->_lang);
								$class->init();
								
								if($_GET['action']!=""){
									if(is_callable(array($rubrique, 'action'.$_GET['action']))){
										$action = 'action'.ucfirst($_GET['action']);
										$class->$action();
										$this->_addError('Appel du contrôleur "action'.ucfirst($_GET['action']).'" de la rubrique "'.$rubrique.'" réussi', __FILE__, __LINE__);
									}
									else{
										$action = 'actionDefault';
										$class->$action();
										$this->_addError('L\'appel du contrôleur "action'.ucfirst($_GET['action']).'" de la rubrique "'.$rubrique.'" a échoué. Appel du contrôleur par défaut', __FILE__, __LINE__);
									}
								}
								elseif($_GET['action']=="" && is_callable(array($rubrique, 'actionDefault'))){
									$action = 'actionDefault';
									$class->$action();
								}
							$this->_output = ob_get_contents();
							ob_get_clean();
						}
						else{
							$this->_addError('L\'instanciation du contrôleur de la rubrique "'.$rubrique.'" a échoué', __FILE__, __LINE__);
						}
					}
					else{
						$this->redirect404();
						$this->setErrorLog('errors', 'The rubric '.$_GET['rubrique'].' were not found');
					}
				}				
			}
			else{
				if(is_file(RUBRIQUE_PATH.'index'.RUBRIQUE_EXT.'.php')){ 
					$this->_setRubrique('index');
				}
				else{
					$this->redirect404();
					$this->setErrorLog('errors', 'The rubric '.$_GET['rubrique'].' were not found');
				}
			}
		}
		
		public function checkHeaderStream($url){
			$extension = explode('.', $url);
			
			switch($extension[count($extension)-1]){
				case 'html':
					header('Content-Type: text/html; charset='.CHARSET.'');	
					$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__);
				break;
				
				case 'xml':
					header('Content-Type: text/xml; charset='.CHARSET.'');
					$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__);
				break;
				
				case 'json':
					header('Content-Type: application/json; charset='.CHARSET.'');
					$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__);
				break;
				
				default:
					header('Content-Type: text/html; charset='.CHARSET.'');
					$this->_addError('Content-Type : "Content-Type: text/html; charset='.CHARSET.'"', __FILE__, __LINE__);
				break;
			}
		}
		
		public function run(){
			echo $this->_output;
		}
			
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		public function GzipinitOutputFilter(){
			ob_start('ob_gzhandler');
			register_shutdown_function('ob_end_flush');
		}
		
		private function _setRubrique($rubrique){
			if(file_exists(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php')){
				if(file_exists(MODEL_PATH.$rubrique.MODEL_EXT.'.php')){
					require_once(MODEL_PATH.$rubrique.MODEL_EXT.'.php');
					$this->_addError('Chargement des fichiers "'.RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php" et "'.MODEL_PATH.$rubrique.MODEL_EXT.'.php"', __FILE__, __LINE__);
				}
				require_once(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php');
				return true;
			}
			else{ 
				$this->windowInfo('Erreur', $this->useLang('rubriquenotfound', array('rubrique' => $rubrique)), 0, './'); 
				$this->_addError('Echec lors du chargement des fichiers "'.RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php" et "'.MODEL_PATH.$rubrique.MODEL_EXT.'.php"', __FILE__, __LINE__);
				return false;
			}
		}
		
		public function setMaintenance(){
			$tpl = new templateGC(GCSYSTEM_PATH.'GCmaintenance', 'GCmaintenance', 0, $this->_lang);				
			$tpl->show();
		}
	}