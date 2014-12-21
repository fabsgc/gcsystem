<?php
	/*\
	 | ------------------------------------------------------
	 | @file : controller.class.php
	 | @author : fab@c++
	 | @description : class mère dont hérite chaque les contrôleur. Abstraite
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		abstract class controller{
			use error, langInstance, general, urlRegex, errorPerso, helperLoader, ormFunctions;
			
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
			 * @param $lang string
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
			
			/**
			 * Méthode héritable, appelée avant chaque action
			 * @access	protected
			 * @since 2.0
			*/

			protected function init(){	
			}

			/**
			 * Méthode héritable, appelée après chaque action
			 * @access	protected
			 * @since 2.0
			*/

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

			/**
			 * Initialise l'antispam et lance la vérification
			 * @access	public
			 * @return bool
			 * @since 2.0
			*/

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

			/**
			 * Chargement et initialisation du model
			 * @access	public
			 * @return void
			 * @since 2.0
			*/
			
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

			/**
			 * Connexion à la base de données
			 * @access	public
			 * @param $db array : identifiants de connexion
			 * @return pdo
			 * @since 2.0
			*/

			final protected function _connectDatabase($db){
				$sql_connect = NULL;

				switch ($db['extension']){
					case 'pdo':
						try{
							$options = array(
								pdo::ATTR_STATEMENT_CLASS => array('\system\pdoStatement', array()),
							);

							$sql_connect = new pdo($db['sgbd'].':host='.$db['hostname'].';dbname='.$db['database'], $db['username'], $db['password'], $options);
						}
						catch (\PDOException $e){
							$this->setErrorLog(LOG_SQL, 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
						}
					break;

					case 'mysqli':
						$sql_connect = new \mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
					break;

					case 'mysql':
						$sql_connect = mysql_connect($db['hostname'], $db['username'], $db['password']);
						$sql_connect = mysql_select_db($db['database']);
					break;

					default :
						$this->setErrorLog(LOG_SQL, 'L\'extension de cette connexion n\'est pas gérée');
					break;
				}

				if(strtolower(CHARSET) == 'utf-8'){
					$sql_connect->exec("SET CHARACTER SET utf8");
				}

				return $sql_connect;
			}
				
			final protected function _createLangInstance(){
				$this->langInstance = new lang($this->lang);
			}

			/**
			 * Utilisation de la classe lang
			 * @access public
			 * @param $sentence string : id de la phrase
			 * @param $var array : variables à utiliser
			 * @param $template : utilisation de la syntaxe des templates ou non (désactivé par défaut)
			 * @return string
			 * @since 2.0
			*/
			
			final public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->langInstance->loadSentence($sentence, $var, $template);
			}

			/**
			 * retourne la langue courante
			 * @access	public
			 * @return string
			 * @since 2.0
			*/
			
			final public function getLang(){
				return $this->lang;
			}

			/**
			 * Activation ou désactivation de la barre de dev
			 * @access	public
			 * @param $set boolean : id de la phrase
			 * @since 2.0
			*/
			
			final protected function setDevTool($set){
				$this->_devTool = $set;
				$GLOBALS['appDev']->setShow($set);
				$GLOBALS['appDev']->setProfiler($set);
			}

			/**
			 * Retourne l'état de la barre de développement 
			 * @access	public
			 * @return boolean
			 * @since 2.0
			*/
			
			final protected function getDevTool(){
				return $this->_devTool;
			}
			
			final public function setLang($lang){
				$this->lang=$lang;
				$this->langInstance->setLang($this->lang);
			}
			
			final public function showDefault(){
				$t= new template(GCSYSTEM_PATH.'newcontroller', 'GCcontroller', '0');
				$t->assign(array('controller' => $_GET['controller']));
				$t->show();
			}

			final public function setNameModel($nameModel){
				$this->_nameModel = $nameModel;
			}

			/**
			 * retourne les données d'une requête SQL sous forme d'entités
			 * @access	public
			 * @param $data array : données d'entrée
			 * @param $entity string : nom de l'entité à utiliser
			 * @return array
			 * @since 2.4
			 */
			final public function toEntity($data = array(), $entity = ''){
				return $this->ormToEntity($this->bdd, $data, $entity);
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