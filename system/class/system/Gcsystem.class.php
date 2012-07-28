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
				
				if(CONNECTBDD == true) { $GLOBALS['base']=$this->_connectDatabase($GLOBALS['db']); }
				
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
				
				$this->setErrorLog('history','Page rewrite : http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].' rubrique : '.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' / origine : '.$_SERVER['HTTP_REFERER'].' / IP : '.$_SERVER['REMOTE_ADDR']);
				
				$GLOBALS['css']= array('default.css');
				$GLOBALS['js'] = array('script.js');
				
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
					$this->_setRubrique($rubrique);
				}
				else{
					$this->redirect404();
					$this->setErrorLog('errors', 'The rubric '.$_GET['rubrique'].' were not found');
				}
			}
			else{
				if(is_file(RUBRIQUE_PATH.'index.php')){ 
					$this->_setRubrique('index');
				}
				else{
					$this->redirect404();
					$this->setErrorLog('errors', 'The rubric '.$_GET['rubrique'].' were not found');
				}
			}
		}
		
		/* ---------- CONNEXION A LA BASE DE DONNEES --------- */
		
		protected function _connectDatabase($db){
			foreach ($db as $d){
				switch ($d['extension']){
					case 'pdo':
						try{
							$sql_connect[''.$d['database'].''] = new PDO('mysql:host='.$d['hostname'].';dbname='.$d['database'], $d['username'], $d['password']);
						}
						catch (PDOException $e){
							$this->setErrorLog('errors_sql', 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
						}	
					break;
					
					case 'mysqli':
						$sql_connect[''.$d['database'].''] = new mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
					break;
					
					case 'mysql':
						$sql_connect[''.$d['database'].''] = mysql_connect($d['hostname'], $d['username'], $d['password']);
						$sql_connect[''.$d['database'].''] = mysql_select_db($d['database']);
					break;
					
					default :
						$this->setErrorLog('errors_sql', 'L\'extension de cette connexion n\'est pas gérée');
					break;
				}
			}
			return $sql_connect;
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
			if(file_exists(SQL_PATH.$rubrique.SQL_EXT.'.php')){ require_once(SQL_PATH.$rubrique.SQL_EXT.'.php');}
			if(file_exists(FORMS_PATH.$rubrique.FORMS_EXT.'.php')){ require_once(FORMS_PATH.$rubrique.FORMS_EXT.'.php'); }
			if(file_exists(INCLUDE_PATH.$rubrique.FUNCTION_EXT.'.php')){ require_once(INCLUDE_PATH.$rubrique.FUNCTION_EXT.'.php');}
			if(file_exists(RUBRIQUE_PATH.$rubrique.'.php')){ require_once(RUBRIQUE_PATH.$rubrique.'.php'); } else { $this->windowInfo('Erreur', RUBRIQUE_NOT_FOUND, 0, './'); }
		}
		
		/* ---------- FONCTIONS ------------- */
		
		public function setMaintenance(){
			$tpl = new templateGC(GCSYSTEM_PATH.'GCmaintenance', 'GCmaintenance', 0, $this->_lang);				
			$tpl->show();
		}
	}
?>