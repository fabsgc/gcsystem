<?php
	/**
	 * @file : terminalGc.class.php
	 * @author : fab@c++
	 * @description : class g&eacute;rant les fichiers compress&#233;s
	 * @version : 2.0 bêta
	*/

	class terminalGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait
		
		protected $_command                       ; //contenu à traiter
		protected $_stream                        ; //contenu à afficher
		protected $_commandExplode                ; //contenu à traiter
		protected $_result                        = '/ <span style="color: red;">commande non reconnu. Tapez <strong>help</strong> pour avoir la liste des commandes valides</span>'; //resultat du traitement
		protected $_dossier                       ; //dossier
		protected $_fichier                       ; //fichier
		protected $_forbidden                     ; //fichiers interdit
		protected $_updateFile                    ; //fichiers pour la mise à jour
		protected $_updateDir                     ; //fichiers interdit
		protected $_helperDefault                 ; //fichiers interdit
		protected $_configIfNoExist               ; //liste des fichiers de config qui seront mis à jour dans le cas où il ne sont plus disponibles

		public  function __construct($command, $lang = 'fr'){
			$this->_lang=$lang;
			$this->_createLangInstance();
			
			$this->_commandExplode = explode(' ', trim($command));
			$this->_command = '<span style="color: gold;"> '.$command.'</span>';
			$this->_forbidden = array(
				MODEL_PATH.'terminal'.MODEL_EXT.'.php', MODEL_PATH.'index'.MODEL_EXT.'.php', FUNCTION_GENERIQUE, RUBRIQUE_PATH.'index'.RUBRIQUE_EXT.'.php', RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php',
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCrubrique'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_ANTISPAM, CLASS_FIREWALL, CLASS_APPLICATION, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE, CLASS_LOG, CLASS_CACHE, CLASS_EXCEPTION, CLASS_TEMPLATE,CLASS_LANG, CLASS_APPDEVGC, CLASS_TERMINAL,
			);

			$this->_updateFile = array(
				FUNCTION_GENERIQUE, RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php',
				'web.config.php',
				'index.php',
				LIB_PATH.'FormsGC/formsGC.class.php', LIB_PATH.'FormsGC/formsGCValidator.class.php',
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCrubrique'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_ANTISPAM, CLASS_FIREWALL, CLASS_APPLICATION, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE,CLASS_LOG,CLASS_CACHE, CLASS_EXCEPTION, CLASS_TEMPLATE, CLASS_LANG, CLASS_APPDEVGC, CLASS_TERMINAL,
				LANG_PATH.'nl'.LANG_EXT, LANG_PATH.'fr'.LANG_EXT, LANG_PATH.'en'.LANG_EXT,
				CLASS_PATH.CLASS_HELPER_PATH.'fileGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'downloadGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'pictureGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'uploadGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'zipGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'bbcodeGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'captchaGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dateGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'feedGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'mailGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'modoGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'paginationGc.class.php',
				CLASS_PATH.CLASS_HELPER_PATH.'socialGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'sqlGc.class.php', CLASS_PATH.CLASS_HELPER_PATH.'text.class.php'
			); // liste des fichiers systèmes à updater
		

			$this->_helperDefault = array(
				'fileGc', 'downloadGc', 'pictureGc', 'uploadGc', 'zipGc', 'bbcodeGc', 'captchaGc', 'dateGc', 'feedGc', 'mailGc', 'modoGc', 'paginationGc', 'socialGc', 'sqlGc', 'textGc'
			);

			$this->_configIfNoExist = $arrayName = array(
				ROUTE, MODOGCCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM
			);
		}

		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}

		public function parse(){
			if((preg_match('#connect (.+)#', $this->_command) && isset($_SESSION['GC_terminalMdp']) && $_SESSION['GC_terminalMdp']==0) || (preg_match('#connect (.+)#', $this->_command) && empty($_SESSION['GC_terminalMdp']))){
				if(TERMINAL_MDP == $this->_commandExplode[1]){
					$this->_result = '<br />><span style="color: chartreuse;"> Le mot de passe est correct</span>';
					$_SESSION['GC_terminalMdp'] = 1;
				}
				else{
					$this->_result = '<br />><span style="color: red;"> Le mot de passe est incorrect</span>';
				}
			}
			elseif(isset($_SESSION['GC_terminalMdp']) && $_SESSION['GC_terminalMdp']==1){
				if(preg_match('#42(.+)#', $this->_command)){
					$this->_result = '<br />> <span style="color: chartreuse;">La grande question sur la vie, l\'univers et le reste (en anglais : the Ultimate 
						Question of Life, the Universe and Everything) est, dans l\'œuvre de Douglas Adams Le Guide du voyageur galactique, la question ultime sur 
						le sens de la vie. Une réponse est proposée, le nombre 42 mais le problème est que personne n\'a jamais su la question précise. 
						Dans l\'histoire, la réponse est cherchée par le super-ordinateur Pensées Profondes (Deep Thought en version originale — dans les anciennes 
						éditions, Grand Compute Un). Cependant, il n\'était pas assez puissant pour fournir la question ultime après avoir trouvé la réponse 
						(à la suite de 7,5 millions d\'années de calculs). La réponse de Pensées Profondes embarque les protagonistes dans une quête pour 
						découvrir la question qui y correspond.';	
				}
				if(preg_match('#add rubrique (.+)#', $this->_command)){
					$this->_commandExplode[2] = $this->correctName(html_entity_decode(htmlspecialchars_decode($this->_commandExplode[2])));
					$this->_commandExplode[2] = preg_replace('#\.#isU', '', $this->_commandExplode[2]);
					
					if(!in_array(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
						if(!is_file(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
							$monfichier = fopen(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', 'a');						
								$t= new templateGC(GCSYSTEM_PATH.'GCrubrique', 'GCrubrique', '0');
								$t->assign(array(
									'rubrique'=> $this->_commandExplode[2]
								));
								$t->setShow(FALSE);
								fputs($monfichier, $t->show());
							fclose($monfichier);
						}
						
						if(!is_file(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
							$monfichier = fopen(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', 'a');						
								$t= new templateGC(GCSYSTEM_PATH.'GCmodel', 'GCmodel', '0');
								$t->assign(array(
									'rubrique'=> ucfirst($this->_commandExplode[2])
								));
								$t->setShow(FALSE);
								fputs($monfichier, $t->show());
							fclose($monfichier);
						}
						
						$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
						$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php';

						$this->_result = '<br />> <span style="color: chartreuse;">la rubrique <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; cr&#233;&#233;e</span>';
						
						$this->_domXml = new DomDocument('1.0', CHARSET);
						if($this->_domXml->load(ROUTE)){						
							$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
							$sentences = $this->_nodeXml->getElementsByTagName('route');
				
							$rubrique = false;
							
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2]){
									$rubrique = true;
								}
							}
							
							if($rubrique == false){
								$this->_markupXml = $this->_domXml->createElement('route');
								$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
								
								if(isset($this->_commandExplode[3])){
									$this->_markupXml->setAttribute("url", $this->_commandExplode[3]);
								}
								else{
									$this->_markupXml->setAttribute("url", "/".$this->_commandExplode[2]);
								}
								
								$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[2]);
								
								if(isset($this->_commandExplode[4])){
									if($this->_commandExplode[4] == 'empty'){
										$this->_markupXml->setAttribute("action", '');
									}
									else{
										$this->_markupXml->setAttribute("action", $this->_commandExplode[4]);
									}
								}
								else{
									$this->_markupXml->setAttribute("action", "");
								}
								
								if(isset($this->_commandExplode[5])){
									if($this->_commandExplode[5] == 'empty'){
										$this->_markupXml->setAttribute("vars", '');
									}
									else{
										$this->_markupXml->setAttribute("vars", $this->_commandExplode[5]);
									}
								}
								else{
									$this->_markupXml->setAttribute("vars", '');
								}
							
								$this->_nodeXml->appendChild($this->_markupXml);
								$this->_domXml->save(ROUTE);
							}
						}
						
						$this->_domXml = new DomDocument('1.0', CHARSET);
				
						if($this->_domXml->load(FIREWALL)){
							$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
							$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
							$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
							
							$sentences = $this->_node3Xml->getElementsByTagName('url');
							
							$rubrique = false;
							
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
									$rubrique = true;
								}
							}
							
							if($rubrique == false){
								$this->_markupXml = $this->_domXml->createElement('url');
								$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
								
								if(isset($this->_commandExplode[6])){
									$this->_markupXml->setAttribute("connected", $this->_commandExplode[6]);
								}
								else{
									$this->_markupXml->setAttribute("connected", '*');
								}
								
								if(isset($this->_commandExplode[7])){
									$this->_markupXml->setAttribute("access", $this->_commandExplode[7]);
								}
								else{
									$this->_markupXml->setAttribute("access", '*');
								}
								
								$this->_node3Xml->appendChild($this->_markupXml);
								$this->_domXml->save(FIREWALL);
							}
						}
					}
					else{
						$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#add plugin (.+)#', $this->_command)){
					if(isset($this->_commandExplode[2]) && isset($this->_commandExplode[3]) && isset($this->_commandExplode[4]) && isset($this->_commandExplode[5]) && isset($this->_commandExplode[6])){
						$this->_domXml = new DomDocument('1.0', CHARSET);
						if($this->_domXml->load(PLUGIN)){							
							$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
							$sentences = $this->_nodeXml->getElementsByTagName('plugin');
				
							$rubrique = false;
							
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2]){
									$rubrique = true;
								}
							}
							
							if($rubrique == false){
								$this->_markupXml = $this->_domXml->createElement('plugin');
								$this->_markupXml->setAttribute("type", $this->_commandExplode[2]);
								$this->_markupXml->setAttribute("name", $this->_commandExplode[3]);
								$this->_markupXml->setAttribute("access", $this->_commandExplode[4]);
								$this->_markupXml->setAttribute("enabled", $this->_commandExplode[5]);
								$this->_markupXml->setAttribute("include", $this->_commandExplode[6]);
							
								$this->_nodeXml->appendChild($this->_markupXml);
								$this->_domXml->save(PLUGIN);
							}
							
							$this->_result = '<br />> <span style="color: chartreuse;">le plugin <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; ajouté</span>';
						}
					}
					else{
						$this->_result = '<br />><span style="color: red;"> Erreur de syntaxe</span>';
					}
				}
				elseif(preg_match('#delete rubrique (.+)#', $this->_command)){
					if(!in_array(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
						if(is_file(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php')){
							unlink(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php');
							$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
						}
						
						if(is_file(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
							unlink(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php');
							$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php';
						}
						
						$this->_domXml = new DomDocument('1.0', CHARSET);
						
						if($this->_domXml->load(ROUTE)){							
							$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
							$sentences = $this->_nodeXml->getElementsByTagName('route');
				
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2]){
									$this->_nodeXml->removeChild($sentence);    
								}
							}
							$this->_domXml->save(ROUTE);
						}
						
						$this->_domXml = new DomDocument('1.0', CHARSET);
				
						if($this->_domXml->load(FIREWALL)){
							$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
							$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
							$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
							$sentences = $this->_node3Xml->getElementsByTagName('url');
							
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
									$this->_node3Xml->removeChild($sentence);    
								}
							}
							
							$this->_domXml->save(FIREWALL);
						}

						$this->_result = '<br />><span style="color: chartreuse;"> la rubrique <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; supprim&#233;e</span>';
					}
					else{
						$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#set plugin (.+)#', $this->_command)){
					if(isset($this->_commandExplode[2]) && isset($this->_commandExplode[3]) && isset($this->_commandExplode[4]) && isset($this->_commandExplode[5]) && isset($this->_commandExplode[6])){
						$this->_domXml = new DomDocument('1.0', CHARSET);
						if($this->_domXml->load(PLUGIN)){					
							if($this->_domXml->load(PLUGIN)){						
								$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
								$sentences = $this->_nodeXml->getElementsByTagName('plugin');
					
								foreach($sentences as $sentence){
									if ($sentence->getAttribute("name") == $this->_commandExplode[3]){
										$this->_nodeXml->removeChild($sentence);
										
										$this->_markupXml = $this->_domXml->createElement('plugin');
										$this->_markupXml->setAttribute("type", $this->_commandExplode[2]);
										$this->_markupXml->setAttribute("name", $this->_commandExplode[3]);
										$this->_markupXml->setAttribute("access", $this->_commandExplode[4]);
										$this->_markupXml->setAttribute("enabled", $this->_commandExplode[5]);
										$this->_markupXml->setAttribute("include", $this->_commandExplode[6]);
									
										$this->_nodeXml->appendChild($this->_markupXml);
										$this->_domXml->save(PLUGIN);
									}
								}
								$this->_domXml->save(PLUGIN);
							}
							
							$this->_result = '<br />> <span style="color: chartreuse;">le plugin <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; modifié</span>';
						}
					}
					else{
						$this->_result = '<br />><span style="color: red;"> Erreur de syntaxe</span>';
					}
				}
				elseif(preg_match('#delete plugin (.+)#', $this->_command)){
					$this->_domXml = new DomDocument('1.0', CHARSET);
					
					if($this->_domXml->load(PLUGIN)){							
						$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
						$sentences = $this->_nodeXml->getElementsByTagName('plugin');
				
						foreach($sentences as $sentence){
							if ($sentence->getAttribute("name") == $this->_commandExplode[2]){
								$this->_nodeXml->removeChild($sentence);

								$this->_result = '<br />><span style="color: chartreuse;"> le plugin <u>'.$this->_commandExplode[2].'</u> a bien été modifié</span>';
								continue;
							}
							else{
								$this->_result = '<br />><span style="color: chartreuse;"> le plugin <u>'.$this->_commandExplode[2].'</u> n\'existe pas</span>';
							}
						}
						$this->_domXml->save(PLUGIN);
					}
				}
				elseif(preg_match('#add template (.+)#', $this->_command)){
					if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
						$monfichier = fopen(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, 'a');
						fclose($monfichier);
						$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
						$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; cr&#233;&#233;</span>';
					}
					else{
						$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#add helper (.+)#', $this->_command)){
					if(!is_file(CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php') && !in_array($this->_commandExplode[2], $this->_helperDefault)){
						$this->_domXml = new DomDocument('1.0', CHARSET);
					
						if($this->_domXml->load(PLUGIN)){
							$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);

							$this->_markupXml = $this->_domXml->createElement('plugin');
							$this->_markupXml->setAttribute("type", 'helper');
							$this->_markupXml->setAttribute("name", $this->_commandExplode[2]);
							$this->_markupXml->setAttribute("access", $this->_commandExplode[2].'.class.php');
							if(isset($this->_commandExplode[3]) && ($this->_commandExplode[3] == 'true' || $this->_commandExplode[3] == 'false')){
								$this->_markupXml->setAttribute("enabled", $this->_commandExplode[3]);
							}
							else{
								$this->_markupXml->setAttribute("enabled", 'true');
							}
							if(isset($this->_commandExplode[4]) && ($this->_commandExplode[4] == '*' || preg_match('#yes\[(.+)\]#isU', $this->_commandExplode[4]) || preg_match('#no\[(.+)\]#isU', $this->_commandExplode[4]))){
								$this->_markupXml->setAttribute("include", $this->_commandExplode[4]);
							}
							else{
								$this->_markupXml->setAttribute("include", '*');
							}
							
							$this->_nodeXml->appendChild($this->_markupXml);
							$this->_domXml->save(PLUGIN);

							$monfichier = fopen(CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php', 'a');
							  $t= new templateGC(GCSYSTEM_PATH.'GCclass', 'GCclass', '0');
								$t->assign(array(
									'nom'=> $this->_commandExplode[2]
								));
								$t->setShow(FALSE);
								fputs($monfichier, $t->show());
							fclose($monfichier);

							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: chartreuse;"> le fichier class <u>'.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php'.'</u> a bien &#233;t&#233; cr&#233;&#233;</span>';
						}
						else{
							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: red;"> Il semble que votre fichier '.PLUGIN.' ait un problème</span>';
						}						
					}
					else{
						$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#set class (.+)#', $this->_command)){
					if(is_file(CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php')){
						$this->_domXml = new DomDocument('1.0', CHARSET);
					
						if($this->_domXml->load(PLUGIN)){
							$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
							$sentences = $this->_nodeXml->getElementsByTagName('plugin');

							foreach($sentences as $sentence){
								if ($sentence->getAttribute("name") == $this->_commandExplode[2]){
									$this->_nodeXml->removeChild($sentence); 

									$this->_markupXml = $this->_domXml->createElement('plugin');
									$this->_markupXml->setAttribute("type", 'helper');
									$this->_markupXml->setAttribute("name", $this->_commandExplode[2]);
									$this->_markupXml->setAttribute("access", $this->_commandExplode[2].'.class.php');
									if(isset($this->_commandExplode[3]) && ($this->_commandExplode[3] == 'true' || $this->_commandExplode[3] == 'false')){
										$this->_markupXml->setAttribute("enabled", $this->_commandExplode[3]);
									}
									else{
										$this->_markupXml->setAttribute("enabled", 'true');
									}
									if(isset($this->_commandExplode[4]) && ($this->_commandExplode[4] == '*' || preg_match('#yes\[(.+)\]#isU', $this->_commandExplode[4]) || preg_match('#no\[(.+)\]#isU', $this->_commandExplode[4]))){
										$this->_markupXml->setAttribute("include", $this->_commandExplode[4]);
									}
									else{
										$this->_markupXml->setAttribute("include", '*');
									}
								}
							}
							$this->_nodeXml->appendChild($this->_markupXml);
							$this->_domXml->save(PLUGIN);

							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: chartreuse;"> le fichier class <u>'.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php'.'</u> a bien été modifié</span>';
						}
						else{
							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: red;"> Il semble que votre fichier '.PLUGIN.' ait un problème</span>';
						}						
					}
					else{
						$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#delete helper (.+)#', $this->_command)){
					if(is_file(CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php') && !in_array($this->_commandExplode[2], $this->_helperDefault)){
						$this->_domXml = new DomDocument('1.0', CHARSET);
					
					  if($this->_domXml->load(PLUGIN)){			
							$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
							$sentences = $this->_nodeXml->getElementsByTagName('plugin');
				
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("name") == $this->_commandExplode[2]){
									$this->_nodeXml->removeChild($sentence); 
								}
							}
							$this->_domXml->save(PLUGIN);

							unlink(CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php');

							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: chartreuse;"> le fichier class <u>'.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php'.'</u> a bien été supprimé du répertoire et du fichier de plugins</span>';
						}
						else{
							$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
							$this->_result = '<br />><span style="color: red;"> Il semble que votre fichier '.PLUGIN.' ait un problème</span>';
						}	
					}
					else{
						$this->_stream .= '<br />> '.CLASS_PATH.CLASS_HELPER_PATH.$this->_commandExplode[2].'.class.php';
						$this->_result = '<br />><span style="color: red;"> La suppression de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#list template#', $this->_command)){
					$this->_mkmap(TEMPLATE_PATH);
					$this->_result = '<br />><span style="color: chartreuse;"> fichiers de template list&#233;s</span>';
				}
				elseif(preg_match('#list cache#', $this->_command)){
					$this->_mkmap(CACHE_PATH);
					$this->_result = '<br />><span style="color: chartreuse;"> fichiers de cache list&#233;s</span>';
				}
				elseif(preg_match('#delete template (.+)#', $this->_command)){
					if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
						if(is_file(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT)){
							unlink(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT);
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.'</u> a bien &#233;t&#233; supprim&#233;</span>';
						}
						else{
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: red;"> Ce template n\'existe pas</span>';
						}
					}
					else{
						$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#set template (.+) (.+)#', $this->_command)){
					if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
						if(is_file(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT)){
							if(!is_file(TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT)){
								rename(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT);
								$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.' -> '.TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT;
								$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.'</u> a bien &#233;t&#233; r&#233;nomm&#233; en <u>'.TEMPLATE_PATH.$this->_commandExplode[3].'</u></span>';
							}
							else{
								$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT;
								$this->_result = '<br />><span style="color: red;"> Un template porte d&#233;jà le même nom</span>';
							}
						}
						else{
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: red;"> Ce template n\'existe pas</span>';
						}
					}
					else{
						$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#set rubrique (.+) (.+)#', $this->_command)){
					if(!in_array(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
						if(is_file(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php') || is_file(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
							if(!is_file(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php') || !is_file(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php')){
								if(is_file(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php') && !is_file(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php')){
									rename(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php');
									$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php'.' -> '.MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php';
									
									if(is_file(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php') && !is_file(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php')){
										rename(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php');
										$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php'.' -> '.RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php';
									}
									
									$this->_result = '<br />><span style="color: chartreuse;"> le fichier <u>'.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php'.'</u> a bien &#233;t&#233; r&#233;nomm&#233; en <u>'.MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php'.'</u></span>';
								}
								
								$this->_domXml = new DomDocument('1.0', CHARSET);
								
								if($this->_domXml->load(ROUTE)){		
									$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
									$sentences = $this->_nodeXml->getElementsByTagName('route');
						
									foreach($sentences as $sentence){
										if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2]){
											$id = $sentence->getAttribute("id");
											$url = $sentence->getAttribute("url");
											$action = $sentence->getAttribute("action");
											$vars = $sentence->getAttribute("vars");
											$this->_nodeXml->removeChild($sentence);
											
											$this->_markupXml = $this->_domXml->createElement('route');
											$this->_markupXml->setAttribute("id", $id);

											if(isset($this->_commandExplode[4])){
												$this->_markupXml->setAttribute("url", $this->_commandExplode[4]);
											}
											else{
												$this->_markupXml->setAttribute("url", $url);
											}
											
											$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[3]);
											
											if(isset($this->_commandExplode[5])){
												if($this->_commandExplode[5] == 'empty'){
													$this->_markupXml->setAttribute("action", '');
												}
												else{
													$this->_markupXml->setAttribute("action", $this->_commandExplode[5]);
												}
											}
											else{
												$this->_markupXml->setAttribute("action", $action);
											}
											
											if(isset($this->_commandExplode[6])){
												if($this->_commandExplode[6] == 'empty'){
													$this->_markupXml->setAttribute("vars", '');
												}
												else{
													$this->_markupXml->setAttribute("vars", $this->_commandExplode[6]);
												}
											}
											else{
												$this->_markupXml->setAttribute("vars", $vars);
											}

											$this->_nodeXml->appendChild($this->_markupXml);
											$this->_domXml->save(ROUTE);
											
											
											$this->_dom2Xml = new DomDocument('1.0', CHARSET);
											if($this->_dom2Xml->load(FIREWALL)){
												$this->_node4Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
												$this->_node5Xml = $this->_node4Xml->getElementsByTagName('firewall')->item(0);
												$this->_node6Xml = $this->_node5Xml->getElementsByTagName('access')->item(0);
												
												$sentences2 = $this->_node6Xml->getElementsByTagName('url');
												
												foreach ($sentences2 as $sentence2){
													if ($sentence2->getAttribute("id") == $id){
														$connected = $sentence->getAttribute("connected");
														$access = $sentence->getAttribute("access");
														
														$this->_node6Xml->removeChild($sentence2);
														
														$this->_markup2Xml = $this->_dom2Xml->createElement('url');
														$this->_markup2Xml->setAttribute("id", $id);
														
														if(isset($this->_commandExplode[7])){
															$this->_markup2Xml->setAttribute("connected", $this->_commandExplode[7]);
														}
														else{
															$this->_markup2Xml->setAttribute("connected", $connected);
														}
														
														if(isset($this->_commandExplode[8])){
															$this->_markup2Xml->setAttribute("access", $this->_commandExplode[8]);
														}
														else{
															$this->_markup2Xml->setAttribute("access", '*');
														}
														
														$this->_node6Xml->appendChild($this->_markup2Xml);
														$this->_dom2Xml->save(FIREWALL);
													}
												}
											}
										}
									}
								}
								
								$data = file_get_contents(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php');
								$data = preg_replace('#class '.$this->_commandExplode[2].' extends applicationGc#isU',
													  'class '.$this->_commandExplode[3].' extends applicationGc', $data);
								file_put_contents(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php', $data);
								
								$data = file_get_contents(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php');
								$data = preg_replace('#class manager'.ucfirst($this->_commandExplode[2]).' extends modelGc#isU',
													  'class manager'.ucfirst($this->_commandExplode[3]).' extends modelGc', $data);
								file_put_contents(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php', $data);

								$this->_result = '<br />><span style="color: chartreuse;"> la rubrique <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; modifiée en <u>'.$this->_commandExplode[3].'</u> et ses options ont été modifiées</span>';
							}
							else{
								$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php';
								$this->_result = '<br />><span style="color: red;"> Une rubrique porte d&#233;jà le même nom</span>';
							}
						}
						else{
							$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
							$this->_result = '<br />><span style="color: red;"> Cette rubrique n\'existe pas</span>';
						}
					}
					else{
						$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#add url (.*) (.*) (.*) (.*) (.*) (.*) (.*)#', $this->_command)){
					//add url id url rubrique action vars connected access
					$this->_domXml = new DomDocument('1.0', CHARSET);
					
					if($this->_domXml->load(ROUTE)){		
						$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
						$sentences = $this->_nodeXml->getElementsByTagName('route');
						
						$exist = false;
						
						foreach($sentences as $sentence){
							if ($sentence->getAttribute("rubrique") == $this->_commandExplode[2] || $sentence->getAttribute("id") == $this->_commandExplode[3]){
								$exist = true;
							}
						}
						
						if($exist == false){
							$this->_markupXml = $this->_domXml->createElement('route');
							$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
							$this->_markupXml->setAttribute("url", '/'.$this->_commandExplode[3]);
							$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[4]);
							$this->_markupXml->setAttribute("action", $this->_commandExplode[5]);
							if($this->_commandExplode[6] != 'empty'){
								$this->_markupXml->setAttribute("vars", $this->_commandExplode[6]);
							}
							else{
								$this->_markupXml->setAttribute("vars", '');	
							}
							$this->_nodeXml->appendChild($this->_markupXml);
							$this->_domXml->save(ROUTE);

							$this->_domXml = new DomDocument('1.0', CHARSET);
				
							if($this->_domXml->load(FIREWALL)){
								$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
								$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
								$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
								
								$sentences = $this->_node3Xml->getElementsByTagName('url');
								
								$rubrique = false;
								
								foreach($sentences as $sentence){
									if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
										$rubrique = true;
									}
								}
								
								if($rubrique == false){
									$this->_markupXml = $this->_domXml->createElement('url');
									$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
									$this->_markupXml->setAttribute("connected", $this->_commandExplode[7]);
									$this->_markupXml->setAttribute("access", $this->_commandExplode[8]);
									
									$this->_node3Xml->appendChild($this->_markupXml);
									$this->_domXml->save(FIREWALL);
								}
							}

							$this->_result = '<br />><span style="color: chartreuse;"> l\'url <u>'.$this->_commandExplode[3].'</u> d\'id <u>'.$this->_commandExplode[2].'</u> a bien été ajoutée au routeur et au parefeu</span>';
						}
						else{
							$this->_result = '<br />><span style="color: red;"> Cette url ou cet id est déjà utilisé</span>';
						}
					}
				}
				elseif(preg_match('#set url (.*) (.*) (.*) (.*) (.*) (.*) (.*)#', $this->_command)){
					//set url id url rubrique action vars connected access
					$this->_domXml = new DomDocument('1.0', CHARSET);
					
					$route = false;

					if($this->_domXml->load(ROUTE)){	
						$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
						$sentences = $this->_nodeXml->getElementsByTagName('route');
				
						foreach($sentences as $sentence){
							if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
								$this->_nodeXml->removeChild($sentence); 

								$this->_markupXml = $this->_domXml->createElement('route');
								$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
								$this->_markupXml->setAttribute("url", '/'.$this->_commandExplode[3]);
								$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[4]);
								$this->_markupXml->setAttribute("action", $this->_commandExplode[5]);
								$this->_markupXml->setAttribute("vars", $this->_commandExplode[6]);
								$this->_nodeXml->appendChild($this->_markupXml);

								$route = true;
								$this->_domXml->save(ROUTE);

								$this->_domXml = new DomDocument('1.0', CHARSET);
				
								if($this->_domXml->load(FIREWALL)){
									$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
									$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
									$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
									
									$sentences = $this->_node3Xml->getElementsByTagName('url');
									
									foreach($sentences as $sentence){
										if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
											$this->_node3Xml->removeChild($sentence);

											$this->_markupXml = $this->_domXml->createElement('url');
											$this->_markupXml->setAttribute("id", $this->_commandExplode[2]);
											$this->_markupXml->setAttribute("connected", $this->_commandExplode[7]);
											$this->_markupXml->setAttribute("access", $this->_commandExplode[8]);
											
											$this->_node3Xml->appendChild($this->_markupXml);
											$this->_domXml->save(FIREWALL);
										}
									}
								}
							}
						}

						if($route == true){
							$this->_result = '<br />><span style="color: chartreuse"> L\'url d\'id <u>'.$this->_commandExplode[2].'</u> a bien été modifiée</span>';
						}
						else{
							$this->_result = '<br />><span style="color: red"> Cette url n\'existe pas</span>';
						}
					}
					else{
						$this->_result = '<br />><span style="color: red"> Le fichier de route semble avoir un problème</span>';
					}
				}
				elseif(preg_match('#delete url (.*)#', $this->_command)){
					$this->_domXml = new DomDocument('1.0', CHARSET);
						
					if($this->_domXml->load(ROUTE)){			
						$this->_nodeXml = $this->_domXml->getElementsByTagName('routes')->item(0);
						$sentences = $this->_nodeXml->getElementsByTagName('route');
						
						$route = false;

						foreach($sentences as $sentence){
							if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
								$this->_nodeXml->removeChild($sentence); 
								$route = true;
							}
						}
						$this->_domXml->save(ROUTE);

						$this->_domXml = new DomDocument('1.0', CHARSET);

						if($this->_domXml->load(FIREWALL)){
							$this->_nodeXml = $this->_domXml->getElementsByTagName('security')->item(0);
							$this->_node2Xml = $this->_nodeXml->getElementsByTagName('firewall')->item(0);
							$this->_node3Xml = $this->_node2Xml->getElementsByTagName('access')->item(0);
								
							$sentences = $this->_node3Xml->getElementsByTagName('url');
								
							foreach($sentences as $sentence){
								if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
									$this->_node3Xml->removeChild($sentence);
									$this->_domXml->save(FIREWALL);
								}
							}
						}

						if($route == true){
							$this->_result = '<br />><span style="color: chartreuse;"> L\'url d\'id <u>'.$this->_commandExplode[2].'</u> a bien été supprimée du fichier de route et du firewall</span>';
						}
						else{
							$this->_result = '<br />><span style="color: red;"> Cette url n\'existe pas</span>';
						}
					}
					else{
						$this->_result = '<br />><span style="color: red"> Le fichier de route semble avoir un problème</span>';
					}
				}
				elseif(preg_match('#list rubrique#', $this->_command)){
					if($this->_dossier = opendir(RUBRIQUE_PATH)){
						$this->_stream .= '<br />>####################### RUBRIQUE';
						while(false !== ($this->_fichier = readdir($this->_dossier))){
							if(is_file(RUBRIQUE_PATH.$this->_fichier) && $this->_fichier!='.htaccess'){
								$this->_stream .= '<br />> '.RUBRIQUE_PATH.$this->_fichier.'';
							}
						}
					}
					if($this->_dossier = opendir(MODEL_PATH)){
						$this->_stream .= '<br />>####################### RUBRIQUE';
						while(false !== ($this->_fichier = readdir($this->_dossier))){
							if(is_file(MODEL_PATH.$this->_fichier) && $this->_fichier!='.htaccess'){
								$this->_stream .= '<br />> '.MODEL_PATH.$this->_fichier.'';
							}
						}
					}

					$this->_result = '<br />><span style="color: chartreuse;"> fichiers de rubrique list&#233;s</span>';
				}
				elseif(preg_match('#list included#', $this->_command)){				
					foreach(get_included_files() as $val){
						$this->_stream .= '<br />> '.$val;
					}
					$this->_result = '<br />><span style="color: chartreuse;"> fichiers inclus list&#233;s</span>';
				}
				elseif(preg_match('#clear cache#', $this->_command)){
					if($this->_dossier = opendir(CACHE_PATH)){
						while(false !== ($this->_fichier = readdir($this->_dossier))){
							if(is_file(CACHE_PATH.$this->_fichier) && $this->_fichier!='.htaccess'){
								unlink(CACHE_PATH.$this->_fichier);
								$this->_stream .= '<br />> '.CACHE_PATH.$this->_fichier.'';
							}
						}
					}
					$this->_result = '<br />><span style="color: chartreuse;"> le cache a bien &#233;t&#233; vid&#233;</span>';
				}
				elseif(preg_match('#clear log#', $this->_command)){
					if($this->_dossier = opendir(LOG_PATH)){
						while(false !== ($this->_fichier = readdir($this->_dossier))){
							if(is_file(LOG_PATH.$this->_fichier)){
								unlink(LOG_PATH.$this->_fichier);
								$this->_stream .= '<br />> '.LOG_PATH.$this->_fichier.LOG_EXT;
							}
						}
					}
					$this->_result = '<br />><span style="color: chartreuse;"> le log a bien &#233;t&#233; vid&#233;</span>';
				}
				elseif(preg_match('#help#', $this->_command)){
					$this->_stream .= '<br />> add rubrique nom (url[lien] action[nom|empty] vars[getvar,getvar|empty] connect[true|false|*] access[role1,role2|*]) facultatif';
					$this->_stream .= '<br />> set rubrique nom nouveaunom (url[lien] action[nom|empty] vars[getvar,getvar|empty] connect[true|false|*] access[role1,role2|*]) facultatif';
					$this->_stream .= '<br />> delete rubrique nom';
					$this->_stream .= '<br />> add url id url rubrique action vars[vars,vars|empty] connected[true|false|*] access[ROLE1,ROLE2|*]';
					$this->_stream .= '<br />> set url id url rubrique action vars[vars,vars|empty] connected[true|false|*] access[ROLE1,ROLE2|*]';
					$this->_stream .= '<br />> delete url id';
					$this->_stream .= '<br />> add template nom';
					$this->_stream .= '<br />> set template nom nouveaunom';
					$this->_stream .= '<br />> delete template nom';
					$this->_stream .= '<br />> add helper nom (enabled[true|false] include[*|no[rubrique,rubrique]|yes[rubrique,rubrique]])facultatif';
					$this->_stream .= '<br />> set class nom enabled[true|false] include[*|no[rubrique,rubrique]|yes[rubrique,rubrique]]';
					$this->_stream .= '<br />> delete helper nom';
					$this->_stream .= '<br />> add plugin type[helper/lib] name access[acces depuis le dossier lib ou helper] enabled[true/false] include[*/no[rubrique,rubrique]/yes[rubrique,rubrique]';
					$this->_stream .= '<br />> set plugin type[helper/lib] name access[acces depuis le dossier lib ou helper] enabled[true/false] include[*/no[rubrique,rubrique]/yes[rubrique,rubrique]';
					$this->_stream .= '<br />> delete plugin name';
					$this->_stream .= '<br />> list template';
					$this->_stream .= '<br />> list included';
					$this->_stream .= '<br />> list rubrique';
					$this->_stream .= '<br />> list cache';
					$this->_stream .= '<br />> clear cache';
					$this->_stream .= '<br />> clear log';
					$this->_stream .= '<br />> clear';
					$this->_stream .= '<br />> update';
					$this->_stream .= '<br />> update updater';
					$this->_stream .= '<br />> install rubrique folder';
					$this->_stream .= '<br />> uninstall rubrique folder';
					$this->_stream .= '<br />> recover config';
					$this->_stream .= '<br />> see log nomdulogsansextansion';
					$this->_stream .= '<br />> see route';
					$this->_stream .= '<br />> see plugin';
					$this->_stream .= '<br />> see app';
					$this->_stream .= '<br />> see firewall';
					$this->_stream .= '<br />> see antispam';
					$this->_stream .= '<br />> changepassword nouveaumdp';
					$this->_stream .= '<br />> connect mdp';
					$this->_stream .= '<br />> disconnect';
					$this->_stream .= '<br />> help';
					$this->_result  = '<br />><span style="color: chartreuse;"> liste des commandes</span>';
				}
				elseif(preg_match('#update updater#', $this->_command)){
					$this->_stream .= $this->_updater();
					$this->_result = '<br />><span style="color: chartreuse;"> updater &#226; jour</span><meta http-equiv="refresh" content="1; URL=#">';
				}
				elseif(preg_match('#update#', $this->_command)){
					$this->_stream .= $this->_update();
					$this->_result = '<br />><span style="color: chartreuse;"> framework &#226; jour</span>';
				}
				elseif(preg_match('#disconnect#', $this->_command) && $this->_mdp==false){
					$this->_result = '<br />><span style="color: chartreuse;"> Vous avez &#233;t&#233; d&#233;connect&#233;</span>';
					$_SESSION['GC_terminalMdp'] = 0;
				}
				elseif(preg_match('#changepassword (.+)#', $this->_command)){
					$sauvegarde = file_get_contents('web.config.php');
					$sauvegarde = preg_replace("`define\('TERMINAL_MDP', '(.+)'\)`isU", 'define(\'TERMINAL_MDP\', \''.$this->_commandExplode[1].'\')',  $sauvegarde);
					file_put_contents('web.config.php', $sauvegarde);
					$this->_result = '<br />><span style="color: chartreuse;"> Le mot de passe a bien &#233;t&#233; modifi&#233;'.$sauvegarde.'</span>';
				}
				elseif(preg_match('#recover config#', $this->_command)){
					foreach($this->_configIfNoExist as $cle => $file){
						if(!is_file($file)){
							$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.$file);
							$fp = fopen($file, "w");
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
							$contenu .= '<br />> <span style="color: chartreuse;">'.$file.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.$file.'</span>';
						}
					}
					$this->_result = '<br />><span style="color: chartreuse;"> Les fichiers de configurations perdus ont bien été remplacés'.$sauvegarde.'</span>';
				}
				elseif(preg_match('#see (.+)#', $this->_command)){
					switch($this->_commandExplode[1]){
						case 'log':
							if(is_file(LOG_PATH.$this->_commandExplode[2].LOG_EXT)){
								$sauvegarde = file_get_contents(LOG_PATH.$this->_commandExplode[2].LOG_EXT);
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=10){
										$search = array ();
										$replace = array ();
										$valeur = preg_replace($search, $replace, $valeur);
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.($valeur).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.($valeur).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de log <strong>'.LOG_PATH.$this->_commandExplode[2].LOG_EXT.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de log <strong>'.LOG_PATH.$this->_commandExplode[2].LOG_EXT.'</strong> n\'existe pas</span>';
							}
						break;
						
						case 'route':
							if(is_file(ROUTE)){
								$sauvegarde = file_get_contents(ROUTE);
								echo $sauvegarde;
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=5){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de route <strong>'.ROUTE.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de route <strong>'.ROUTE.'</strong> n\'existe pas ce qui est étonnant à moins que vous n\'ayez désactivé le route via le framework</span>';
							}
						break;
						
						case 'plugin':
							if(is_file(PLUGIN)){
								$sauvegarde = file_get_contents(PLUGIN);
								echo $sauvegarde;
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=5){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de plugins <strong>'.PLUGIN.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de plugins <strong>'.PLUGIN.'</strong> n\'existe pas. Vous devriez vite le récupérer</span>';
							}
						break;
						
						case 'app':
							if(is_file(APPCONFIG)){
								$sauvegarde = file_get_contents(APPCONFIG);
								echo $sauvegarde;
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=5){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de config <strong>'.APPCONFIG.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de config <strong>'.APPCONFIG.'</strong> n\'existe pas. Vous devriez vite le récupérer</span>';
							}
						break;
						
						case 'firewall':
							if(is_file(FIREWALL)){
								$sauvegarde = file_get_contents(FIREWALL);
								echo $sauvegarde;
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=5){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de sécurité <strong>'.FIREWALL.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de sécurité <strong>'.FIREWALL.'</strong> n\'existe pas. Vous devriez vite le récupérer si vous voulez disposer d\'un pare feu</span>';
							}
						break;

						case 'antispam':
							if(is_file(ASPAM)){
								$sauvegarde = file_get_contents(ASPAM);
								echo $sauvegarde;
								$sauvegardes = explode("\n", $sauvegarde);
								
								$i = 0;
								
								foreach($sauvegardes as $valeur){
									if(strlen($valeur)>=5){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
											$i=0;
										}	
									}							
								}
								
								$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de configuration de l\'antispam <strong>'.ASPAM.'</strong> a bien &#233;t&#233; affich&#233;</span>';
							}
							else{
								$this->_result = '<br />><span style="color: red;"> Le fichier de configuration de l\'antispam <strong>'.ASPAM.'</strong> n\'existe pas. Vous devriez vite le récupérer si vous voulez disposer d\'un système d\'anti spam</span>';
							}
						break;
					}
				}
				else{
				
				}
			}
			else{
				//$this->_stream .= '><span style="color: red;"> Erreur de connexion</span>';
				$this->_result = '<br />><span style="color: red;"> Erreur de connexion. Vous devez vous connecter gr&#226;ce au  mot de passe du fichier de config</span>';
			}
			
			if($this->_stream!="")
				return '>'.$this->_command.' <br /><span style="display: inline-block; margin-left: 25px; margin-top: -14px">'.$this->_stream.'</span> '.$this->_result;
			else
				return '>'.$this->_command.' '.$this->_result;
		}

		protected function _updater(){
			if(function_exists('curl_init')){
				$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.CLASS_TERMINAL);
				$fp = fopen(CLASS_TERMINAL, "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
				return $contenu .= '<br />> <span style="color: chartreuse;">'.CLASS_TERMINAL.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.CLASS_TERMINAL.'</span>';
			}
			else{
				return $contenu .= '<br />> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
			}
		}
		
		protected function _mkmap($dir){
			$dossier = opendir ($dir);
		   
			while ($fichier = readdir ($dossier)){
				if ($fichier != "." && $fichier != ".."){
					if(filetype($dir.$fichier) == 'dir'){
						$this->_mkmap($dir.$fichier.'/');
					}
					elseif($fichier!='.htaccess'){
						$this->_stream .= '<br />> '.$dir.$fichier.'';
					}					
				}       
			}
			closedir ($dossier);    
		}

		protected function _update(){
			if(function_exists('curl_init')){
				$contenu = "";
				$sauvegarde ="";
				$sauvegarde2 ="";
				$suppr = "";
				$suppr2 = "";

				$sauvegarde = file_get_contents('web.config.php');
				$sauvegarde = preg_replace('`(.*)parametres de connexion a la base de donnees(.*)`isU', '$2', $sauvegarde);
				$sauvegarde2 = file_get_contents('index.php');
				$sauvegarde2 = preg_replace('`(.*)articulation du site web(.*)`isU', '$2', $sauvegarde2);

				foreach($this->_updateFile as $file){				
					$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.$file);
					$fp = fopen($file, "w");
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
					$contenu .= '<br />> <span style="color: chartreuse;">'.$file.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.$file.'</span>';
				}

				$suppr = file_get_contents('web.config.php');
				$suppr = preg_replace('`(.*)(parametres de connexion a la base de donnees)(.*)`is', '$1parametres de connexion a la base de donnees', $suppr);
				
				if($suppr!="" && $sauvegarde!=""){
					file_put_contents('web.config.php', $suppr);
					file_put_contents('web.config.php', $sauvegarde, FILE_APPEND);
				}

				$suppr2 = file_get_contents('index.php');
				$suppr2 = preg_replace('`(.*)(articulation du site web)(.*)`is', '$1articulation du site web', $suppr2);
				
				if($suppr2!="" && $sauvegarde2!=""){
					file_put_contents('index.php', $suppr2);
					file_put_contents('index.php', $sauvegarde2, FILE_APPEND);
				}

				foreach($this->_configIfNoExist as $cle => $file){
					if(!is_file($file)){
						$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.$file);
						$fp = fopen($file, "w");
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);
						$contenu .= '<br />> <span style="color: chartreuse;">'.$file.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.$file.'</span>';
					}
				}

				$this->_configIfNoExist = $arrayName = array(
				ROUTE, MODOGCCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM
			);

				return $contenu;
			}	
			else{
				return $contenu .= '<br />> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
			}
		}
		
		public function correctName($message){
			$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
			$replace = array ('e','a','i','u','o','c','_','');
			
			$message =  preg_replace('#Ã©#isU', 'é', $message);
			$message =  preg_replace('#Ã¨#isU', 'è', $message);
			$message =  preg_replace('#Ã§#isU', 'ç', $message);
			$message =  preg_replace('#Ã#isU', 'à', $message);
			$message =  preg_replace('#Ã¹#isU', 'ù', $message);
			$message =  preg_replace('#Ã»#isU', 'û', $message);
			$message =  preg_replace('#Ã¼#isU', 'ü', $message);
			$message =  preg_replace('#Ã´#isU', 'ô', $message);
			$message =  preg_replace('#Ã¶#isU', 'ö', $message);
			$message =  preg_replace('#Ã®#isU', 'î', $message);
			$message =  preg_replace('#Ã¯#isU', 'ï', $message);
			$message =  preg_replace('#Â#isU', '', $message);
			$message =  preg_replace('#Å“#isU', 'œ', $message);
			$message =  preg_replace('#Å’#isU', 'Œ', $message);
			$message =  preg_replace('#à #isU', 'à', $message);
			$message =  preg_replace('#à¢#isU', 'â', $message);
			$message =  preg_replace('#à‡#isU', 'Ç', $message);
			$message =  preg_replace('#â€™#isU', '\'', $message);
			$message =  preg_replace('#â‚¬#isU', '€', $message);
			$message =  preg_replace('#à«#isU','ë', $message);
			$message =  preg_replace('#â€¦#isU','...', $message);
			
			$message =  preg_replace('#à¹#isU','ù', $message);
			$message =  preg_replace('#à»#isU','û', $message);
			$message =  preg_replace('#à¼#isU','ü', $message);
			$message =  preg_replace('#à´#isU','ô', $message);
			$message =  preg_replace('#à¶#isU','ö', $message);
			$message =  preg_replace('#à®#isU','î', $message);
			$message =  preg_replace('#à¯#isU','ï', $message);
			$message =  preg_replace('#àª#isU','ê', $message);
			
			$message =  preg_replace('#â„¢#isU','™', $message);
			$message =  preg_replace('#à¡#isU','á', $message);
			$message =  preg_replace('#à‰#isU','É', $message);
			$message =  preg_replace('#àŠ#isU','Ê', $message);
			$message =  preg_replace('#àˆ#isU','È', $message);
			$message =  preg_replace('#à‹#isU','Ë', $message);
			$message =  preg_replace('#à€#isU','À', $message);
			$message =  preg_replace('#à„€#isU','Ä', $message);
			$message =  trim($message);
			
			return preg_replace($search, $replace, $message);
		}

		public  function __destruct(){
		}
	}