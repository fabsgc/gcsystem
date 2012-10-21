<?php
	/**
	 * @file : installGc.class.php
	 * @author : fab@c++
	 * @description : class gérant l'installation de rubriques externes
	 * @version : 2.0 bêta
	*/

	class installGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait
		
		protected $_file                             ;
		protected $_zip                              ;
		protected $_zipContent              = array();
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
				ROUTE, MODOGCCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM, INSTALLED, CRON, ERRORPERSO,
				MODEL_PATH.'index'.MODEL_EXT.'.php', MODEL_PATH.'terminal'.MODEL_EXT.'.php', 
				RUBRIQUE_PATH.'index'.RUBRIQUE_EXT.'.php', RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php', FUNCTION_GENERIQUE,
				TEMPLATE_PATH.ERRORDUOCUMENT_PATH.'403'.TEMPLATE_EXT, TEMPLATE_PATH.ERRORDUOCUMENT_PATH.'404'.TEMPLATE_EXT, TEMPLATE_PATH.ERRORDUOCUMENT_PATH.'500'.TEMPLATE_EXT,
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCclass'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCerror'.TEMPLATE_EXT, 
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCmodel'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCnewrubrique'.TEMPLATE_EXT, 
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCspam'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT, 
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCTerminal'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT, 
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT, 
				APP_PATH.'.htaccess',
				CSS_PATH.'default.css', CSS_PATH.'index.html', CSS_PATH.'jquery-ui.css',
				FILE_PATH.'index.html',
				IMG_PATH.GCSYSTEM_PATH.'arbo.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/align.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/barre.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/citation.png',
				IMG_PATH.GCSYSTEM_PATH.'bbcode/clin.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/code.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/color.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/email.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/expo.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/float.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/gras.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/heureux.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/hihi.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/huh.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/image.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/index.html', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/italique.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/langue.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/lien.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/liste.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/math.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/mechant.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/pleure.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/rire.png',
				IMG_PATH.GCSYSTEM_PATH.'bbcode/secret.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/siffle.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/sizeup.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/smile.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/son.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/souligne.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/style.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/sub.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/sup.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/tab.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/think.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/titre1.png', 
				IMG_PATH.GCSYSTEM_PATH.'bbcode/titre2.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/triste.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/unsure.png', IMG_PATH.GCSYSTEM_PATH.'bbcode/video.png', 
				IMG_PATH.GCSYSTEM_PATH.'empty_avatar.png', IMG_PATH.GCSYSTEM_PATH.'http.png', IMG_PATH.GCSYSTEM_PATH.'index.html', IMG_PATH.GCSYSTEM_PATH.'logo.png', 
				IMG_PATH.GCSYSTEM_PATH.'logo300_6.png', IMG_PATH.GCSYSTEM_PATH.'memory.png', IMG_PATH.GCSYSTEM_PATH.'sql.png', IMG_PATH.GCSYSTEM_PATH.'time.png', IMG_PATH.GCSYSTEM_PATH.'tpl.png', 
				IMG_PATH.'index.html', IMG_PATH.'jquery/index.html', IMG_PATH.'jquery/ui-bg_flat_0_aaaaaa_40x100.png', IMG_PATH.'jquery/ui-bg_flat_75_ffffff_40x100.png', IMG_PATH.'jquery/ui-bg_glass_55_fbf9ee_1x400.png', 
				IMG_PATH.'jquery/ui-bg_glass_65_ffffff_1x400.png', IMG_PATH.'jquery/ui-bg_glass_75_dadada_1x400.png', IMG_PATH.'jquery/ui-bg_glass_75_e6e6e6_1x400.png', IMG_PATH.'jquery/ui-bg_glass_95_fef1ec_1x400.png',
				IMG_PATH.'jquery/ui-bg_highlight-soft_75_cccccc_1x100.png', IMG_PATH.'jquery/ui-icons_222222_256x240.png', IMG_PATH.'jquery/ui-icons_2e83ff_256x240.png', IMG_PATH.'jquery/ui-icons_454545_256x240.png', IMG_PATH.'jquery/ui-icons_888888_256x240.png',
				IMG_PATH.'jquery/ui-icons_cd0a0a_256x240.png',
				JS_PATH.'index.html', JS_PATH.'inpage.js', JS_PATH.'jquery-ui.min.js', JS_PATH.'jquery.min.js', JS_PATH.'script.js',
				SYSTEM_PATH.'.htaccess', SYSTEM_PATH.'class/autoload.php', CLASS_PATH.CLASS_HELPER_PATH.'bbcodeGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'captchaGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'errorpersoGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dateGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dirGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'downloadGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'feedGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'fileGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'mailGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'modoGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'paginationGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'pictureGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'socialGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'sqlGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'textGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'uploadGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'zipGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'antispamGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'appDevGc.class.php',
				CLASS_PATH.CLASS_SYSTEM_PATH.'applicationGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'cacheGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'configGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'exceptionGc.class.php',
				CLASS_PATH.CLASS_SYSTEM_PATH.'firewallGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'Gcsystem.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'generalGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'installGc.class.php',
				CLASS_PATH.CLASS_SYSTEM_PATH.'langGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'logGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'modelGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'pluginGc.class.php',
				CLASS_PATH.CLASS_SYSTEM_PATH.'backupGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'routerGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'templateGc.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'terminalGc.class.php', LANG_PATH.'en.xml', LANG_PATH.'fr.xml',
				LANG_PATH.'nl.xml'
			);
			
			//répertoires existants où il est interdit de créer des fichiers ou de toucher à des fichiers
			$this->_forbiddenDir = array(
				CACHE_PATH, 
				TEMPLATE_PATH.ERRORDUOCUMENT_PATH, TEMPLATE_PATH.GCSYSTEM_PATH,
				IMG_PATH.GCSYSTEM_PATH,
				CLASS_PATH.CLASS_SYSTEM_PATH,
				LANG_PATH,
				LOG_PATH,
				LIB_PATH.'FormsGC/', LIB_PATH.'geshi/'
			);

			//répertoires où il est interdit de créer de nouveaux répertoires
			$this->_forbiddenCreateDir = array(
				CACHE_PATH,
				APP_CONFIG_PATH,
				MODEL_PATH,
				RUBRIQUE_PATH,
				TEMPLATE_PATH.ERRORDUOCUMENT_PATH, TEMPLATE_PATH.GCSYSTEM_PATH,
				IMG_PATH.GCSYSTEM_PATH,
				SYSTEM_PATH, CLASS_PATH, CLASS_PATH.CLASS_SYSTEM_PATH, CLASS_PATH.CLASS_HELPER_PATH,
				LANG_PATH,
				LOG_PATH,
				LIB_PATH.'FormsGC/', LIB_PATH.'geshi/'
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
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$this->_id = $this->_domXml->getElementsByTagName('install')->item(0)->getAttribute("id");
				$this->_name = $this->_domXml->getElementsByTagName('install')->item(0)->getAttribute("name");

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
				$this->_domXml = new DomDocument('1.0', CHARSET);
				if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
					//on récupère les attributs id et name du plugin et on vérifie si ils sont corrects
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

								//on check ensuite les conflits dans le install.xml
									//routes
									$this->_checkConfigRoutes();

									//apps
									$this->_checkConfigApp();

									//plugins
									$this->_checkConfigPlugins();

									//firewalls
									$this->_checkConfigFirewalls();

									//langs
									$this->_checkConfigLangs();

									//sqls
									$this->_checkConfigSqls();
							}
							else{
								$this->_conflit = false;
								$this->_addError('le plugin a déjà été installé. L\'installation de cette version du plugin a échoué', __FILE__, __LINE__, ERROR);
								return false;
							}
						}
						else{
							$this->_conflit = false;
							$this->_addError('le fichier install.xml est endommagé, il manque des paramètres afin qu\'il puisse être lu et utilisé correctement. L\'installation de cette version du plugin a échoué', __FILE__, __LINE__, ERROR);
							return false;
						}
					}
					else{
						$this->_conflit = false;
						$this->_addError('Les paramètres id et name du plugin sont manquants ou incorrects. L\'installation de cette version du plugin a échoué', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_conflit = false;
					$this->_addError('le fichier install.xml est endommagé. L\'installation du plugin a échoué', __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			else{
				$this->_conflit = false;
				$this->_addError('le fichier zip est endommagé ou inaccessible. L\'installation du plugin a échoué', __FILE__, __LINE__, ERROR);
				return false;
			}
		}

		protected function _checkInstallFile(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$return = true;
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('routes')->item(0);
				
				//on check la balise routes
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section routes du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('route');

					if(is_object($this->_markupXml)){
						foreach($this->_markupXml as $key => $sentence){
							if($sentence->hasAttribute('id') && $sentence->hasAttribute('url') && $sentence->hasAttribute('rubrique') 
								&& $sentence->hasAttribute('action') && $sentence->hasAttribute('vars') && $sentence->hasAttribute('cache')
							){
								//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
								$this->_xmlContent['routes'][$key] = array(
									'id' => $sentence->getAttribute('id'),
									'url' => $sentence->getAttribute('url'),
									'rubrique' =>  $sentence->getAttribute('rubrique'),
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

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('apps')->item(0);
				
				//on check la balise apps
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section apps du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('app');

					if(is_object($this->_markupXml)){
						foreach($this->_markupXml as $key=>$sentence){
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

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('plugins')->item(0);
				
				//on check la balise plugins
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section plugins du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('plugin');

					if(is_object($this->_node2Xml)){
						foreach($this->_markupXml as $key => $sentence){
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

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewalls')->item(0);
				
				//on check la balise firewals
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section firewalls du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('firewall');
					if(is_object($this->_markupXml)){
						foreach($this->_markupXml as $key=>$sentence){
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

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('sqls')->item(0);
				
				//on check la balise sqls
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section sqls du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('sql');
					if(is_object($this->_markupXml)){
						foreach($this->_markupXml as $key=>$sentence){
							//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
							$this->_xmlContent['sqls'][$key] = array(
								'value' => $sentence->nodeValue
							);
						}
					}
				}

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('langs')->item(0);
				
				//on check la balise langs
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section langs du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					$this->_markupXml = $this->_node2Xml->getElementsByTagName('sentence');
					if(is_object($this->_markupXml)){
						foreach($this->_markupXml as $key=>$sentence){
							if($sentence->hasAttribute('id')){

								//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
								$this->_xmlContent['langs'][$key]['id'] = $sentence->getAttribute('id');

								if($sentence->hasChildNodes()){
									$this->_markup2Xml = $sentence->getElementsByTagName('lang');
									foreach($this->_markup2Xml as $key2=>$sentence2){
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

				$this->_node2Xml = $this->_nodeXml->getElementsByTagName('readme')->item(0);
				
				//on check la balise readme
				if(!is_object($this->_node2Xml)){
					$this->_conflit = false;
					$this->_addError('la section readme du fichier install.xml est endommagée.', __FILE__, __LINE__, ERROR);
					$return = false;
				}
				else{
					//on stocke toutes les données dans un array pour pouvoir les utiliser plus tard à l'installation
					$this->_xmlContent['readme'] = $this->_node2Xml->nodeValue;
				}

				return $return;
			}
			else{
				return false;
			}
		}

		protected function _checkIsInstalled(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$id = $this->_domXml->getElementsByTagName('install')->item(0)->getAttribute("id");
				if($this->_domXml->load(INSTALLED)){
					$return = true;
					$this->_nodeXml = $this->_domXml->getElementsByTagName('installed')->item(0);
					$this->_markupXml = $this->_nodeXml->getElementsByTagName('install');
					foreach($this->_markupXml as $sentence){
						if ($id == $sentence->getAttribute("id")){
							$return = false;
						}
					}

					return $return;
				}
				else{
					$this->_conflit = false;
					$this->_addError('le fichier listant les plugins installés '.INSTALLED.' est endommagé ou inexistant.', __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			else{
				return false;
			}
		}

		protected function _checkFilesForbidden(){
			foreach ($this->_zipContent as $key => $value) {
				if(!preg_match('#[\/]$#isU', strval($key)) && $key != 'install.xml'){
					if(in_array($key, $this->_forbiddenFile)){
						$this->_conflit = false;
						$this->_addError('le fichier '.$key.' est un fichier système. Un plugin n\'est pas en droit de le modifier', __FILE__, __LINE__, ERROR);
					}
				}
			}
		}

		protected function _checkFilesExist(){
			foreach ($this->_zipContent as $key => $value) {
				if(!preg_match('#[\/]$#isU', strval($key)) && $key != 'install.xml'){
					if(!in_array($key, $this->_forbiddenFile) && file_exists($key)){
						$this->_conflit = false;
						$this->_addError('le plugin semble rentrer en conflit avec un fichier existant : '.$key.'.', __FILE__, __LINE__, ERROR);
					}
				}
			}
		}

		protected function _checkDirs(){
			foreach ($this->_zipContent as $key => $value) {
				if(!preg_match('#[\/]$#isU', strval($key)) && $key != 'install.xml'){
					foreach ($this->_forbiddenDir as $key2 => $value2) {
						if(preg_match('#'.preg_quote($value2).'#isU', strval($key))){
							$this->_conflit = false;
							$this->_addError('le répertoire '.$value2.' est un répertoire système or le fichier '.$key.' va y être ajouté par le plugin. Un plugin n\'est pas en droit d\'y ajouter ou d\'y modifier des fichiers systèmes', __FILE__, __LINE__, ERROR);
						}
					}
				}
			}			
		}

		protected function _checkCreateDirs(){
			foreach ($this->_zipContent as $key => $value){
				if(preg_match('#[\/]$#isU', strval($key)) && $key != 'install.xml'){
					foreach ($this->_forbiddenCreateDir as $key2 => $value2){
						if(preg_match('#^'.preg_quote($value2).'(.+)#isU', $key) 
							&& strlen($key) >= strlen($value2) 
							&& !in_array($key, $this->_forbiddenCreateDir) 
							&& strlen($value2) >= $this->_checkCreateLongDirs($key)
							&& !preg_match('#^system\/lib\/(.*)#isU', $key)
						){
							$this->_conflit = false;
							$this->_addError('le répertoire '.$key.' veut être ajouté dans le répertoire '.$value2.' qui est un répertoire système. Un plugin n\'est pas en droit d\'y ajouter des répertoires', __FILE__, __LINE__, ERROR);
						}
					}
				}
			}
		}

		protected function _checkCreateLongDirs($dir){
			//on récupère le sous dossier interdit le plus interne pour que _checkCreateDirs ne renvoie pas 2 erreurs pour le même dossier de plugin
			$result = 0;
			foreach ($this->_forbiddenCreateDir as $key => $value){
				if(preg_match('#^'.preg_quote($value).'(.+)#isU', $dir)){
					if(strlen($value) > strlen($result) && strlen($result) > 0){
						$result = strlen($value);
						
					}
					elseif(strlen($result) == 0){
						$result = strlen($value);
					}
				}
			}

			return $result;
		}

		protected function _checkConfigRoutes(){
			//on ouvre la section routes du install.xml puis on vérifie que y a aucun conflit avec d'autres route sur l'id et l'url
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(ROUTE)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('routes')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('route');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('routes')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('route');

				foreach($this->_nodeXml as $key => $value){
					foreach ($this->_node2Xml as $key2 => $value2) {
						if($value->getAttribute('id') == $value2->getAttribute('id')){
							$this->_conflit = false;
							$this->_addError('l\'id de route '.$value->getAttribute('id').' de la section n°'.$key.' du route est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}

						if($value->getAttribute('url') == $value2->getAttribute('url')){
							$this->_conflit = false;
							$this->_addError('l\'url de route "'.$value->getAttribute('url').'" de la section n°'.$key.' du route est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}
					}
				}
			}
		}

		protected function _checkConfigApp(){
			//on ouvre app.xml et on vérifie si certaines define ne sont pas déjà prises
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(APPCONFIG)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('apps')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('app');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('definitions')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('define');

				foreach($this->_nodeXml as $key => $value){
					foreach ($this->_node2Xml as $key2 => $value2) {
						if($value->getAttribute('id') == $value2->getAttribute('id')){
							$this->_conflit = false;
							$this->_addError('l\'id de define "'.$value->getAttribute('id').'" de la section n°'.$key.' est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}
					}
				}
			}
		}

		protected function _checkConfigPlugins(){
			//on ouvre plugins.xml et on vérifie si name et access ne sont pas déjà pris
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(PLUGIN)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('plugins')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('plugin');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('plugins')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('plugin');

				foreach($this->_nodeXml as $key => $value){
					foreach ($this->_node2Xml as $key2 => $value2) {
						if($value->getAttribute('name') == $value2->getAttribute('name')){
							$this->_conflit = false;
							$this->_addError('le nom de plugin '.$value->getAttribute('name').' de la section n°'.$key.' est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}

						if($value->getAttribute('access') == $value2->getAttribute('access')){
							$this->_conflit = false;
							$this->_addError('l\'accès du plugin "'.$value->getAttribute('access').'" de la section n°'.$key.' est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}

						if($value->getAttribute('name') == $value2->getAttribute('name')){
							$this->_conflit = false;
							$this->_addError('le nom du plugin "'.$value->getAttribute('access').'" de la section n°'.$key.' est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}
					}

					switch($value->getAttribute('type')){
						case 'lib':
							if(isset($this->_zipContent[LIB_PATH.$value->getAttribute('access')])){
								$this->_conflit = false;
								$this->_addError('la lib "'.LIB_PATH.$value->getAttribute('access').' n\'existe pas', __FILE__, __LINE__, ERROR);
							}
						break;

						case 'helper':
							if(isset($this->_zipConten[CLASS_PATH.CLASS_HELPER_PATH.$value->getAttribute('access')])){
								$this->_conflit = false;
								$this->_addError('le helper "'.CLASS_PATH.CLASS_HELPER_PATH.$value->getAttribute('access').'" n\'existe pas', __FILE__, __LINE__, ERROR);
							}
						break;
					}
				}
			}
		}

		protected function _checkConfigFirewalls(){
			//on ouvre firewall.xml et on vérifie access id n'existe pas déjà
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(FIREWALL)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('firewalls')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('firewall');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('firewall')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('url');

				foreach($this->_nodeXml as $key => $value){
					foreach ($this->_node2Xml as $key2 => $value2) {
						if($value->getAttribute('id') == $value2->getAttribute('id')){
							$this->_conflit = false;
							$this->_addError('l\'id du parefeu "'.$value->getAttribute('id').'" de la section n°'.$key.' est déjà utilisé par le projet. Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
						}
					}
				}
			}
		}

		protected function _checkConfigLangs(){
			//on ouvre [lang].xml si il existe et on vérifie si sentence n'existe pas déjà
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('langs')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('sentence');

				foreach($this->_nodeXml as $key => $value){
					$langsFile = $this->_mkmap(LANG_PATH);

					foreach ($langsFile as $file){
						if($this->_dom2Xml->load($file)){
							
							$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('lang')->item(0);
							$this->_node2Xml = $this->_node2Xml->getElementsByTagName('sentence');

							foreach($this->_node2Xml as $key2 => $value2){

								if($value->getAttribute('id') == $value2->getAttribute('id')){
									$this->_conflit = false;
									$this->_addError('l\'id de la phrase "'.$value->getAttribute('id').'" de la section n°'.$key.' est déjà utilisé par le projet dans le fichier de lang "'.$file.'". Un plugin ne peut pas l\'utiliser', __FILE__, __LINE__, ERROR);
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
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('sqls')->item(0);

				if($this->_nodeXml->hasChildNodes() && (!is_object($this->_bdd) || $this->_bddName == null)){
					$this->_conflit = false;
					$this->_addError('La connexion sql entrée en paramètre n\'est pas valide. Le plugin ne peut pas exécuter les requêtes necéssaires.', __FILE__, __LINE__, ERROR);
				}
				
				if(is_object($this->_bdd) && $this->_bddName != null){
					//dans les fichiers de rubriques et manager, on corrige ça : $this->bdd[mauvaiseconnexion]
					foreach ($this->_zipContent as $key => $value) {
						if(preg_match('#(\.class\.php)$#isU', $key) && preg_match('#^app\/(.*)$#isU', $key)){
							$this->_zipContent[$key] = preg_replace('#\$this\->bdd\[(.*)\]#isU', '$$this->bdd[\''.$this->_bddName.'\']', $value);
						}
					}
				}
			}
		}

		public function install(){
			if($this->_zip->getIsExist()==true && $this->_conflit == true){
				//on remplit les fichiers de configs
					//routes
					$this->_installConfigRoutes();

					//apps
					$this->_installConfigApp();

					//plugins
					$this->_installConfigPlugins();

					//firewalls
					$this->_installConfigFirewalls();

					//langs
					$this->_installConfigLangs();

					//sqls
					$this->_installConfigSqls();

				//installed
				$this->_installConfigInstalled();

				//on installe les nouveaux fichiers
				$this->_installFiles();
			}
			else{
				return false;
			}
		}

		protected function _installConfigRoutes(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(ROUTE)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('routes')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('route');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('routes')->item(0);

				foreach($this->_nodeXml as $key => $value){
					$this->_markup2Xml = $this->_dom2Xml->createElement('route');
					$this->_markup2Xml->setAttribute("id", $value->getAttribute('id'));
					$this->_markup2Xml->setAttribute("url", $value->getAttribute('url'));
					$this->_markup2Xml->setAttribute("rubrique", $value->getAttribute('rubrique'));
					$this->_markup2Xml->setAttribute("action", $value->getAttribute('action'));
					$this->_markup2Xml->setAttribute("vars", $value->getAttribute('vars'));
					$this->_markup2Xml->setAttribute("cache", $value->getAttribute('cache'));

					$this->_node2Xml->appendChild($this->_markup2Xml);
				}

				$this->_dom2Xml->save(ROUTE);
			}
		}

		protected function _installConfigApp(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(APPCONFIG)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('apps')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('app');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('definitions')->item(0);

				foreach($this->_nodeXml as $key => $value){
					$this->_markup2Xml = $this->_dom2Xml->createElement('define');
					$this->_markup2Xml->setAttribute("id", $value->getAttribute('id'));
					$this->_markup2Xml->setAttribute("value", $value->getAttribute('value'));
					$this->_node2Xml->appendChild($this->_markup2Xml);
				}

				$this->_dom2Xml->save(APPCONFIG);
			}
		}

		protected function _installConfigPlugins(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(PLUGIN)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('plugins')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('plugin');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('plugins')->item(0);

				foreach($this->_nodeXml as $key => $value){
					$this->_markup2Xml = $this->_dom2Xml->createElement('plugin');
					$this->_markup2Xml->setAttribute("type", $value->getAttribute('type'));
					$this->_markup2Xml->setAttribute("name", $value->getAttribute('name'));
					$this->_markup2Xml->setAttribute("access", $value->getAttribute('access'));
					$this->_markup2Xml->setAttribute("enabled", $value->getAttribute('enabled'));
					$this->_markup2Xml->setAttribute("include", $value->getAttribute('include'));
					$this->_node2Xml->appendChild($this->_markup2Xml);
				}

				$this->_dom2Xml->save(PLUGIN);
			}
		}

		protected function _installConfigFirewalls(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->loadXml($this->_zipContent['install.xml']) && $this->_dom2Xml->load(FIREWALL)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('firewalls')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('firewall');

				$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('firewall')->item(0);
				$this->_node2Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);

				foreach($this->_nodeXml as $key => $value){
					$this->_markup2Xml = $this->_dom2Xml->createElement('url');
					$this->_markup2Xml->setAttribute("id", $value->getAttribute('id'));
					$this->_markup2Xml->setAttribute("connected", $value->getAttribute('connected'));
					$this->_markup2Xml->setAttribute("access", $value->getAttribute('access'));
					$this->_node2Xml->appendChild($this->_markup2Xml);
				}

				$this->_dom2Xml->save(FIREWALL);
			}
		}

		protected function _installConfigLangs(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			$this->_dom2Xml = new DomDocument('1.0', CHARSET);
			$id = '';

			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('langs')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('sentence');

				foreach($this->_nodeXml as $key => $value){
					$id = $value->getAttribute('id');

					$this->_markupXml = $value->getElementsByTagName('lang');

					foreach ($this->_markupXml as $key2 => $value2){
						if(!file_exists(LANG_PATH.$value2->getAttribute('lang').LANG_EXT)){
							$monfichier = fopen(LANG_PATH.$value2->getAttribute('lang').LANG_EXT, 'a');						
								$t= new templateGC(GCSYSTEM_PATH.'GClang', 'GClang', '0');
								$t->setShow(FALSE);
								fputs($monfichier, $t->show());
							fclose($monfichier);
						}

						if($this->_dom2Xml->load(LANG_PATH.$value2->getAttribute('lang').LANG_EXT)){
							$this->_node2Xml = $this->_dom2Xml->getElementsByTagName('lang')->item(0);

							$this->_markup2Xml = $this->_dom2Xml->createElement('sentence');
							$this->_markup2Xml->setAttribute("id", $id);
							$texte = $this->_dom2Xml->createTextNode($value2->nodeValue);
							$this->_markup2Xml->appendChild($texte);
							$this->_node2Xml->appendChild($this->_markup2Xml);
						}

						$this->_dom2Xml->save(LANG_PATH.$value2->getAttribute('lang').LANG_EXT);
					}
				}
			}
		}

		protected function _installConfigSqls(){
			$query = new sqlGc($this->_bdd);

			$this->_domXml = new DomDocument('1.0', CHARSET);
			$id = '';

			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('sqls')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('sql');

				foreach ($this->_nodeXml as $key => $value) {
					$value->nodeValue = preg_replace(
						array('#&lt;#isU', '#&gt;#isU', '#&quot;#isU', '#&#39;#isU'), 
						array('<', '>', '"', "'"),
					$value->nodeValue);
					$query->query($key, $value->nodeValue);
					$query->fetch($key, sqlGc::PARAM_NORETURN);
				}
			}
		}

		protected function _installFiles(){
			$this->_domXml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->load(INSTALLED)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('installed')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('install');

				foreach ($this->_nodeXml as $key => $value) {
					if($value->getAttribute('id') == $this->_id){

						$this->_markupXml = $value->getElementsByTagName('files')->item(0);

						foreach ($this->_zipContent as $key2 => $value2) {
							if(preg_match('#[\/]$#isU', strval($key2)) && $key2 != 'install.xml'){
								//c'est un dossier, on va l'ajouter si il n'existe pas déjà
								
								if(!is_dir($key2)){
									mkdir($key2);

									$file = $this->_domXml->createElement('file');
									$file->setAttribute("path", $key2);

									$this->_markupXml->appendChild($file);
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

									$file = $this->_domXml->createElement('file');
									$file->setAttribute("path", $key2);

									$this->_markupXml->appendChild($file);
								}
							}
						}

						$this->_domXml->save(INSTALLED);
					}
				}
			}
		}

		protected function _installConfigInstalled(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(INSTALLED)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('installed')->item(0);
				$this->_markupXml = $this->_domXml->createElement('install', '');
				$this->_markupXml->setAttribute("id", $this->_id);
				$this->_markupXml->setAttribute("name", $this->_name);
					$file = $this->_domXml->createElement('files', '');
					$this->_markupXml->appendChild($file);
					$xml = $this->_domXml->createElement('xml');

					//ajouter les config du route
					$routes = $this->_domXml->createElement('routes');
					foreach ($this->_xmlContent['routes'] as $key => $value) {
						$route = $this->_domXml->createElement('route');
						$route->setAttribute('id', $value['id']);
						$route->setAttribute('url', $value['url']);
						$route->setAttribute('rubrique', $value['rubrique']);
						$route->setAttribute('action', $value['action']);
						$route->setAttribute('vars', $value['vars']);
						$route->setAttribute('cache', $value['cache']);
						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config de app
					$routes = $this->_domXml->createElement('apps');
					foreach ($this->_xmlContent['apps'] as $key => $value) {
						$route = $this->_domXml->createElement('app');
						$route->setAttribute('id', $value['id']);
						$route->setAttribute('value', $value['value']);
						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config de plugin
					$routes = $this->_domXml->createElement('plugins');
					foreach ($this->_xmlContent['plugins'] as $key => $value) {
						$route = $this->_domXml->createElement('plugin');
						$route->setAttribute('type', $value['type']);
						$route->setAttribute('name', $value['name']);
						$route->setAttribute('access', $value['access']);
						$route->setAttribute('enabled', $value['enabled']);
						$route->setAttribute('include', $value['include']);
						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config du firewall
					$routes = $this->_domXml->createElement('firewalls');
					foreach ($this->_xmlContent['firewalls'] as $key => $value) {
						$route = $this->_domXml->createElement('firewall');
						$route->setAttribute('id', $value['id']);
						$route->setAttribute('connected', $value['connected']);
						$route->setAttribute('access', $value['access']);
						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config du sql
					$routes = $this->_domXml->createElement('sqls');
					foreach ($this->_xmlContent['sqls'] as $key => $value) {
						$route = $this->_domXml->createElement('sql', $value['value']);
						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config du sql
					$routes = $this->_domXml->createElement('langs');
					foreach ($this->_xmlContent['langs'] as $key => $value) {
						$route = $this->_domXml->createElement('sentence');
						$route->setAttribute('id', $value['id']);

						foreach ($value['sentence'] as $key2 => $value2) {
							$route2 = $this->_domXml->createElement('lang', $value2);
							$route2->setAttribute('lang', $key2);
							$route->appendChild($route2);
						}

						$routes->appendChild($route);
					}
					$xml->appendChild($routes);

					//ajouter les config du readme
					$routes = $this->_domXml->createElement('readme', $this->_xmlContent['readme']);
					$xml->appendChild($routes);

					$this->_markupXml->appendChild($xml);

				$this->_nodeXml->appendChild($this->_markupXml);
				$this->_domXml->save(INSTALLED);
			}
		}

		public function checkUninstall($id){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(INSTALLED)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('installed')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('install');

				$plugin = false;

				foreach ($this->_nodeXml as $key => $value){
					if($value->getAttribute('id') == $id){ //l'id existe \o/
						$plugin = true;
						$this->_node2Xml = $value->getElementsByTagName('files')->item(0);
						$this->_node2Xml = $this->_node2Xml->getElementsByTagName('file');

						foreach ($this->_node2Xml as $key2 => $value2){ // on vérifie si on a le droit de supprimer le fichier
							if(in_array($value2->getAttribute('path'), $this->_forbiddenFile)){
								$this->_conflitUninstall = false;
								$this->_addError('La désinstallation veut supprimer un fichier système : '.$value2->getAttribute('path'), __FILE__, __LINE__, ERROR);
							}

							foreach ($this->_forbiddenDir as $key => $value) { //on vérifie si on ne supprime pas des fichiers situés dans des répertoires interdits
								if(preg_match('#'.$value2->getAttribute('path').'#isU', $value)){
									$this->_conflitUninstall = false;
									$this->_addError('La désinstallation veut supprimer un fichier système : '.$value2->getAttribute('path'), __FILE__, __LINE__, ERROR);
								}
							}
						}
					}
				}

				if($plugin == false){
					$this->_conflitUninstall = false;
					$this->_addError('Le plugin d\'id '.$id.' n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			else{
				$this->_conflitUninstall = false;
				$this->_addError('Le fichier de désinstallation '.INSTALLED.' est endommagé', __FILE__, __LINE__, ERROR);
				return false;
			}
		}

		public function uninstall($id){
			if($this->getConflitUninstall() == true){

			}
			else{
				return false;
			}
		}

		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}

		protected function _setFile($file, $bdd, $bddname){
			$this->_zip = new zipGc($file);
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