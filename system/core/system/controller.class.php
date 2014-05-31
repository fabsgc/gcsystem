<?php
	/**
	 * @file : controller.class.php
	 * @author : fab@c++
	 * @description : class mère dont hérite chaque les contrôleur. Abstraite
	 * @version : 2.3 Bêta
	*/
	
	namespace system{
		abstract class controller{
			use error, langInstance, general, urlRegex, errorPerso, helperLoader; //trait
			
			protected $_devTool            = true       ;
			protected $_firewall                        ;
			protected $_antispam                        ;
			protected $_nameModel          = ""         ; //pour les crons lors de l'init du model, on ne peut pas utiliser $_GET['controller']
			public $model                               ; //instance du model
			public $lang                   = DEFAULTLANG; //lang par défaut
			public $bdd                                 ; //instance PDO
			public $event                               ; //instance du gestionnaire d'évènement
			
			/**
			 * Constructeur de la classe. initialisation du contrôleur de l'application
			 * @access	public
			 * @return	void
			 * @since 2.0
			*/
			
			final public function __construct($lang=""){
				if($lang==""){ 
					$this->lang = $this->getLangClient(); 
				} 
				else { 
					$this->lang = $lang; 
				}

				$this->_createLangInstance();

				if(CONNECTBDD == true) { 
					$this->bdd=$this->_connectDatabase($GLOBALS['db']); 
				}

				$this->_firewall = false;

				$this->event = new eventManager();
			}
			
			protected function init(){	
			}

			protected function end(){	
			}
			
			final public function setFirewall(){
				$this->_firewall = new firewall($this->lang);
				
				if($this->_firewall->check()){
					$this->_addError('Le parefeu n\'a identifié aucune erreur dans l\'accès au contrôleur '.$_GET['controller'].'/'.$_GET['action'], __FILE__, __LINE__, INFORMATION);
					return true;
				}
				else{
					return false;
				}
			}

			final public function setAntispam(){
				$this->_antispam = new antispam($this->lang);
				
				if($this->_antispam->check()){
					$this->_addError('L\'antispam a vérifié que l\'utilisateur n\'avait pas atteint son quota de requêtes', __FILE__, __LINE__, INFORMATION);
					return true;
				}
				else{
					return false;
				}
			}
			
			final public function loadModel(){
				if($this->_nameModel != ""){ //si il ne s'agit pas d'une url mais d'un CRON
					$class = 'manager'.ucfirst($this->_nameModel);
					if(class_exists($class)){	
						$this->_addError('Model '.$this->_nameModel.' initialisé', __FILE__, __LINE__, INFORMATION);
						$this->model = new $class($this->lang, $this->bdd);
						$this->model->init();
					}
					else{
						$this->_addError('Impossible de charger le model "'.$this->_nameModel.'"', __FILE__, __LINE__, FATAL);
					}
				}
				else{
					$class = 'manager'.ucfirst($_GET['controller']);
					if(class_exists($class)){	
						$this->_addError('Model '.$_GET['controller'].' initialisé', __FILE__, __LINE__, INFORMATION);
						$this->model = new $class($this->lang, $this->bdd);
						$this->model->init();
					}
					else{
						$this->_addError('Impossible de charger le model "'.$_GET['controller'].'"', __FILE__, __LINE__, FATAL);
					}
				}
			}
			
			final protected function _connectDatabase($db){
				foreach ($db as $d){
					switch ($d['extension']){
						case 'pdo':
							try{
								$sql_connect[''.$d['database'].''] = new \PDO($d['sgbd'].':host='.$d['hostname'].';dbname='.$d['database'], $d['username'], $d['password']);
							}
							catch (PDOException $e){
								$this->setErrorLog(LOG_SQL, 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
							}	
						break;
						
						case 'mysqli':
							$sql_connect[''.$d['database'].''] = new \mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
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
				$this->langInstance = new lang($this->lang);
			}
			
			final public function useLang($sentence, $var = array()){
				return $this->langInstance->loadSentence($sentence, $var);
			}
			
			final public function getLang(){
				return $this->lang;
			}
			
			final protected function setDevTool($set){
				$this->_devTool = $set;
				$GLOBALS['appDev']->setShow($set);
			}
			
			final protected function getDevTool(){
				return $this->_devTool;
			}
			
			final public function setLang($lang){
				$this->lang=$lang;
				$this->langInstance->setLang($this->lang);
			}
			
			final public function showDefault(){
				$t= new template(GCSYSTEM_PATH.'GCnewcontroller', 'GCcontroller', '0');
				$t->assign(array('controller' => $_GET['controller']));
				$t->show();
			}

			final public function setNameModel($nameModel){
				$this->_nameModel = $nameModel;
			}
			
			/**
			 * destructeur
			 * @access	public
			 * @return	void
			 * @since 2.0
			*/

			public function __desctuct(){
				foreach ($this->bdd as $value) {
					$value->closeCursor();
				}
			}
		}
	}