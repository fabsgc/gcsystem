<?php
	/**
	 * @file : rubrique.class.php
	 * @author : fab@c++
	 * @description : class mère de l'application
	 * @version : 2.0 bêta
	*/
	
	class Gcsystem{
		use errorGc, langInstance, generalGc, urlRegex;                            //trait
		/* --- infos d'en tete -- */
		
		protected $_domXml                     ;
		protected $_nodeXml                    ;
		protected $_markupXml                  ;
		
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
		
		public function getDevTool($set){
			return $this->_devTool;
		}
		
		public function init(){
			if($this->_initInstance == 0){
				//$this->GzipinitOutputFilter();
				header('Content-Type: text/html; charset='.CHARSET.''); 
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
					if(isset($_GET['sousaction'])) { $_GET['sousaction']=htmlentities($_GET['sousaction']); }
					if(isset($_GET['id'])) { $_GET['id']=intval(htmlentities($_GET['id'])); }
					if(isset($_GET['page'])) { $_GET['page']=intval(htmlentities($_GET['page'])); }
					if(isset($_GET['search'])) { $_GET['search']=htmlentities($_GET['search']); }
					if(isset($_GET['design'])) { $_GET['design']=intval(htmlentities($_GET['design'])); }
					if(isset($_GET['menu'])) { $_GET['menu']=intval(htmlentities($_GET['menu'])); }
					if(isset($_GET['cat'])) { $_GET['cat']=intval(htmlentities($_GET['cat'])); }
					if(isset($_GET['soucat'])) { $_GET['soucat']=intval(htmlentities($_GET['soucat'])); }
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
			$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
			
			if($this->_domXml->load(ROUTE))
				$this->_addError('fichier ouvert : '.ROUTE);
			else
				$this->_addError('Le fichier '.ROUTE.' n\'a pas pu être ouvert');
				
			$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
			$routes = $this->_nodeXml->getElementsByTagName('route');
			
			foreach($routes as $route){
				$vars = array();
                
                if ($route->hasAttribute('vars')){
                    $vars = explode(',', $route->getAttribute('vars'));
                }
				
				$this->_routerInstance->addRoute(new routeGc($route->getAttribute('url'), $route->getAttribute('rubrique'), $route->getAttribute('action'), $vars));
			}
			
			if($matchedRoute = $this->_routerInstance->getRoute($this->getUri())){
				$_GET = array_merge($_GET, $matchedRoute->vars());
				$_GET['rubrique'] = $matchedRoute->module();
				$_GET['action'] = $matchedRoute->action();
			}
			else{
				$_GET['rubrique'] = "";
			}
		}
		
		public function route(){
			if(REWRITE == true) { $this->_getRubrique(); }
			
			if(isset($_GET['rubrique'])){
				$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
				if($this->_domXml->load(ROUTE)){
					$this->_addError('fichier ouvert : '.ROUTE);
				}
				else{
					$this->_addError('Le fichier '.ROUTE.' n\'a pas pu être ouvert');
				}
				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('route');
				
				$rubrique = "";
				
				foreach($this->_markupXml as $sentence){
					if ($sentence->getAttribute("rubrique") == $_GET['rubrique']){
						$rubrique =  $sentence->getAttribute("rubrique");
					}
				}
				
				if($rubrique!=""){
					if($this->_setRubrique($rubrique) == true){
						$class = new $rubrique();
						$class->init();
						
						if($_GET['action']!=""){
							$action = 'action'.ucfirst($_GET['action']);
							$class->$action();
						}
						elseif($_GET['action']=="" && is_callable(array($rubrique, 'actionDefault'))){
							$action = 'actionDefault'.ucfirst($_GET['action']);
							$class->$action();
						}
					}
				}
				else{
					$this->redirect404();
					$this->setErrorLog('errors', 'The rubric '.$_GET['rubrique'].' were not found');
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
			
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		public function GzipinitOutputFilter() {
			ob_start('ob_gzhandler');
			register_shutdown_function('ob_end_flush');
		}
		
		private function _setRubrique($rubrique){
			if(file_exists(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php')){
				require_once(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php');
				return true;
			} 
			else{ 
				$this->windowInfo('Erreur', RUBRIQUE_NOT_FOUND, 0, './'); 
				return false;
			}
		}
		
		/* ---------- FONCTIONS ------------- */
		
		public function setMaintenance(){
			$tpl = new templateGC(GCSYSTEM_PATH.'GCmaintenance', 'GCmaintenance', 0, $this->_lang);				
			$tpl->show();
		}
	}