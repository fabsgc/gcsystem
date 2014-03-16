<?php
	/**
	 * @file : firewall.class.php
	 * @author : fab@c++
	 * @description : class gérant le firewall, l'accès aux pages
	 * @version : 2.3 Bêta
	*/
	
	namespace system{
		class firewall {
			use error, langInstance, general, urlRegex; //trait
			
			protected $_security             = array();
			protected $_sessions             = array();
			protected $_id                            ;
			protected $_domXml                        ;
			
			public function __construct($lang = 'fr'){
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();
				if(REWRITE == true){
					$this->_domXml = new \DomDocument('1.0', CHARSET);
					
					if($this->_domXml->load(FIREWALL)){
						$this->_addError('le fichier '.FIREWALL.' a bien été chargé', __FILE__, __LINE__, INFORMATION);
						$this->_setRoleHierarchy();
						$this->_setFirewallConfigLoginSource();
						$this->_setFirewallConfigDefaultSource();
						$this->_setFirewallConfigForbidden();
						$this->_setFirewallConfigcsrf();
						$this->_setFirewallConfigConnect();
						$this->_setFirewallAccess();
						$this->_setSession();
					}
					else{
						$this->_addError('le fichier '.FIREWALL.' n\'a pas pu être chargé', __FILE__, __LINE__, FATAL);
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
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('roles_hierarchy')->item(0);
				$this->_session[$node2Xml->getAttribute('name')] = '';
				$this->_security['roles_hierarchy']['name'] = $node2Xml->getAttribute('name');
				$markupXml = $node2Xml->getElementsByTagName('role_hierarchy');
				
				foreach($markupXml as $cle => $role){
					$this->_security['roles_hierarchy']['role_hierarchy'][$role->getAttribute('name')] = $cle;
				}
			}
			
			protected function _setFirewallConfigLoginSource(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$node3Xml = $node2Xml->getElementsByTagName('config')->item(0);
				$markupXml = $node3Xml->getElementsByTagName('login')->item(0);
				$this->_security['firewall']['config']['login']['source']['id'] = $markupXml->getElementsByTagName('source')->item(0)->getAttribute('id');
				$this->_security['firewall']['config']['login']['source']['vars'] = explode(',', $markupXml->getElementsByTagName('source')->item(0)->getAttribute('vars'));
			
				foreach ($this->_security['firewall']['config']['login']['source']['vars'] as $key => $value) {
					if(preg_match('#^\$#isU', $value)){

						ob_start ();
							eval('echo '.$value.';');
							$this->_security['firewall']['config']['login']['source']['vars'][$key] = ob_get_contents();
						ob_get_clean();
					}
				}
			}

			protected function _setFirewallConfigDefaultSource(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$node3Xml = $node2Xml->getElementsByTagName('config')->item(0);
				$markupXml = $node3Xml->getElementsByTagName('default')->item(0);
				$this->_security['firewall']['config']['default']['source']['id'] = $markupXml->getElementsByTagName('source')->item(0)->getAttribute('id');
				$this->_security['firewall']['config']['default']['source']['vars'] = explode(',', $markupXml->getElementsByTagName('source')->item(0)->getAttribute('vars'));
			
				foreach ($this->_security['firewall']['config']['default']['source']['vars'] as $key => $value) {
					if(preg_match('#^\$#isU', $value)){

						ob_start ();
							eval('echo '.$value.';');
							$this->_security['firewall']['config']['default']['source']['vars'][$key] = ob_get_contents();
						ob_get_clean();
					}
				}
			}
			
			protected function _setFirewallConfigForbidden(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('config')->item(0);
				$this->_security['firewall']['config']['forbidden']['template']['src'] = $markupXml->getElementsByTagName('forbidden')->item(0)->getAttribute('template');
			
				$markup2Xml = $markupXml->getElementsByTagName('forbidden')->item(0);
				$markup3Xml = $markup2Xml->getElementsByTagName('variable');

				foreach ($markup3Xml as $cle => $val) {
					$this->_security['firewall']['config']['forbidden']['template']['variable'][$val->getAttribute('name')]['type'] = $val->getAttribute('type');
					$this->_security['firewall']['config']['forbidden']['template']['variable'][$val->getAttribute('name')]['name'] = $val->getAttribute('name');
					$this->_security['firewall']['config']['forbidden']['template']['variable'][$val->getAttribute('name')]['value'] = $val->getAttribute('value');
				}
			}
			
			protected function _setFirewallConfigcsrf(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('config')->item(0);
				$this->_session[$markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('name')] = '';
				$this->_security['firewall']['config']['csrf']['enabled'] = $markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('enabled');
				$this->_security['firewall']['config']['csrf']['template']['src'] = $markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('template');
				$this->_security['firewall']['config']['csrf']['name'] = $markupXml->getElementsByTagName('csrf')->item(0)->getAttribute('name');
				
				$markup2Xml = $markupXml->getElementsByTagName('csrf')->item(0);
				$markup3Xml = $markup2Xml->getElementsByTagName('variable');

				foreach ($markup3Xml as $val) {
					$this->_security['firewall']['config']['csrf']['template']['variable'][$val->getAttribute('name')]['type'] = $val->getAttribute('type');
					$this->_security['firewall']['config']['csrf']['template']['variable'][$val->getAttribute('name')]['name'] = $val->getAttribute('name');
					$this->_security['firewall']['config']['csrf']['template']['variable'][$val->getAttribute('name')]['value'] = $val->getAttribute('value');
				}
			}
			
			protected function _setFirewallConfigConnect(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('config')->item(0);
				$this->_session[$markupXml->getElementsByTagName('connect')->item(0)->getAttribute('name')] = '';
				$this->_security['firewall']['config']['connect']['name'] = $markupXml->getElementsByTagName('connect')->item(0)->getAttribute('name');
				$this->_security['firewall']['config']['connect']['no'] = $markupXml->getElementsByTagName('connect')->item(0)->getAttribute('no');
				$this->_security['firewall']['config']['connect']['yes'] = $markupXml->getElementsByTagName('connect')->item(0)->getAttribute('yes');
			}
			
			protected function _setFirewallAccess(){
				$nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
				$node3Xml = $node2Xml->getElementsByTagName('access')->item(0);
				$markupXml = $node3Xml->getElementsByTagName('url');
				
				foreach($markupXml as $cle => $val){
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
											$t = new template($this->_security['firewall']['config']['forbidden']['template']['src'], 'GCfirewallForbiddenGrade', 0);
											foreach($this->_security['firewall']['config']['forbidden']['template']['variable'] as $cle => $val){
												if($val['type'] == 'var'){
													$t->assign(array($val['name']=>$val['value']));
												}
												else{
													$t->assign(array($val['name']=>$this->useLang($val['value'])));
												}
											}
											$t -> show();

											$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['controller'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car son grade n\'est pas autorisé', __FILE__, __LINE__, ERROR);
											
											return false;
										}
									}
									else{
										$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['controller'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car il doit être connecté', __FILE__, __LINE__, ERROR);
										
										if($this->getUrl($this->_security['firewall']['config']['login']['source']['id'],  $this->_security['firewall']['config']['login']['source']['vars']) != ""){
											header('Location: '.$this->getUrl($this->_security['firewall']['config']['login']['source']['id'], $this->_security['firewall']['config']['login']['source']['vars']));
											return false;
										}
										else{
											$this->_addError('Le parefeu n\'a pas pu exécuter la redirection vers l\'url d\'id '.$this->_security['firewall']['config']['login']['source']['id'], __FILE__, __LINE__, FATAL);
											return false;
										}

										return false;
									}
								break;
								
								case 'false':
									//il faut être déconnecté pour pouvoir accéder à la page
									if($this->_checkConnected() == false){
										return true;
									}
									else{
										$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['controller'].'/'.$_GET['action'].' comme interdit pour cet utilisateur car il est connecté', __FILE__, __LINE__, ERROR);
										
										if($this->getUrl($this->_security['firewall']['config']['default']['source']['id'],  $this->_security['firewall']['config']['default']['source']['vars']) != ""){
											header('Location: '.$this->getUrl($this->_security['firewall']['config']['default']['source']['id'], $this->_security['firewall']['config']['default']['source']['vars']));
											return false;
										}
										else{
											$this->_addError('Le parefeu n\'a pas pu exécuter la redirection vers l\'url d\'id '.$this->_security['firewall']['config']['default']['source']['id'], __FILE__, __LINE__, FATAL);
											return false;
										}
										
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
							$t = new template($this->_security['firewall']['config']['csrf']['template']['src'], 'GCfirewallForbiddenCsrf', 0, $this->_lang);
											
							foreach($this->_security['firewall']['config']['csrf']['template']['variable'] as $cle => $val){
								if($val['type'] == 'var'){
									$t->assign(array($val['name']=>$val['value']));
								}
								else{
									$t->assign(array($val['name']=>$this->useLang($val['value'])));
								}
							}

							$t -> show();

							$this->_addError('Le parefeu a identifié l\'accès à la page '.$_GET['controller'].'/'.$_GET['action'].' comme interdit pour cet utilisateur : faille CSRF', __FILE__, __LINE__, ERROR);
							
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
				if(isset($_GET[$this->_security['firewall']['config']['csrf']['name']]) && isset($_POST[$this->_security['firewall']['config']['csrf']['name']]) && $_SESSION[$this->_security['firewall']['config']['csrf']['name']]!=''){
					if($_GET[$this->_security['firewall']['config']['csrf']['name']] == $_SESSION[$this->_security['firewall']['config']['csrf']['name']] && $_POST[$this->_security['firewall']['config']['csrf']['name']] == $_SESSION[$this->_security['firewall']['config']['csrf']['name']]){
						return true; //Get et POST et Sessions correspondent
					}
					else{
						return false;
					}
				}
				elseif((isset($_GET[$this->_security['firewall']['config']['csrf']['name']]) || isset($_POST[$this->_security['firewall']['config']['csrf']['name']])) && $_SESSION[$this->_security['firewall']['config']['csrf']['name']]!=''){
					if($_GET[$this->_security['firewall']['config']['csrf']['name']] == $_SESSION[$this->_security['firewall']['config']['csrf']['name']]){
						return true; //Get et Sessions correspondent
					}
					elseif($_POST[$this->_security['firewall']['config']['csrf']['name']] == $_SESSION[$this->_security['firewall']['config']['csrf']['name']]){
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
				//renvoie true si le statut du membre est présent dans la liste des statuts autorisés false dans l'autre cas
				if((isset($_SESSION[''.$this->_security['roles_hierarchy']['name'].'']) 
					&& in_array($_SESSION[$this->_security['roles_hierarchy']['name']], $this->_security['firewall']['access']['url']['access'])) 
					|| (isset($this->_security['firewall']['access']['url']['access']['*']) && $this->_security['firewall']['access']['url']['access']['*'] == '*')){
					return true;
				}
				else{
					return false;
				}
			}
			
			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			protected function useLang($sentence, $var = array()){
				return $this->_langInstance->loadSentence($sentence, $var);
			}
			
			public function __destruct(){
			}
		}
	}