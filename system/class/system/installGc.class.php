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

		public  function __construct($file = '', $bdd = null, $bddname = null, $lang = 'fr'){
			$this->_setFile($file, $bdd, $bddname);

			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();

			//fichiers dont la modification est interdite
			$this->_forbiddenFile = array(
				ROUTE, MODOGCCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM, INSTALLED,
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
				SYSTEM_PATH.'.htaccess', SYSTEM_PATH.'class/autoload.php', SYSTEM_PATH.CLASS_HELPER_PATH.'bbcodeGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'captchaGc.class.php',
				SYSTEM_PATH.CLASS_HELPER_PATH.'dateGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'dirGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'downloadGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'feedGc.class.php',
				SYSTEM_PATH.CLASS_HELPER_PATH.'fileGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'mailGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'modoGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'paginationGc.class.php',
				SYSTEM_PATH.CLASS_HELPER_PATH.'pictureGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'socialGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'sqlGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'textGc.class.php',
				SYSTEM_PATH.CLASS_HELPER_PATH.'uploadGc.class.php', SYSTEM_PATH.CLASS_HELPER_PATH.'zipGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'antispamGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'appDevGc.class.php',
				SYSTEM_PATH.CLASS_SYSTEM_PATH.'applicationGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'cacheGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'configGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'exceptionGc.class.php',
				SYSTEM_PATH.CLASS_SYSTEM_PATH.'firewallGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'Gcsystem.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'generalGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'installGc.class.php',
				SYSTEM_PATH.CLASS_SYSTEM_PATH.'langGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'logGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'modelGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'pluginGc.class.php',
				SYSTEM_PATH.CLASS_SYSTEM_PATH.'routerGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'templateGc.class.php', SYSTEM_PATH.CLASS_SYSTEM_PATH.'terminalGc.class.php', LANG_PATH.'en.xml', LANG_PATH.'fr.xml',
				LANG_PATH.'nl.xml',
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
				LIB_PATH
			);
		}

		public function getConflit(){
			return $this->_conflit;
		}

		protected function _getNameId(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
				$id = $this->_domXml->getElementsByTagName('install')->item(0)->getAttribute("id");
				if($this->_domXml->load(INSTALLED)){
					$return = true;
					$this->_nodeXml = $this->_domXml->getElementsByTagName('installed')->item(0);
					$this->_markupXml = $this->_nodeXml->getElementsByTagName('install');
					foreach($this->_markupXml as $sentence){
						if ($sentence->hasAttribute('id') && $sentence->hasAttribute('name')
							&& preg_match('#^(([0-9a-zA-Z]{18})[\.]([0-9a-zA-Z]{8}))#isU', strval($sentence->getAttribute("id"))) 
							&& strlen($sentence->getAttribute("id")) == 28
							&& strval($sentence->getAttribute("name")) != ''
						){
						
							$return = true;
							$this->_id = $sentence->getAttribute("id");
							$this->_name = $sentence->getAttribute("name");
						}
						else{
							$this->_conflit = false;
							$return = false;
						}
					}

					return $return;
				}
				else{
					return false;
				}
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
						foreach($this->_markupXml as $sentence){
							if($sentence->hasAttribute('id') && $sentence->hasAttribute('url') && $sentence->hasAttribute('rubrique') 
								&& $sentence->hasAttribute('action') && $sentence->hasAttribute('vars') && $sentence->hasAttribute('cache')
							){
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
						foreach($this->_markupXml as $sentence){
							if($sentence->hasAttribute('type') && ($sentence->getAttribute('type') == 'helper' || $sentence->getAttribute('type') == 'lib') 
								&& $sentence->hasAttribute('name') && $sentence->hasAttribute('access') && $sentence->hasAttribute('enabled') && $sentence->hasAttribute('include')
							){
								if(!preg_match('#[\.class\.php]$#isU', strval($sentence->getAttribute('access')))){
									$this->_conflit = false;
									$this->_addError('les sections plugin de la section plugins du fichier install.xml possèdent des attributs incorrect.', __FILE__, __LINE__, ERROR);
									$return = false;
								}
							}
							else{
								$this->_conflit = false;
								$this->_addError('les sections plugin de la section plugins du fichier install.xml possèdent des attributs incorrect.', __FILE__, __LINE__, ERROR);
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
							if($sentence->hasAttribute('id') && $sentence->hasAttribute('connected') && ($sentence->getAttribute('connected') == 'true' || $sentence->getAttribute('connected') == 'false')){
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
								if($sentence->hasChildNodes()){
									$this->_markup2Xml = $sentence->getElementsByTagName('lang');
									foreach($this->_markup2Xml as $key2=>$sentence2){
										if($sentence2->hasAttribute('lang')){
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

				if($this->_nodeXml->hasChildNodes() && (is_object($this->_bdd) || $this->_bddName == null)){
					$this->_conflit = false;
					$this->_addError('La connexion sql entrée en paramètre n\'est pas valide. Le plugin ne peut pas exécuter les requêtes necéssaires.', __FILE__, __LINE__, ERROR);
				}
				
				if(!is_object($this->_bdd) && $this->_bddName != null){
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

				//on installe les nouveaux fichiers
				$this->_installFiles();
			}
			else{
				return false;
			}
		}

		protected function _installConfigRoutes(){

		}

		protected function _installConfigApp(){

		}

		protected function _installConfigPlugins(){

		}

		protected function _installConfigFirewalls(){

		}

		protected function _installConfigLangs(){

		}

		protected function _installConfigSqls(){

		}

		protected function _installFiles(){

		}

		public function uninstall($id){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->loadXml(INSTALLED){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('install')->item(0);
			}
			else{
				$this->_addError('Le fichier de désinstallation '.INSTALLED.' est endommagé.', __FILE__, __LINE__, ERROR);
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