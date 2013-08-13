<?php
	/**
	 * @file : applicationGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les contrôleurs. abstraite
	 * @version : 2.0 bêta
	*/
	
	abstract class applicationGc{
		use errorGc, langInstance, generalGc, urlRegex, domGc, errorPerso, helperLoader; //trait
		
		protected $_devTool            = true                                          ;
		protected $_var                = array()                                       ; //contient les variables que l'on passe depuis l'extérieur : obsolète
		protected $bdd                                                                 ; //contient la connexion sql
		protected $_firewall                                                           ;
		protected $_antispam                                                           ;
		protected $_nameModel          = ""                                            ; //pour les crons lors de l'init du model, on ne peut pas utiliser $_GET['rubrique']
		
		/* ---------- CONSTRUCTEURS --------- */
		
		final public function __construct($lang=""){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();	
			if(CONNECTBDD == true) {$this->bdd=$this->_connectDatabase($GLOBALS['db']); }
			$this->_addError('Contrôleur '.$_GET['rubrique'].' initialisé', __FILE__, __LINE__, INFORMATION);
			$this->_firewall = false;
		}
		
		protected function init(){	
		}

		protected function end(){	
		}
		
		final public function setFirewall(){
			$this->_firewall = new firewallGc($this->_lang);
			
			if($this->_firewall->check()){
				$this->_addError('Le parefeu n\'a identifié aucune erreur dans l\'accès à la rubrique '.$_GET['rubrique'].'/'.$_GET['action'], __FILE__, __LINE__, INFORMATION);
				return true;
			}
			else{
				return false;
			}
		}

		final public function setAntispam(){
			$this->_antispam = new antispamGc($this->_lang);
			
			if($this->_antispam->check()){
				$this->_addError('L\'antispam a vérifié que l\'utilisateur n\'avait pas atteint son quota de requêtes', __FILE__, __LINE__, INFORMATION);
				return true;
			}
			else{
				return false;
			}
		}
		
		final protected function loadModel(){
			if($this->_nameModel != ""){ //si il ne s'agit pas d'une url mais d'un CRON
				$class = 'manager'.ucfirst($this->_nameModel);
				if(class_exists($class)){	
					$this->_addError('Model '.$this->_nameModel.' initialisé', __FILE__, __LINE__, INFORMATION);
					$instance = new $class($this->_lang, $this->bdd);
					$instance->init();
					return $instance;
				}
				else{
					$this->_addError('Impossible de charger le model "'.$this->_nameModel.'"', __FILE__, __LINE__, ERROR);
				}
			}
			else{
				$class = 'manager'.ucfirst($_GET['rubrique']);
				if(class_exists($class)){	
					$this->_addError('Model '.$_GET['rubrique'].' initialisé', __FILE__, __LINE__, INFORMATION);
					$instance = new $class($this->_lang, $this->bdd);
					$instance->init();
					return $instance;
				}
				else{
					$this->_addError('Impossible de charger le model "'.$_GET['rubrique'].'"', __FILE__, __LINE__, ERROR);
				}
			}
		}
		
		final protected function _connectDatabase($db){
			foreach ($db as $d){
				switch ($d['extension']){
					case 'pdo':
						try{
							$sql_connect[''.$d['database'].''] = new PDO($d['sgbd'].':host='.$d['hostname'].';dbname='.$d['database'], $d['username'], $d['password']);
						}
						catch (PDOException $e){
							$this->setErrorLog(LOG_SQL, 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
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
						$this->setErrorLog(LOG_SQL, 'L\'extension de cette connexion n\'est pas gérée');
					break;
				}
			}

			if(strtolower(CHARSET) == 'utf-8'){
				foreach ($sql_connect as $value) {
					$value->exec("SET CHARACTER SET utf8");
				}
			}

			return $sql_connect;
		}
			
		final protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		final protected function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		final protected function getLang(){
			return $this->_lang;
		}
		
		final protected function setDevTool($set){
			$this->_devTool = $set;
			$GLOBALS['appDevGc']->setShow($set);
		}
		
		final protected function getDevTool($set){
			return $this->_devTool;
		}
		
		final protected function setLang($lang){
			$this->_lang=$lang;
			$this->_langInstance->setLang($this->_lang);
		}
		
		final protected function newToken(){
			return uniqid(rand(), true);
		}
		
		final protected function showDefault(){
			$t= new templateGC(GCSYSTEM_PATH.'GCnewrubrique', 'GCrubrique', '0');
			$t->assign(array('rubrique' => $_GET['rubrique']));
			$t->show();
		}

		final public function setNameModel($nameModel){
			$this->_nameModel = $nameModel;
		}
		
		final protected function affTemplate($nom_template){
			if(is_file(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT) && file_exists(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT) && is_readable(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT)) { 
				include(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT);
			} 
			else { 
				$this->_addError(LOG_SYSTEM, 'Le template '.$nom_template.' n\'a pas été trouvé');
			}
		}
		
		public function __desctuct(){
			foreach ($this->bdd as $key => $value) {
				$value->closeCursor();
			}
		}
	}