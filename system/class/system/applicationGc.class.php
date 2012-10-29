<?php
	/**
	 * @file : applicationGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les contrôleurs. abstraite
	 * @version : 2.0 bêta
	*/
	
	abstract class applicationGc{
		use errorGc, langInstance, generalGc, urlRegex, domGc, htmlHeaderGc       ; //trait
		
		protected $_devTool            = true                                     ;
		protected $_var                = array()                                  ; //contient les variables que l'on passe depuis l'extérieur : obsolète
		protected $bdd                                                            ; //contient la connexion sql
		protected $_firewall                                                      ;
		protected $_antispam                                                      ;
		
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
			$class = 'manager'.ucfirst($_GET['rubrique']);
			if(class_exists($class)){	
				$this->_addError('Model '.$_GET['rubrique'].' initialisé', __FILE__, __LINE__, INFORMATION);
				$instance = new $class($this->_lang, $this->bdd);
				$instance->init();
				return $instance;
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
		
		final protected function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
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
		
		protected function setVar($nom, $val){
			$this->_var[$nom] = $val;
		}
		
		protected function setVarArray($var){
			foreach($var as $cle => $val){
				$this->_var[$cle] = $val;
			}
		}
		
		protected function getVar($nom){
			if(isset($this->_var[$nom]))
				return $this->_var[$nom];
			else
				return false;
		}
		
		protected function unSetVar($nom){
			if(isset($this->_var[$nom]))
				unset($this->_var[$nom]);
			else
				return false;
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
		
		final protected function affTemplate($nom_template){
			if(is_file(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT) && file_exists(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT) && is_readable(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT)) { 
				include(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT);
			} 
			else { 
				$this->setErrorLog('errors', 'Le template '.$nom_template.' n\'a pas été trouvé');
			}
		}

		final protected function getFirewallArray(){ 
			return $this->_firewall->getFirewallArray(); 
		}

		final protected function getFirewallRoles(){ 
			return $this->_firewall->getFirewallRoles(); 
		}

		final protected function getFirewallRole($nom = "empty"){ 
			return $this->_firewall->getFirewallRole($nom); 
		}

		final protected function getFirewall(){ 
			return $this->_firewall->getFirewall(); 
		}

		final protected function getFirewallConfig(){ 
			return $this->_firewall->getFirewallConfig(); 
		}

		final protected function getFirewallConfigLogin(){ 
			return $this->_firewall->getFirewallConfigLogin(); 
		}

		final protected function getFirewallConfigLoginSource(){ 
			return $this->_firewall->getFirewallConfigLoginSource(); 
		}

		final protected function getFirewallConfigLoginSourceId(){ 
			return $this->_firewall->getFirewallConfigLoginSourceId(); 
		}

		final protected function getFirewallConfigLoginSourceVars(){ 
			return $this->_firewall->getFirewallConfigLoginSourceVars(); 
		}

		final protected function getFirewallConfigLoginTarget(){ 
			return $this->_firewall->getFirewallConfigLoginTarget(); 
		}

		final protected function getFirewallConfigLoginTargetId(){ 
			return $this->_firewall->getFirewallConfigLoginTargetId(); 
		}

		final protected function getFirewallConfigLoginTargetVars(){ 
			return $this->_firewall->getFirewallConfigLoginTargetVars(); 
		}

		final protected function getFirewallConfigForbidden(){ 
			return $this->_firewall->getFirewallConfigForbidden(); 
		}

		final protected function getFirewallConfigForbiddenTemplate(){ 
			return $this->_firewall->getFirewallConfigForbiddenTemplate(); 
		}

		final protected function getFirewallConfigForbiddenTemplateSrc(){ 
			return $this->_firewall->getFirewallConfigForbiddenTemplateSrc(); 
		}

		final protected function getFirewallConfigForbiddenTemplateVars(){ 
			return $this->_firewall->getFirewallConfigForbiddenTemplateVars(); 
		}

		final protected function getFirewallConfigForbiddenTemplateVar($nom = "empty"){
			return $this->_firewall->getFirewallConfigForbiddenTemplateVar($nom);
		}

		final protected function getFirewallConfigForbiddenTemplateVarType($nom = "empty"){
			return $this->_firewall->getFirewallConfigForbiddenTemplateVarType($nom);
		}

		final protected function getFirewallConfigForbiddenTemplateVarName($nom = "empty"){
			return $this->_firewall->getFirewallConfigForbiddenTemplateName($nom);
		}

		final protected function getFirewallConfigForbiddenTemplateVarValue($nom = "empty"){
			return $this->_firewall->getFirewallConfigForbiddenTemplateVarValue($nom);
		}

		final protected function getFirewallConfigCsrf(){ 
			return $this->_firewall->getFirewallConfigCsrf(); 
		}

		final protected function getFirewallConfigCsrfEnabled(){ 
			return $this->_firewall->getFirewallConfigCsrfEnabled(); 
		}

		final protected function getFirewallConfigCsrfTemplate(){ 
			return $this->_firewall->getFirewallConfigCsrfTemplate(); 
		}

		final protected function getFirewallConfigCsrfTemplateSrc(){ 
			return $this->_firewall->getFirewallConfigCsrfTemplateSrc(); 
		}

		final protected function getFirewallConfigCsrfTemplateVars(){ 
			return $this->_firewall->getFirewallConfigCsrfTemplateVars(); 
		}

		final protected function getFirewallConfigCsrfTemplateVar($nom = "empty"){
			return $this->_firewall->getFirewallConfigCsrfTemplateVar($nom);
		}

		final protected function getFirewallConfigCsrfTemplateVarType($nom = "empty"){
			return $this->_firewall->getFirewallConfigCsrfTemplateVarType($nom);
		}

		final protected function getFirewallConfigCsrfTemplateVarName($nom = "empty"){
			return $this->_firewall->getFirewallConfigCsrfTemplateName($nom);
		}

		final protected function getFirewallConfigCsrfTemplateVarValue($nom = "empty"){
			return $this->_firewall->getFirewallConfigCsrfTemplateVarValue($nom);
		}

		final protected function getFirewallConnect(){ 
			return $this->_firewall->getFirewallConnect(); 
		}

		final protected function getFirewallAccess(){ 
			return $this->_firewall->getFirewallAccess(); 
		}

		final protected function getAntispamArray(){ 
			return $this->_antispam->getAntispamArray();
		}

		final protected function getAntispamConfig(){ 
			return $this->_antispam->getAntispamConfig();
		}

		final protected function getAntispamConfigQueryIp(){ 
			return $this->_antispam->getAntispamConfigQueryIp();
		}

		final protected function getAntispamConfigQueryIpNumber(){ 
			return $this->_antispam->getAntispamConfigQueryIpNumber();
		}

		final protected function getAntispamConfigQueryIpDuration(){ 
			return $this->_antispam->getAntispamConfigQueryIpDuration();
		}

		final protected function getAntispamConfigError(){ 
			return $this->_antispam->getAntispamConfigError();
		}

		final protected function getAntispamConfigErrorTemplate(){ 
			return $this->_antispam->getAntispamConfigErrorTemplate();
		}

		final protected function getAntispamConfigErrorTemplateSrc(){
			return $this->_antispam->getAntispamConfigErrorTemplateSrc();
		}

		final protected function getAntispamConfigErrorTemplateVars(){ 
			return $this->_antispam->getAntispamConfigErrorTemplateVars();
		}

		final protected function getAntispamConfigErrorTemplateVar($nom = "empty"){ 
			return $this->_antispam->getAntispamConfigErrorTemplateVar($nom);
		}

		final protected function getAntispamConfigErrorTemplateVarType($nom = "empty"){ 
			return $this->_antispam->getAntispamConfigErrorTemplateVarType($nom);
		}

		final protected function getAntispamConfigErrorTemplateVarName($nom = "empty"){ 
			return $this->_antispam->getAntispamConfigErrorTemplateVarName($nom);
		}

		final protected function getAntispamConfigErrorTemplateVarValue($nom = "empty"){ 
			return $this->_antispam->getAntispamConfigErrorTemplateVarValue($nom);
		}

		final protected function getAntispamIps(){ 
			return $this->_antispam->getAntispamIps();
		}

		final protected function getAntispamIp($nom = "empty"){ 
			return $this->_antispam->getAntispamIp($nom);
		}

		final protected function getAntispamIpNumber($nom = "empty"){ 
			return $this->_antispam->getAntispamIpNumber($nom);
		}

		final protected function getAntispamIpSince($nom = "empty"){ 
			return $this->_antispam->getAntispamIpSince($nom);
		}

		final protected function getAntispamIpIp($nom = "empty"){ 
			return $this->_antispam->getAntispamIpIp($nom);
		}
		
		public function __desctuct(){
		}
	}