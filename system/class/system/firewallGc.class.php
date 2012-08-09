<?php
	/**
	 * @file : firewallGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le firewall, l'accès aux pages
	 * @version : 2.0 bêta
	*/
	
	class FirewallGc {
		use errorGc, langInstance, generalGc, urlRegex, domGc                ; //trait
		
		protected $_security             = array();
		protected $_sessions             = array();
		protected $_id                            ;
		
		public function __construct($lang = 'fr'){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
			if(REWRITE == true){
				$this->_domXml = new DomDocument('1.0', CHARSET);
				
				if($this->_domXml->load(FIREWALL)){
					$this->_addError('le fichier '.FIREWALL.' a bien été chargé', __FILE__, __LINE__, INFORMATION);
					$this->_setRoleHierarchy();
					$this->_setFirewallConfigLoginSource();
					$this->_setFirewallConfigLoginTarget();
					$this->_setFirewallConfigForbidden();
					$this->_setFirewallConfigcsrf();
					$this->_setFirewallConfigConnect();
					$this->_setFirewallAccess();
					$this->_setSession();
				}
				else{
					$this->_addError('le fichier '.FIREWALL.' n\'a pas pu être chargé', __FILE__, __LINE__, ERROR);
					return true;
				}
			}
			else{
				$this->_addError('le mode routeur est désactivé. Le parefeu ne peut pas fonctionner', __FILE__, __LINE__, WARNING);
				return true;
			}
		}
		
		public function check(){
			return $this->_check();
		}
		
		protected function _setRoleHierarchy(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('roles_hierarchy')->item(0);
			$this->_session[$this->_node2Xml->getAttribute('name')] = '';
			$this->_security['roles_hierarchy']['name'] = $this->_node2Xml->getAttribute('name');
			$this->_markupXml = $this->_node2Xml->getElementsByTagName('role_hierarchy');
			
			foreach($this->_markupXml as $cle => $role){
				$this->_security['roles_hierarchy']['role_hierarchy'][$role->getAttribute('name')] = $cle;
			}
		}
		
		protected function _setFirewallConfigLoginSource(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_node3Xml = $this->_node2Xml->getElementsByTagName('config')->item(0);
			$this->_markupXml = $this->_node3Xml->getElementsByTagName('login')->item(0);
			$this->_security['firewall']['config']['login']['source']['id'] = $this->_markupXml->getElementsByTagName('source')->item(0)->getAttribute('id');
			$this->_security['firewall']['config']['login']['source']['vars'] = explode(',', $this->_markupXml->getElementsByTagName('source')->item(0)->getAttribute('vars'));
		}
		
		protected function _setFirewallConfigLoginTarget(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_node3Xml = $this->_node2Xml->getElementsByTagName('config')->item(0);
			$this->_markupXml = $this->_node3Xml->getElementsByTagName('login')->item(0);
			$this->_security['firewall']['config']['login']['target']['id'] = $this->_markupXml->getElementsByTagName('target')->item(0)->getAttribute('id');
			$this->_security['firewall']['config']['login']['target']['vars'] = explode(',', $this->_markupXml->getElementsByTagName('target')->item(0)->getAttribute('vars'));
		}
		
		protected function _setFirewallConfigForbidden(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_markupXml = $this->_node2Xml->getElementsByTagName('config')->item(0);
			$this->_security['firewall']['config']['forbidden']['template'] = $this->_markupXml->getElementsByTagName('forbidden')->item(0)->getAttribute('template');
		}
		
		protected function _setFirewallConfigcsrf(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_markup = $this->_node2Xml->getElementsByTagName('config')->item(0);
			$this->_session[$this->_markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('name')] = '';
			$this->_security['firewall']['config']['csrf']['enabled'] = $this->_markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('enabled');
			$this->_security['firewall']['config']['csrf']['template'] = $this->_markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('template');
			$this->_security['firewall']['config']['csrf']['name'] = $this->_markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('name');
		}
		
		protected function _setFirewallConfigConnect(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_markup = $this->_node2Xml->getElementsByTagName('config')->item(0);
			$this->_session[$this->_markupXml->getElementsByTagName('connect')->item(0)->getAttribute('name')] = '';
			$this->_security['firewall']['config']['connect']['name'] = $this->_markupXml->getElementsByTagName('connect')->item(0)->getAttribute('name');
			$this->_security['firewall']['config']['connect']['no'] = $this->_markupXml->getElementsByTagName('connect')->item(0)->getAttribute('no');
			$this->_security['firewall']['config']['connect']['yes'] = $this->_markupXml->getElementsByTagName('connect')->item(0)->getAttribute('yes');
		}
		
		protected function _setFirewallAccess(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
			$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
			$this->_markupXml = $this->_node3Xml->getElementsByTagName('url');
			
			foreach($this->_markupXml as $cle => $val){
				if($val->getAttribute('id') == $_GET['pageid']){
					$this->_security['firewall']['access']['url']['id'] = $val->getAttribute('id');
					$this->_security['firewall']['access']['url']['connected'] = $val->getAttribute('connected');
					$access = explode(',', $val->getAttribute('access'));
					$this->_id = $val->getAttribute('id');
					
					if(count($access) > 0){
						foreach($access as $cle => $val){
							if($val == '*'){
								$this->_security['firewall']['access']['url']['access'][$val] = $val;
							}
							else{
								$this->_security['firewall']['access']['url']['access'][$val] = $this->_security['roles_hierarchy']['role_hierarchy'][$val];
							}
						}
					}
					else{
						$this->_security['firewall']['access']['url']['access']['*'] = '*';
					}
				}
			}
		}
		
		protected function _setSession(){
			foreach($this->_session as $cle => $val){
				if(isset($_SESSION[$cle])){
					$this->_session[$cle] = $val;
				}
			}
		}
		
		protected function _check(){
			if(isset($this->_security['firewall']['access'])){ //si la page a été indiquée dans le firewall.xml
				if($this->_security['firewall']['config']['csrf']['enabled'] == 'true'){ //la vérification CSRF a été activée ou non ?
					if($this->_checkCsrf() == true){ //la vérification CSRF a marché ou non ?
						switch($this->_security['firewall']['access']['url']['connected']){ //verifier avant si il faut être connecte
							case '*' :
								//on considère que tout le monde peut accéder à la page
								return true;
							break;
							
							case 'true':
								//il faut être connecté pour pouvoir accéder à la page
								if($this->_checkConnected() == true){
									if($this->_checkRole() == true){
										return true;
									}
									else{
										$t = new templateGc($this->_security['firewall']['config']['forbidden']['template'], 'GCfirewallForbiddenGrade', 0);
										$t->assign(array(
											'title'=>$this->useLang('firewallforbiddentitle'),
											'content'=>$this->useLang('firewallforbiddencontent'),
											'redirect'=>FOLDER,
											'time'=>0,
											'css'=>FOLDER.'/asset/css/default.css'
										));
										$t -> show();
										$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['rubrique'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car son grade n\'est pas autorisé', __FILE__, __LINE__, ERROR);
										return false;
									}
								}
								else{
									$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['rubrique'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car il doit être connecté', __FILE__, __LINE__, ERROR);
									//header('Location: '.$this->getUrl($this->_security['firewall']['config']['login']['source']['id'], $this->_security['firewall']['config']['login']['source']['vars']));
									return false;
								}
							break;
							
							case 'false':
								//il faut être déconnecté pour pouvoir accéder à la page
								if($this->_checkConnected() == false){
									return true;
								}
								else{
									$t = new templateGc($this->_security['firewall']['config']['forbidden']['template'], 'GCfirewallForbiddenGrade', 0);
										$t->assign(array(
											'title'=>$this->useLang('firewallforbiddentitle'),
											'content'=>$this->useLang('firewallforbiddencontent'),
											'redirect'=>FOLDER,
											'time'=>0,
											'css'=>FOLDER.'/asset/css/default.css'
										));
									$t -> show();
									$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['rubrique'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car il est connecté', __FILE__, __LINE__, ERROR);
									return false;
								}
							break;
							
							default :
								//on considère que tout le monde peut accéder à la page
								return true;
							break;
						}
					}
					else{
						$t = new templateGc($this->_security['firewall']['config']['csrf']['template'], 'GCfirewallCsrf', 0, $this->_lang);
						$t->assign(array(
							'title'=>$this->useLang('firewallcsrftitle'),
							'content'=>$this->useLang('firewallcsrfcontent'),
							'redirect'=>FOLDER,
							'time'=>0,
							'css'=>FOLDER.'/asset/css/default.css'
						));
						$t -> show();
						$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['rubrique'].'/'.$_GET['action'].' comme interdit pour cet utilisateur : faille CSRF', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					//la vérification CSRF a été désactivée, on check les urlss
					return true;
				}
			}
			else{
				return true; //la page n'a pas à être vérifiée
			}
		}
		
		protected function _checkCsrf(){
			if(isset($_GET[$this->_security['firewall']['config']['csrf']['name']]) && $_SESSION[$this->_security['firewall']['config']['csrf']['name']]!=''){
				if($_GET[$this->_security['firewall']['config']['csrf']['name']] == $_SESSION[$this->_security['firewall']['config']['csrf']['name']]){
					return true; //Get et Sessions correspondent
				}
				else{
					return false;
				}
			}
			elseif(isset($_GET[$this->_security['firewall']['config']['csrf']['name']])){
				return false; //on a une variable get dans l'url mais pas de sessions token, ça n'est pas normal
			}
			else{
				return true; //pas de get ni de Session, la vérification Csrf ne peut pas continuer
			}
		}
		
		protected function _checkConnected(){
			//renvoie true si le visiteur est connecté false si il n'est pas connecté
			if(isset($_SESSION[$this->_security['firewall']['config']['connect']['name']]) && $_SESSION[$this->_security['firewall']['config']['connect']['name']] == $this->_security['firewall']['config']['connect']['yes']){
				return true;
			}
			else{
				return false;
			}
		}
		
		protected function _checkRole(){
			//renvoie true si le statut du membre est supérieur ou égal au statut minimal, false dans l'autre cas
			if((isset($_SESSION[$this->_security['roles_hierarchy']['name']]) && in_array($_SESSION[$this->_security['roles_hierarchy']['name']], $this->_security['firewall']['access']['url']['access'])) 
				|| $this->_security['firewall']['access']['url']['access']['*'] == '*'){
				return true;
			}
			else{
				return false;
			}
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		protected function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		public function __destruct(){
		}
	}