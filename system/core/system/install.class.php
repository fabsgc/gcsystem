
<?php
	/**
	 * @file : install.class.php
	 * @author : fab@c++
	 * @description : class gérant l'installation de addons externes
	 * @version : 2.3 Bêta
	*/

	namespace system{
		class install{
			use error, langInstance, general;
			
			protected $_file                             ;
			protected $_zip                              ;
			protected $_zipContent              = array(); //toutes les données du XML chargé
			protected $_xmlContent              = array(); 
			protected $_conflit                 = true   ; //true = pas de conflits, false = conflits
			protected $_forbiddenFile           = array();
			protected $_forbiddenDir            = array();
			protected $_forbiddenCreateDir      = array();
			protected $_authorizedDir           = array();
			protected $_id                      = ''     ;
			protected $_name                    = ''     ;
			protected $_bdd                     = null   ;
			protected $_bddName                 = null   ;
			protected $_readMe                  = ''     ;
			protected $_conflitUninstall        = true   ; //true = pas de conflits, false = conflits

			public  function __construct($file = '', $bdd = null, $bddname = null, $lang = 'fr'){
				if($file!=''){$this->_setFile($file, $bdd, $bddname); }

				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();

				//fichiers dont la modification est interdite
				$this->_forbiddenFile = array(
					ROUTE, MODOCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM, ADDON, CRON, ERRORPERSO,
					MODEL_PATH.'index'.MODEL_EXT.'.php', MODEL_PATH.'terminal'.MODEL_EXT.'.php', 
					CONTROLLER_PATH.'index'.CONTROLLER_EXT.'.php', CONTROLLER_PATH.'terminal'.CONTROLLER_EXT.'.php', FUNCTION_GENERIQUE,
					TEMPLATE_PATH.ERRORDOCUMENT_PATH.'httpError'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCerror'.TEMPLATE_EXT, 
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCmodel'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCnewcontroller'.TEMPLATE_EXT, 
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCspam'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT, 
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCTerminal'.TEMPLATE_EXT, 
					SYSTEM_PATH.'.htaccess', SYSTEM_PATH.'class/autoload.php', CLASS_PATH.CLASS_HELPER_PATH.'bbcode.class.php', CLASS_PATH.CLASS_HELPER_PATH.'captcha.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'ftp.class.php', CLASS_PATH.CLASS_HELPER_PATH.'date.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dir.class.php', CLASS_PATH.CLASS_HELPER_PATH.'download.class.php', CLASS_PATH.CLASS_HELPER_PATH.'feed.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'file.class.php', CLASS_PATH.CLASS_HELPER_PATH.'mail.class.php', CLASS_PATH.CLASS_HELPER_PATH.'modo.class.php', CLASS_PATH.CLASS_HELPER_PATH.'pagination.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'picture.class.php', CLASS_PATH.CLASS_HELPER_PATH.'sql.class.php', CLASS_PATH.CLASS_HELPER_PATH.'text.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'upload.class.php', CLASS_PATH.CLASS_HELPER_PATH.'zip.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'antispam.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'appDev.class.php',
					CLASS_EXCEPTION, CLASS_BACKUP, CLASS_CRON, CLASS_INSTALL, CLASS_ANTISPAM, CLASS_FIREWALL, CLASS_CONTROLLER, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL,CLASS_ENGINE,CLASS_LOG,CLASS_CACHE, CLASS_TEMPLATE, CLASS_LANG, CLASS_appDev, CLASS_TERMINAL, CLASS_ERROR_PERSO,
					LANG_PATH.'en.xml', LANG_PATH.'fr.xml', LANG_PATH.'nl.xml'
				);
				
				//répertoires existants où il est interdit de créer des fichiers ou de toucher à des fichiers
				$this->_forbiddenDir = array(
					CACHE_PATH, 
					TEMPLATE_PATH.ERRORDOCUMENT_PATH, TEMPLATE_PATH.GCSYSTEM_PATH,
					IMG_PATH.GCSYSTEM_PATH,
					CLASS_PATH.CLASS_SYSTEM_PATH,
					LANG_PATH,
					LOG_PATH,
					LIB_PATH.'FormsGC/'
				);

				//répertoires où il est interdit de créer de nouveaux répertoires
				$this->_forbiddenCreateDir = array(
					CACHE_PATH,
					APP_CONFIG_PATH,
					MODEL_PATH,
					CONTROLLER_PATH,
					TEMPLATE_PATH.ERRORDOCUMENT_PATH, TEMPLATE_PATH.GCSYSTEM_PATH,
					IMG_PATH.GCSYSTEM_PATH,
					SYSTEM_PATH, CLASS_PATH, CLASS_PATH.CLASS_SYSTEM_PATH, CLASS_PATH.CLASS_HELPER_PATH,
					LANG_PATH,
					LOG_PATH,
					LIB_PATH.'FormsGC/'
				);

				$this->_authorizedDir = array(
					LIB_PATH,
					FILE_PATH,
					IMG_PATH,
					CSS_PATH,
					JS_PATH,
					TEMPLATE_PATH
				);
			}

			public function getConflit(){
				return $this->_conflit;
			}

			public function getConflitUninstall(){
				return $this->_conflitUninstall;
			}

			public function getError(){
				return $this->_error;
			}

			public function getReadMe(){
				return $this->_xmlContent['readme'];
			}

			protected function _getNameId(){
				$domXml = new \DomDocument('1.0', CHARSET);
				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$this->_id = $domXml->getElementsByTagName('install')->item(0)->getAttribute("id");
					$this->_name = $domXml->getElementsByTagName('install')->item(0)->getAttribute("name");

					if(preg_match('#^(([0-9a-zA-Z]{19})[\.]([0-9a-zA-Z]{8}))#isU', $this->_id) 
						&& strlen($this->_id) == 28
						&& strval($this->_name) != ''
					){
						$return = true;
					}
					else{
						$this->_conflit = false;
						$return = false;
					}

					return $return;
				}
				else{
					return false;
				}
			}

			public function check(){
				if($this->_zip->getIsExist()==true){
					$this->_zipContent = $this->_zip->getContentFileZip();

					//on check si le fichier install.xml est valide
					$domXml = new \DomDocument('1.0', CHARSET);
					if($domXml->loadXml($this->_zipContent['install.xml'])){                    
						//on récupère les attributs id et name de l'Add-on et on vérifie si ils sont corrects
						if($this->_getNameId() == true){
							//on check l'intégrité du fichier d'installation
							if($this->_checkInstallFile()){
								//on check si le fichier n'a pas déjà été installé
								if($this->_checkIsInstalled() == true){
									//on check les fichiers autorisés $this->_forbiddenFile
									$this->_checkFilesForbidden();

									//on check les fichiers non interdits mais entrant en conflits avec d'autres fichiers existants
									$this->_checkFilesExist();

									//on check ensuite les répertoires existants où il est interdit de créer des fichiers ou de toucher à des fichiers $this->_forbiddenDir
									$this->_checkDirs();

									//on check ensuite les répertoires où il est interdit de créer de nouveaux répertoires $this->_forbiddenCreateDir
									$this->_checkCreateDirs();

									//on check ensuite les conflits dans le install.xml avec les fichiers de configurations
										//routes
										$this->_checkConfigRoutes();

										//apps
										$this->_checkConfigApp();

										//plugins
										$this->_checkConfigPlugins();

										//firewalls
										$this->_checkConfigFirewalls();

										//cron
										$this->_checkConfigCrons();

										//errorperso
										$this->_checkConfigErrorPerso();

										//langs
										$this->_checkConfigLangs();

										//sqls
										$this->_checkConfigSqls();
	                                
	                                    //print_r($this->_xmlContent);

										return $this->_conflit; // faut penser à retourner ce truc hein ^^
								}
								else{
									$this->_conflit = false;
									$this->_addError('l\'add-on a déjà été installé. L\'installation de cette version de l\'add-on a échoué', __FILE__, __LINE__, ERROR);
									return false;
								}
							}
							else{
								$this->_conflit = false;
								$this->_addError('le fichier install.xml est endommagé, il manque des paramètres afin qu\'il puisse être lu et utilisé correctement. L\'installation de cette version de l\'add-on a échoué', __FILE__, __LINE__, ERROR);
								return false;
							}
						}
						else{
							$this->_conflit = false;
							$this->_addError('Les paramètres id et name de l\'add-on sont manquants ou incorrects. L\'installation de cette version de l\'add-on a échoué', __FILE__, __LINE__, ERROR);
							return false;
						}
					}
					else{
						$this->_conflit = false;
						$this->_addError('le fichier install.xml est endommagé. L\'installation de l\'add-on a échoué', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_conflit = false;
					$this->_addError('le fichier zip est endommagé ou inaccessible. L\'installation de l\'add-on a échoué', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			protected function _checkInstallFile(){
				$domXml = new \DomDocument('1.0', CHARSET);
				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$return = true;
	                
	                $this->_xmlContent['id'] = $domXml->getElementsByTagName('install')->item(0)->getAttribute("id");
	                $this->_xmlContent['name'] = $domXml->getElementsByTagName('install')->item(0)->getAttribute("name");
	                
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$node2Xml = $nodeXml->getElementsByTagName('routes')->item(0);
					
					//on check la balise routes
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section routes du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('route');

						if(is_object($markupXml)){
							foreach($markupXml as $key => $sentence){
								if($sentence->hasAttribute('id') && $sentence->hasAttribute('url') && $sentence->hasAttribute('controller') 
									&& $sentence->hasAttribute('action') && $sentence->hasAttribute('vars') && $sentence->hasAttribute('cache')
								){
									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['routes'][$key] = array(
										'id' => $sentence->getAttribute('id'),
										'url' => $sentence->getAttribute('url'),
										'controller' =>  $sentence->getAttribute('controller'),
										'action' => $sentence->getAttribute('action'),
										'vars' =>  $sentence->getAttribute('vars'),
										'cache' => $sentence->getAttribute('cache')
									);
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section route n°'.$key.' de la section routes du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('apps')->item(0);
					
					//on check la balise apps
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section apps du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('app');

						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								if($sentence->hasAttribute('id') && $sentence->hasAttribute('value')){
									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['apps'][$key] = array(
										'id' => $sentence->getAttribute('id'),
										'value' => $sentence->getAttribute('value')
									);
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section app n°'.$key.' de la section apps du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('plugins')->item(0);
					
					//on check la balise plugins
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section plugins du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('plugin');

						if(is_object($node2Xml)){
							foreach($markupXml as $key => $sentence){
								if($sentence->hasAttribute('type') && ($sentence->getAttribute('type') == 'helper' || $sentence->getAttribute('type') == 'lib') 
									&& $sentence->hasAttribute('name') && $sentence->hasAttribute('access') && $sentence->hasAttribute('enabled') && $sentence->hasAttribute('include')
								){
									if(!preg_match('#[\.class\.php]$#isU', strval($sentence->getAttribute('access')))){
										$this->_conflit = false;
										$this->_addError('la section plugin n°'.$key.' de la section plugins du fichier install.xml possède des attributs incorrect.', __FILE__, __LINE__, ERROR);
										$return = false;
									}else{
										//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
										$this->_xmlContent['plugins'][$key] = array(
											'type' => $sentence->getAttribute('type'),
											'name' => $sentence->getAttribute('name'),
											'access' =>  $sentence->getAttribute('access'),
											'enabled' => $sentence->getAttribute('enabled'),
											'include' =>  $sentence->getAttribute('include')
										);
									}
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section plugin n°'.$key.' de la section plugins du fichier install.xml possède des attributs incorrect.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('firewalls')->item(0);
					
					//on check la balise firewals
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section firewalls du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('firewall');
						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								if($sentence->hasAttribute('id') && $sentence->hasAttribute('connected') && $sentence->hasAttribute('access') && ($sentence->getAttribute('connected') == 'true' || $sentence->getAttribute('connected') == 'false')){
									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['firewalls'][$key] = array(
										'id' => $sentence->getAttribute('id'),
										'connected' => $sentence->getAttribute('connected'),
										'access' =>  $sentence->getAttribute('access')
									);
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section firewall n°'.$key.' de la section firewalls du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('crons')->item(0);

					//on check la balise crons
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section crons du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('cron');
						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								if($sentence->hasAttribute('id') && $sentence->hasAttribute('controller') && $sentence->hasAttribute('action') && $sentence->hasAttribute('time')){
									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['crons'][$key] = array(
										'id' => $sentence->getAttribute('id'),
										'controller' => $sentence->getAttribute('controller'),
										'action' => $sentence->getAttribute('action'),
										'time' => $sentence->getAttribute('time')
									);
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section cron n°'.$key.' de la section crons du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('errors')->item(0);

					//on check la balise errors
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section errors du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('error');
						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								if($sentence->hasAttribute('id') && $sentence->hasAttribute('template')){
									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['errors'][$sentence->getAttribute('id')]['id'] = $sentence->getAttribute('id');
									$this->_xmlContent['errors'][$sentence->getAttribute('id')]['template'] = $sentence->getAttribute('template');

									if($sentence->hasChildNodes()){
										$markup2Xml = $sentence->getElementsByTagName('var');
										foreach($markup2Xml as $key2=>$sentence2){
											if($sentence2->hasAttribute('id') && $sentence2->hasAttribute('type')){
												$this->_xmlContent['errors'][$sentence->getAttribute('id')]['vars'][$sentence2->getAttribute('id')] = array(
													'id' => $sentence2->getAttribute('id'),
													'type' => $sentence2->getAttribute('type'),
													'value' => $sentence2->nodeValue
												);
											}
											else{
												$this->_conflit = false;
												$this->_addError('la section var n°'.$key2.' de la section error n°'.$key.' de la section errors du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
												$return = false;
											}
										}
									}
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section error n°'.$key.' de la section errors du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('sqls')->item(0);

					//on check la balise sqls
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section sqls du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('sql');
						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
								$this->_xmlContent['sqls'][$key] = array(
									'value' => $sentence->nodeValue
								);
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('langs')->item(0);
					
					//on check la balise langs
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section langs du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('sentence');
						if(is_object($markupXml)){
							foreach($markupXml as $key=>$sentence){
								if($sentence->hasAttribute('id')){

									//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
									$this->_xmlContent['langs'][$key]['id'] = $sentence->getAttribute('id');

									if($sentence->hasChildNodes()){
										$markup2Xml = $sentence->getElementsByTagName('lang');
										foreach($markup2Xml as $key2=>$sentence2){
											if($sentence2->hasAttribute('lang')){
												$this->_xmlContent['langs'][$key]['sentence'][$sentence2->getAttribute('lang')] = $sentence2->nodeValue;
											}
											else{
												$this->_conflit = false;
												$this->_addError('la section lang n°'.$key2.' de la section sentence n°'.$key.' de la section langs du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
												$return = false;
											}
										}
									}
								}
								else{
									$this->_conflit = false;
									$this->_addError('la section sentence n°'.$key.' de la section langs du fichier install.xml possède des attributs incorrects.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
						}
					}
	                
	                $node2Xml = $nodeXml->getElementsByTagName('files')->item(0);
					
					//on check la balise apps
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section files du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						$markupXml = $node2Xml->getElementsByTagName('file');

						if(is_object($markupXml)){
	                        $this->_xmlContent['files'] = array();
							foreach($markupXml as $sentence){
								array_push($this->_xmlContent['files'], $sentence->nodeValue);
							}
						}
					}

					$node2Xml = $nodeXml->getElementsByTagName('readme')->item(0);
					
					//on check la balise readme
					if(!is_object($node2Xml)){
						$this->_conflit = false;
						$this->_addError('la section readme du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
						$return = false;
					}
					else{
						//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
						$this->_xmlContent['readme'] = $node2Xml->nodeValue;
					}

					return $return;
				}
				else{
					return false;
				}
			}

			protected function _checkIsInstalled(){
	            $this->_dom2Xml = new \DomDocument('1.0', CHARSET);
	                
	            if($this->_dom2Xml->load(ADDON)){
	                $return = true;
	                $nodeXml = $this->_dom2Xml->getElementsByTagName('installed')->item(0);
	                $markupXml = $nodeXml->getElementsByTagName('install');
	            
	                foreach($markupXml as $sentence){
	                    if ($id == intval($this->_xmlContent['id'])){
	                        $return = false;
	                    }
	                }
	                
	                return $return;
	            }
	            else{
	                $this->_conflit = false;
	                $this->_addError('le fichier listant les plugins installés '.ADDON.' est endommagé ou inexistant.', __FILE__, __LINE__, ERROR);
	                return false;
	            }
			}

			protected function _checkFilesForbidden(){
				foreach ($this->_xmlContent['files'] as $value) {
					if(in_array($value, $this->_forbiddenFile)){
						$this->_conflit = false;
						$this->_addError('le fichier '.$value.' est un fichier système. Un add-on n\'est pas en droit de le modifier', __FILE__, __LINE__, ERROR);
					}
				}
			}

			protected function _checkFilesExist(){
				foreach ($this->_xmlContent['files'] as $value) {
					if(!in_array($value, $this->_forbiddenFile) && file_exists($value)){
						$this->_conflit = false;
						$this->_addError('l\'add-on semble rentrer en conflit avec un fichier existant : '.$value.'.', __FILE__, __LINE__, ERROR);
					}
				}
			}

			protected function _checkDirs(){
	            foreach ($this->_xmlContent['files'] as $value) {
	                foreach ($this->_forbiddenDir as $key2 => $value2) {
	                    if(preg_match('#'.preg_quote($value2).'#isU', strval($value))){
	                        $this->_conflit = false;
	                        $this->_addError('le répertoire '.$value2.' est un répertoire système or le fichier '.$key.' va y être ajouté par l\'add-on. Un add-on n\'est pas en droit d\'y ajouter ou d\'y modifier des fichiers systèmes', __FILE__, __LINE__, ERROR);
	                    }
	                }
	            }		
			}

			protected function _checkCreateDirs(){
	            foreach ($this->_xmlContent['files'] as $value) {
	                $dir = explode('/', $value);
	                
	                for($i = 0; $i < count($dir) -1;$i++){
	                    foreach ($this->_forbiddenCreateDir as $value2){
							if(preg_match('#^'.preg_quote($value2).'(.+)#isU', $dir[$i]) && !preg_match('#^system\/lib\/(.*)#isU', $dir[$i])){
								$this->_conflit = false;
								$this->_addError('le répertoire '.$key.' veut être ajouté dans le répertoire '.$value2.' qui est un répertoire système. Un add-on n\'est pas en droit d\'y ajouter des répertoires', __FILE__, __LINE__, ERROR);
	                            break;
	                        }
						}
	                }
	            }
			}

			protected function _checkConfigRoutes(){
				//on ouvre la section routes du install.xml puis on vérifie que y a aucun conflit avec d'autres route sur l'id et l'url
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->load(ROUTE)){
					$nodeXml = $domXml->getElementsByTagName('routes')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('route');

					foreach($this->_xmlContent['files'] as $key => $value){
						foreach ($nodeXml as $key2 => $value2) {
							if($value['id'] == $value2->getAttribute('id')){
								$this->_conflit = false;
								$this->_addError('l\'id de route "'.$value['id'].'" de la section n°'.$key.' du route est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}

							if($value['url'] == $value2->getAttribute('url')){
								$this->_conflit = false;
								$this->_addError('l\'url de route "'.$value['url'].'" de la section n°'.$key.' du route est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigApp(){
				//on ouvre app.xml et on vérifie si certaines define ne sont pas déjà prises
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->load(APPCONFIG)){
					$nodeXml = $domXml->getElementsByTagName('definitions')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('define');

					foreach($this->_xmlContent['apps'] as $key => $value){
						foreach ($nodeXml as $key2 => $value2) {
							if($value['id'] == $value2->getAttribute('id')){
								$this->_conflit = false;
								$this->_addError('l\'id de define "'.$value['id'].'" de la section n°'.$key.' est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigPlugins(){
				//on ouvre plugins.xml et on vérifie si name et access ne sont pas deja pris
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(PLUGIN)){
					$node2Xml = $domXml->getElementsByTagName('plugins')->item(0);
					$node2Xml = $nodeXml->getElementsByTagName('plugin');

					foreach($this->_xmlContent['plugins'] as $key => $value){
						foreach ($nodeXml as $key2 => $value2) {
							if($value['name'] == $value2->getAttribute('name')){
								$this->_conflit = false;
								$this->_addError('le nom de plugin '.$value['name'].' de la section n°'.$key.' est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}

							if($value['access'] == $value2->getAttribute('access')){
								$this->_conflit = false;
								$this->_addError('l\'accès du plugin "'.$value['access'].'" de la section n°'.$key.' est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigFirewalls(){
				//on ouvre firewall.xml et on vérifie access id n'existe pas déjà
				$domXml = new \DomDocument('1.0', CHARSET);

				if($this->_xmlContent['firewalls'] && $domXml->load(FIREWALL)){
					$nodeXml = $domXml->getElementsByTagName('security')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('firewall')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('access')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('url');

					foreach($nodeXml as $key => $value){
						foreach ($node2Xml as $key2 => $value2) {
							if($value->getAttribute('id') == $value2->getAttribute('id')){
								$this->_conflit = false;
								$this->_addError('l\'id du parefeu "'.$value->getAttribute('id').'" de la section n°'.$key.' est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigCrons(){
				//on ouvre cron.xml et on vérifie si les id n'existe pas déjà
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(CRON)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('crons')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('cron');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('crons')->item(0);
					$node2Xml = $node2Xml->getElementsByTagName('cron');

					foreach($nodeXml as $key => $value){
						foreach ($node2Xml as $key2 => $value2){
							if($value->getAttribute('id') == $value2->getAttribute('id')){
								$this->_conflit = false;
								$this->_addError('l\'id de cron '.$value->getAttribute('id').' de la section n°'.$key.' du cron est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigErrorPerso(){
				//on ouvre errorperso.xml et on vérifie si les id n'existe pas déjà
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(ERRORPERSO)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('errors')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('error');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('errorperso')->item(0);
					$node2Xml = $node2Xml->getElementsByTagName('errors')->item(0);
					$node2Xml = $node2Xml->getElementsByTagName('error');

					foreach($nodeXml as $key => $value){
						foreach ($node2Xml as $key2 => $value2){
							if($value->getAttribute('id') == $value2->getAttribute('id')){
								$this->_conflit = false;
								$this->_addError('l\'id d\'errorperso '.$value->getAttribute('id').' de la section n°'.$key.' du fichier errorpersoGx.xml est déjà utilisé par le projet. Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
							}
						}
					}
				}
			}

			protected function _checkConfigLangs(){
				//on ouvre [lang].xml si il existe et on vérifie si sentence n'existe pas déjà
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('langs')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('sentence');

					foreach($nodeXml as $key => $value){
						$langsFile = $this->_mkmap(LANG_PATH);

						foreach ($langsFile as $file){
							if($this->_dom2Xml->load($file)){
								$node2Xml = $this->_dom2Xml->getElementsByTagName('lang')->item(0);
								$node2Xml = $node2Xml->getElementsByTagName('sentence');

								foreach($node2Xml as $key2 => $value2){

									if($value->getAttribute('id') == $value2->getAttribute('id')){
										$this->_conflit = false;
										$this->_addError('l\'id de la phrase "'.$value->getAttribute('id').'" de la section n°'.$key.' est déjà utilisé par le projet dans le fichier de lang "'.$file.'". Un add-on ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
									}
								}
							}
						}
					}
				}
			}

			protected function _checkConfigSqls(){
				//modifier condition pour la prod, mais en dev, la co sql est trop lente
				//on check juste si la co sql est valide
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('sqls')->item(0);

					if($nodeXml->hasChildNodes() && (!is_object($this->_bdd[$this->_bddName]))){
						$this->_conflit = false;
						$this->_addError('La connexion sql entrée en paramètre n\'est pas valide. L\'add-on ne peut pas exécuter les requêtes necéssaires.', __FILE__, __LINE__, ERROR);
					}
					
					if(is_object($this->_bdd[$this->_bddName])){
						//dans les fichiers de controllers et manager, on corrige ça : $this->bdd[mauvaiseconnexion]
						foreach ($this->_zipContent as $key => $value) {
							if(preg_match('#(\.class\.php)$#isU', $key) && preg_match('#^app\/(.*)$#isU', $key)){
								$this->_zipContent[$key] = preg_replace('#\$this\->bdd\[(.*)\]#isU', '$$this->bdd[\''.$this->_bddName.'\']', $value);
							}
						}
					}
				}
			}

			public function install(){
				$result = '';
				if($this->_zip->getIsExist()==true && $this->_conflit == true){
					//on remplit les fichiers de configs
						//routes
						$result= $this->_installConfigRoutes();

						//apps
						$result.= $this->_installConfigApp();

						//plugins
						$result.= $this->_installConfigPlugins();

						//firewalls
						$result.= $this->_installConfigFirewalls();

						//crons
						$result.= $this->_installConfigCrons();

						//errorperso
						$result.= $this->_installConfigErrorspersos();

						//langs
						$result.= $this->_installConfigLangs();

						//sqls
						$result.= $this->_installConfigSqls();

					//installed
					$result.= $this->_installConfigInstalled();

					//on installe les nouveaux fichiers
					$result.= $this->_installFiles();

					return $result;
				}
				else{
					return false;
				}
			}

			protected function _installConfigRoutes(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(ROUTE)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('routes')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('route');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('routes')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('route');
						$markup2Xml->setAttribute("id", $value->getAttribute('id'));
						$markup2Xml->setAttribute("url", $value->getAttribute('url'));
						$markup2Xml->setAttribute("controller", $value->getAttribute('controller'));
						$markup2Xml->setAttribute("action", $value->getAttribute('action'));
						$markup2Xml->setAttribute("vars", $value->getAttribute('vars'));
						$markup2Xml->setAttribute("cache", $value->getAttribute('cache'));

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout route, id : '.$value->getAttribute('id').'</span>';
					}

					$this->_dom2Xml->save(ROUTE);
				}

				return $result;
			}

			protected function _installConfigApp(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(APPCONFIG)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('apps')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('app');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('definitions')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('define');
						$markup2Xml->setAttribute("id", $value->getAttribute('id'));
						$markup2Xml->setAttribute("value", $value->getAttribute('value'));

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout constante utilisateur, id : '.$value->getAttribute('id').'</span>';
					}

					$this->_dom2Xml->save(APPCONFIG);
				}

				return $result;
			}

			protected function _installConfigPlugins(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(PLUGIN)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('plugins')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('plugin');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('plugins')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('plugin');
						$markup2Xml->setAttribute("type", $value->getAttribute('type'));
						$markup2Xml->setAttribute("name", $value->getAttribute('name'));
						$markup2Xml->setAttribute("access", $value->getAttribute('access'));
						$markup2Xml->setAttribute("enabled", $value->getAttribute('enabled'));
						$markup2Xml->setAttribute("include", $value->getAttribute('include'));

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout plugin, nom : '.$value->getAttribute('name').'</span>';
					}

					$this->_dom2Xml->save(PLUGIN);
				}

				return $result;
			}

			protected function _installConfigFirewalls(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(FIREWALL)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('firewalls')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('firewall');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
					$node2Xml = $node2Xml->getElementsByTagName('firewall')->item(0);
					$node2Xml = $node2Xml->getElementsByTagName('access')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('url');
						$markup2Xml->setAttribute("id", $value->getAttribute('id'));
						$markup2Xml->setAttribute("connected", $value->getAttribute('connected'));
						$markup2Xml->setAttribute("access", $value->getAttribute('access'));

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout parefeu, id : '.$value->getAttribute('id').'</span>';
					}

					$this->_dom2Xml->save(FIREWALL);
				}

				return $result;
			}

			protected function _installConfigCrons(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(CRON)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('crons')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('cron');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('crons')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('cron');
						$markup2Xml->setAttribute("id", $value->getAttribute('id'));
						$markup2Xml->setAttribute("controller", $value->getAttribute('controller'));
						$markup2Xml->setAttribute("action", $value->getAttribute('action'));
						$markup2Xml->setAttribute("time", $value->getAttribute('time'));
						$markup2Xml->setAttribute("executed", '');

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout cron, id : '.$value->getAttribute('id').'</span>';
					}

					$this->_dom2Xml->save(CRON);
				}

				return $result;
			}

			protected function _installConfigErrorspersos(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

				if($domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(ERRORPERSO)){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('errors')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('error');

					$node2Xml = $this->_dom2Xml->getElementsByTagName('errorperso')->item(0);
					$node2Xml = $this->_dom2Xml->getElementsByTagName('errors')->item(0);

					foreach($nodeXml as $key => $value){
						$markup2Xml = $this->_dom2Xml->createElement('error');
						$markup2Xml->setAttribute("id", $value->getAttribute('id'));
						$markup2Xml->setAttribute("template", $value->getAttribute('template'));

						foreach ($this->_xmlContent['errors'][$value->getAttribute('id')]['vars'] as $key2 => $value2) {
							$this->_markup3Xml = $this->_dom2Xml->createElement('var');
							$this->_markup3Xml->setAttribute("id", $value2['id']);
							$this->_markup3Xml->setAttribute("type", $value2['type']);
							
							$texte = $this->_dom2Xml->createTextNode($value2['value']);
							$this->_markup3Xml->appendChild($texte);

							$markup2Xml->appendChild($this->_markup3Xml);
						}

						$node2Xml->appendChild($markup2Xml);

						$result .= '<br />><span style="color: chartreuse">ajout errorperso, id : '.$value->getAttribute('id').'</span>';
					}
				}

				$this->_dom2Xml->save(ERRORPERSO);

				return $result;
			}

			protected function _installConfigLangs(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				$this->_dom2Xml = new \DomDocument('1.0', CHARSET);
				$id = '';

				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('langs')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('sentence');

					foreach($nodeXml as $key => $value){
						$id = $value->getAttribute('id');

						$markupXml = $value->getElementsByTagName('lang');

						foreach ($markupXml as $key2 => $value2){
							if(!file_exists(LANG_PATH.$value2->getAttribute('lang').LANG_EXT)){
								$monfichier = fopen(LANG_PATH.$value2->getAttribute('lang').LANG_EXT, 'a');						
									$t= new template(GCSYSTEM_PATH.'GClang', 'GClang', '0');
									$t->setShow(FALSE);
									fputs($monfichier, $t->show());
								fclose($monfichier);
							}

							if($this->_dom2Xml->load(LANG_PATH.$value2->getAttribute('lang').LANG_EXT)){
								$node2Xml = $this->_dom2Xml->getElementsByTagName('lang')->item(0);

								$markup2Xml = $this->_dom2Xml->createElement('sentence');
								$markup2Xml->setAttribute("id", $id);
								$texte = $this->_dom2Xml->createTextNode($value2->nodeValue);
								$markup2Xml->appendChild($texte);
								$node2Xml->appendChild($markup2Xml);
							}

							$this->_dom2Xml->save(LANG_PATH.$value2->getAttribute('lang').LANG_EXT);

							$result .= '<br />><span style="color: chartreuse">ajout fichier langue '.$value2->getAttribute('lang').', id : '.$id.'</span>';
						}
					}
				}

				return $result;
			}

			protected function _installConfigSqls(){
				$result = '';
				$query = new sql($this->_bdd[$this->_bddName]);

				$domXml = new \DomDocument('1.0', CHARSET);
				$id = '';

				if($domXml->loadXml($this->_zipContent['install.xml'])){
					$nodeXml = $domXml->getElementsByTagName('install')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('sqls')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('sql');

					foreach ($nodeXml as $key => $value) {
						$requete = preg_replace(
							array('#&lt;#isU', '#&gt;#isU', '#&quot;#isU', '#&39;#isU'), 
							array('<', '>', '"', "'"),
						$value->nodeValue);

						$query->query($key, $requete);
						$query->fetch($key, sql::PARAM_NORETURN);

						$result .= '<br />><span style="color: chartreuse">exécution requête : '.$requete.'</span>';
					}
				}

				return $result;
			}

			protected function _installFiles(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->load(ADDON)){
					$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('install');

					foreach ($nodeXml as $key => $value) {
						if($value->getAttribute('id') == $this->_id){

							$markupXml = $value->getElementsByTagName('files')->item(0);

							foreach ($this->_zipContent as $key2 => $value2) {
								if(preg_match('#[\/]$#isU', strval($key2)) && $key2 != 'install.xml'){
									//c'est un dossier, on va l'ajouter si il n'existe pas déjà
									
									if(!is_dir($key2)){
										mkdir($key2);

										$file = $domXml->createElement('file');
										$file->setAttribute("path", $key2);

										$markupXml->appendChild($file);

										$result .= '<br />><span style="color: chartreuse">ajout répertoire : '.$key2.'</span>';
									}
								}
							}

							foreach ($this->_zipContent as $key2 => $value2) {
								if(!preg_match('#[\/]$#isU', strval($key2)) && $key2 != 'install.xml'){
									//c'est un fichier, on va l'ajouter si il n'existe pas déjà

									if(!is_file($key2) && !file_exists($key2)){
										$monfichier = fopen($key2, 'a');							
											fputs($monfichier, $value2);
										fclose($monfichier);

										$file = $domXml->createElement('file');
										$file->setAttribute("path", $key2);

										$markupXml->appendChild($file);

										$result .= '<br />><span style="color: chartreuse">ajout fichier : '.$key2.'</span>';
									}
								}
							}

							$domXml->save(ADDON);
						}
					}
				}

				return $result;
			}

			protected function _installConfigInstalled(){
				$result = '';
				$domXml = new \DomDocument('1.0', CHARSET);
				if($domXml->load(ADDON)){
					$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
					$markupXml = $domXml->createElement('install', '');
					$markupXml->setAttribute("id", $this->_id);
					$markupXml->setAttribute("name", $this->_name);
						$file = $domXml->createElement('files', '');
						$markupXml->appendChild($file);
						$xml = $domXml->createElement('xml');

						//ajouter les config du route
						$routes = $domXml->createElement('routes');
						foreach ($this->_xmlContent['routes'] as $key => $value) {
							$route = $domXml->createElement('route');
							$route->setAttribute('id', $value['id']);
							$route->setAttribute('url', $value['url']);
							$route->setAttribute('controller', $value['controller']);
							$route->setAttribute('action', $value['action']);
							$route->setAttribute('vars', $value['vars']);
							$route->setAttribute('cache', $value['cache']);
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config de app
						$routes = $domXml->createElement('apps');
						foreach ($this->_xmlContent['apps'] as $key => $value) {
							$route = $domXml->createElement('app');
							$route->setAttribute('id', $value['id']);
							$route->setAttribute('value', $value['value']);
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config de plugin
						$routes = $domXml->createElement('plugins');
						foreach ($this->_xmlContent['plugins'] as $key => $value) {
							$route = $domXml->createElement('plugin');
							$route->setAttribute('type', $value['type']);
							$route->setAttribute('name', $value['name']);
							$route->setAttribute('access', $value['access']);
							$route->setAttribute('enabled', $value['enabled']);
							$route->setAttribute('include', $value['include']);
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config du firewall
						$routes = $domXml->createElement('firewalls');
						foreach ($this->_xmlContent['firewalls'] as $key => $value) {
							$route = $domXml->createElement('firewall');
							$route->setAttribute('id', $value['id']);
							$route->setAttribute('connected', $value['connected']);
							$route->setAttribute('access', $value['access']);
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config du cron
						$routes = $domXml->createElement('crons');
						foreach ($this->_xmlContent['crons'] as $key => $value) {
							$route = $domXml->createElement('cron');
							$route->setAttribute('id', $value['id']);
							$route->setAttribute('ccontroller', $value['controller']);
							$route->setAttribute('action', $value['action']);
							$route->setAttribute('time', $value['time']);
							$route->setAttribute('executed', '');
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config d'errorperso
						$routes = $domXml->createElement('errors');
						foreach ($this->_xmlContent['errors'] as $key => $value) {
							$route = $domXml->createElement('error');
							$route->setAttribute('id', $value['id']);
							$route->setAttribute('template', $value['template']);

							foreach ($value['vars'] as $key2 => $value2) {
								$route2 = $domXml->createElement('var');
								$route2->setAttribute('id', $value2['id']);
								$route2->setAttribute('type', $value2['type']);

								$texte = $domXml->createTextNode($value2['value']);
								$route2->appendChild($texte);

								$route->appendChild($route2);
							}
							
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config du sql
						$routes = $domXml->createElement('sqls');
						foreach ($this->_xmlContent['sqls'] as $key => $value) {
							$route = $domXml->createElement('sql', $value['value']);
							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config des langs
						$routes = $domXml->createElement('langs');
						foreach ($this->_xmlContent['langs'] as $key => $value) {
							$route = $domXml->createElement('sentence');
							$route->setAttribute('id', $value['id']);

							foreach ($value['sentence'] as $key2 => $value2) {
								$route2 = $domXml->createElement('lang', $value2);
								$route2->setAttribute('lang', $key2);
								$route->appendChild($route2);
							}

							$routes->appendChild($route);
						}
						$xml->appendChild($routes);

						//ajouter les config du readme
						$routes = $domXml->createElement('readme', $this->_xmlContent['readme']);
						$xml->appendChild($routes);

						$markupXml->appendChild($xml);

					$nodeXml->appendChild($markupXml);
					$domXml->save(ADDON);
				}

				$result .= '<br />><span style="color: chartreuse">génération fichier '.ADDON.'</span>';
				return $result;
			}

			public function checkUninstall($id){
				$domXml = new \DomDocument('1.0', CHARSET);
				if($domXml->load(ADDON)){
					$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('install');

					$plugin = false;

					foreach ($nodeXml as $key => $value){
						if($value->getAttribute('id') == $id){ //l'id existe \o/
							$plugin = true;
							$node2Xml = $value->getElementsByTagName('files')->item(0);
							$node2Xml = $node2Xml->getElementsByTagName('file');

							foreach ($node2Xml as $key2 => $value2){ // on vérifie si on a le droit de supprimer le fichier
								if(in_array($value2->getAttribute('path'), $this->_forbiddenFile)){
									$this->_conflitUninstall = false;
									$this->_addError('La désinstallation veut supprimer un fichier système : '.$value2->getAttribute('path'), __FILE__, __LINE__, ERROR);
								}

								foreach ($this->_forbiddenDir as $key => $value) { //on vérifie si on ne supprime pas des fichiers situés dans des répertoires interdits
									if(preg_match('#^'.$value2->getAttribute('path').'#isU', $value)){
										$this->_conflitUninstall = false;
										$this->_addError('La désinstallation veut supprimer un fichier système : '.$value2->getAttribute('path'), __FILE__, __LINE__, ERROR);
									}
								}
							}
						}
					}

					if($plugin == false){
						$this->_conflitUninstall = false;
						$this->_addError('L\add-on d\'id '.$id.' n\'existe pas', __FILE__, __LINE__, ERROR);
						return false;
					}

					if($this->_conflitUninstall == false){
						return false;
					}
					else{
						return true;
					}
				}
				else{
					$this->_conflitUninstall = false;
					$this->_addError('Le fichier de désinstallation '.ADDON.' est endommagé', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			public function uninstall($id){
				$result = '';
				
				if($this->getConflitUninstall() == true){
					$domXml = new \DomDocument('1.0', CHARSET);
					$this->_dom2Xml = new \DomDocument('1.0', CHARSET);
					
					if($domXml->load(ADDON)){
						$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
						$nodeXml = $nodeXml->getElementsByTagName('install');

						foreach ($nodeXml as $key => $value){
							if($value->getAttribute('id') == $id){ //l'id existe \o/
								
								/* ###### suppression des fichiers ###### */
									//on commence par supprimer les fichiers mis en place par le plugin
									$node2Xml = $value->getElementsByTagName('files')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('file');

									//on supprime d'abord les fichiers
									foreach ($node2Xml as $key2 => $value2){
										if(!preg_match('#\/$#i', $value2->getAttribute('path'))){
											$result = '<br />><span style="color: chartreuse">suppression fichier : '.$value2->getAttribute('path').'</span>';
											unlink($value2->getAttribute('path'));
										}
									}

									//on supprime ensuite les fichiers
									foreach($node2Xml as $key2 => $value2){
										if(preg_match('#\/$#i', $value2->getAttribute('path'))){
											$result .= '<br />><span style="color: chartreuse">suppression répertoire : '.$value2->getAttribute('path').'</span>';
											rmdir($value2->getAttribute('path'));
										}
									}

								/* ###### SUPPRESSION DES LIGNES DANS LES FICHIERS DE CONFIG ###### */
									/* ###### ROUTE ###### */
									$node2Xml = $value->getElementsByTagName('routes')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('route');

									if($this->_dom2Xml->load(ROUTE)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('routes')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('route');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('id') == $value3->getAttribute('id')){
													$result .= '<br />><span style="color: chartreuse">suppression route, id : '.$value3->getAttribute('id').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier de route '.ROUTE.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(ROUTE);

									/* ###### APP ###### */
									$node2Xml = $value->getElementsByTagName('apps')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('app');

									if($this->_dom2Xml->load(APPCONFIG)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('definitions')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('define');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('id') == $value3->getAttribute('id')){
													$result .= '<br />><span style="color: chartreuse">suppression constante utilisateur, id : '.$value3->getAttribute('id').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier de constantes '.APPCONFIG.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(APPCONFIG);

									/* ###### PLUGIN ###### */
									$node2Xml = $value->getElementsByTagName('plugins')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('plugin');

									if($this->_dom2Xml->load(PLUGIN)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('plugins')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('plugin');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('name') == $value3->getAttribute('name')){
													$result .= '<br />><span style="color: chartreuse">suppression plugin, id : '.$value3->getAttribute('name').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier de plugins '.PLUGIN.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(PLUGIN);

									/* ###### FIREWALL ###### */
									$node2Xml = $value->getElementsByTagName('firewalls')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('firewall');

									if($this->_dom2Xml->load(FIREWALL)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('firewall')->item(0);
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('access')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('url');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('id') == $value3->getAttribute('id')){
													$result .= '<br />><span style="color: chartreuse">suppression parefeu, id : '.$value3->getAttribute('id').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier du parefeu '.FIREWALL.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(FIREWALL);

									/* ###### CRON ###### */
									$node2Xml = $value->getElementsByTagName('crons')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('cron');

									if($this->_dom2Xml->load(CRON)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('crons')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('cron');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('id') == $value3->getAttribute('id')){
													$result .= '<br />><span style="color: chartreuse">suppression cron, id : '.$value3->getAttribute('id').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier de crons '.CRON.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(CRON);


									/* ###### ERROR ###### */
									$node2Xml = $value->getElementsByTagName('errors')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('error');

									if($this->_dom2Xml->load(ERRORPERSO)){
										$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('errorperso')->item(0);
										$this->_node3Xml = $this->_node3Xml->getElementsByTagName('errors')->item(0);
										$this->_node4Xml = $this->_node3Xml->getElementsByTagName('error');

										foreach ($node2Xml as $key2 => $value2){
											foreach($this->_node4Xml as $key3 => $value3){
												if($value2->getAttribute('id') == $value3->getAttribute('id')){
													$result .= '<br />><span style="color: chartreuse">suppression errorperso, id : '.$value3->getAttribute('id').'</span>';
													$this->_node3Xml->removeChild($value3); 
												}
											}
										}
									}
									else{
										$this->_addError('Le fichier de erreurs personnalisées '.ERRORPERSO.' est endommagé', __FILE__, __LINE__, ERROR);
										return false;
									}

									$this->_dom2Xml->save(ERRORPERSO);

									/* ###### LANG ###### */
									$node2Xml = $value->getElementsByTagName('langs')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('sentence');

									foreach ($node2Xml as $key2 => $value2) {
										$value2->getAttribute('id');
										//on lit toutes les versions

										foreach ($value2->getElementsByTagName('lang') as $key3 => $value3){
											if(file_exists(LANG_PATH.$value3->getAttribute('lang').'.xml')){
												if($this->_dom2Xml->load(LANG_PATH.$value3->getAttribute('lang').'.xml')){
													$this->_node3Xml = $this->_dom2Xml->getElementsByTagName('lang')->item(0);
													$this->_node4Xml = $this->_node3Xml->getElementsByTagName('sentence');

													foreach ($this->_node4Xml as $key4 => $value4) {
														if($value4->getAttribute('id') == $value2->getAttribute('id')){
															$result .= '<br />><span style="color: chartreuse">suppression phrase fichier de langue '.$value3->getAttribute('lang').' , id : '.$value4->getAttribute('id').'</span>';
															$this->_node3Xml->removeChild($value4); 
														}
													}

													$this->_dom2Xml->save(LANG_PATH.$value3->getAttribute('lang').'.xml');
												}
											}
										}
									}

									/* ###### SQL ###### */
									$node2Xml = $value->getElementsByTagName('sqls')->item(0);
									$node2Xml = $node2Xml->getElementsByTagName('sql');

									$result .= '<br />><span style="color: chartreuse">########## requêtes sql exécutées : ################################</span>';

									foreach ($node2Xml as $key2 => $value2){
										$result .= '<br />><span style="color: chartreuse"># '.$value2->nodeValue.'</span>';
									}

									$result .= '<br />><span style="color: chartreuse">############################################################</span>';

									/* ###### README ###### */
									$result .= '<br />><span style="color: chartreuse">########## readme : ################################</span>';
									$result .= '<br />><span style="color: chartreuse"># '.$value->getElementsByTagName('readme')->item(0)->nodeValue.'</span>';
									$result .= '<br />><span style="color: chartreuse">############################################################</span>';
							}
						}

						$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
						$node2Xml = $nodeXml->getElementsByTagName('install');

						foreach ($node2Xml as $key => $value){
							if($value->getAttribute('id') == $id){ //l'id existe \o/
								$nodeXml->removeChild($value); 
							}
						}

						$domXml->save(ADDON);

						return $result;
					}
					else{
						$this->_addError('Le fichier de désinstallation '.ADDON.' est endommagé', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					return false;
				}
			}

			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}

			protected function _setFile($file, $bdd, $bddname){
				$this->_zip = new zip($file);
				$this->_bdd = $bdd;
				$this->_bddName = $bddname;
			}

			protected function _mkmap($dir){
				$dossier = opendir ($dir);
			   	$result  = array()       ;

				while ($fichier = readdir ($dossier)){
					if ($fichier != "." && $fichier != ".."){
						if(filetype($dir.$fichier) == 'dir'){
							$this->_mkmap($dir.$fichier.'/');
						}
						elseif($fichier!='.htaccess'){
							array_push($result, $dir.$fichier);
						}					
					}       
				}
				closedir ($dossier);

				return $result;
			}

			public  function __destruct(){
			}
		}
	}