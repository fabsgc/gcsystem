<?php
	/**
	 * @file : terminalGc.class.php
	 * @author : fab@c++
	 * @description : class g&eacute;rant les fichiers compress&#233;s
	 * @version : 2.0 bêta
	*/

	class terminalGc{
		use errorGc, langInstance, domGc;                  //trait
		
		protected $_command                       ; //contenu à traiter
		protected $_stream                        ; //contenu à afficher
		protected $_commandExplode                ; //contenu à traiter
		protected $_result                        = '/ <span style="color: red;">commande non reconnu. Tapez <strong>help</strong> pour avoir la liste des commandes valides</span>'; //resultat du traitement
		protected $_dossier                       ; //dossier
		protected $_fichier                       ; //fichier
		protected $_forbidden                     ; //fichier interdit
		protected $_updateFile                    ; //fichier interdit
		protected $_updateDir                     ; //fichier interdit

		public  function __construct($command, $lang = 'fr'){
			$this->_lang=$lang;
			$this->_createLangInstance();
			
			$this->_commandExplode = explode(' ', trim($command));
			$this->_command = '<span style="color: gold;"> '.$command.'</span>';
			$this->_forbidden = array(
				MODEL_PATH.'terminal'.MODEL_EXT.'.php', MODEL_PATH.'index'.MODEL_EXT.'.php', FUNCTION_GENERIQUE, RUBRIQUE_PATH.'index'.RUBRIQUE_EXT.'.php', RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php',
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCrubrique'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_APPLICATION, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE, CLASS_LOG, CLASS_CACHE, CLASS_CAPTCHA, CLASS_EXCEPTION, CLASS_TEMPLATE,CLASS_LANG, CLASS_APPDEVGC, CLASS_TERMINAL,
			);
			$this->_updateFile = array(
				FUNCTION_GENERIQUE, RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php',
				'web.config.php',
				'index.php',
				LIB_PATH.'FormsGC/formsGC.class.php', LIB_PATH.'FormsGC/formsGCValidator.class.php',
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCrubrique'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
				TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_APPLICATION, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE,CLASS_LOG,CLASS_CACHE, CLASS_EXCEPTION, CLASS_TEMPLATE, CLASS_LANG, CLASS_APPDEVGC, CLASS_TERMINAL,
				LANG_PATH.'nl'.LANG_EXT, LANG_PATH.'fr'.LANG_EXT, LANG_PATH.'en'.LANG_EXT, 
			); // liste des fichiers systèmes à updater
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
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
					if(!in_array(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
						$monfichier = fopen(RUBRIQUE_PATH.$this->_commandExplode[2].RUBRIQUE_EXT.'.php', 'a');						
						$t= new templateGC(GCSYSTEM_PATH.'GCrubrique', 'GCrubrique', '0');
						$t->assign(array(
							'rubrique'=> $this->_commandExplode[2]
						));
						$t->setShow(FALSE);
						fputs($monfichier, $t->show());
						fclose($monfichier);
						
						$monfichier = fopen(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', 'a');						
						$t= new templateGC(GCSYSTEM_PATH.'GCmodel', 'GCmodel', '0');
						$t->assign(array(
							'rubrique'=> ucfirst($this->_commandExplode[2])
						));
						$t->setShow(FALSE);
						fputs($monfichier, $t->show());
						fclose($monfichier);
						
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
								$this->_markupXml->setAttribute("id", uniqid());
								$this->_markupXml->setAttribute("url", "");
								$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[2]);
								$this->_markupXml->setAttribute("action", "");
								$this->_markupXml->setAttribute("vars", "");
							
								$this->_nodeXml->appendChild($this->_markupXml);
								$this->_domXml->save(ROUTE);
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
						
						$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
						
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
					$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
					
					if($this->_domXml->load(PLUGIN)){							
						$this->_nodeXml = $this->_domXml->getElementsByTagName('plugins')->item(0);
						$sentences = $this->_nodeXml->getElementsByTagName('plugin');
				
						foreach($sentences as $sentence){
							if ($sentence->getAttribute("name") == $this->_commandExplode[2]){
								$this->_nodeXml->removeChild($sentence);    
							}
						}
						$this->_domXml->save(PLUGIN);
					}

					$this->_result = '<br />><span style="color: chartreuse;"> le plugin <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; supprim&#233;</span>';
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
				elseif(preg_match('#add class (.+)#', $this->_command)){
					if(!in_array(CLASS_PATH.$this->_commandExplode[2].'.class.php', $this->_forbidden)){
						$monfichier = fopen(CLASS_PATH.$this->_commandExplode[2].'.class.php', 'a');
						fclose($monfichier);
						$this->_stream .= '<br />> '.CLASS_PATH.$this->_commandExplode[2].'.class.php';
						$this->_result = '<br />><span style="color: chartreuse;"> le fichier class <u>'.CLASS_PATH.$this->_commandExplode[2].'.class.php'.'</u> a bien &#233;t&#233; cr&#233;&#233;</span>';
					}
					else{
						$this->_stream .= '<br />> '.CLASS_PATH.$this->_commandExplode[2].'.class.php';
						$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
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
				elseif(preg_match('#rename template (.+) (.+)#', $this->_command)){
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
				elseif(preg_match('#rename rubrique (.+) (.+)#', $this->_command)){
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
								
								$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
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
											$this->_markupXml->setAttribute("url", $url);
											$this->_markupXml->setAttribute("rubrique", $this->_commandExplode[3]);
											$this->_markupXml->setAttribute("action", $action);
											$this->_markupXml->setAttribute("vars", $vars);
										
											$this->_nodeXml->appendChild($this->_markupXml);
										}
									}
									$this->_domXml->save(ROUTE);
									
									$data = file_get_contents(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php');
									
									$data = preg_replace('#class '.$this->_commandExplode[2].' extends applicationGc#isU',
														  'class '.$this->_commandExplode[3].' extends applicationGc', $data);
									
									file_put_contents(RUBRIQUE_PATH.$this->_commandExplode[3].RUBRIQUE_EXT.'.php', $data);
								}

								$this->_result = '<br />><span style="color: chartreuse;"> la rubrique <u>'.$this->_commandExplode[2].'</u> a bien &#233;t&#233; r&#233;nomm&#233;e en <u>'.$this->_commandExplode[3].'</u></span>';
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
					$this->_stream .= '<br />> add rubrique nom';
					$this->_stream .= '<br />> delete rubrique nom';
					$this->_stream .= '<br />> rename rubrique nom nouveaunom';
					$this->_stream .= '<br />> add template nom';
					$this->_stream .= '<br />> delete template nom';
					$this->_stream .= '<br />> rename template nom nouveaunom';
					$this->_stream .= '<br />> add class nom';
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
					$this->_stream .= '<br />> see log nomdulogsansextansion';
					$this->_stream .= '<br />> changepassword nouveaumdp';
					$this->_stream .= '<br />> connect mdp';
					$this->_stream .= '<br />> disconnect';
					$this->_stream .= '<br />> help';
					$this->_result = '<br />><span style="color: chartreuse;"> liste des commandes</span>';
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
				elseif(preg_match('#see log (.+)#', $this->_command)){
					if(is_file(LOG_PATH.$this->_commandExplode[2].LOG_EXT)){
						$sauvegarde = file_get_contents(LOG_PATH.$this->_commandExplode[2].LOG_EXT);
						$sauvegardes = explode("\n", $sauvegarde);
						
						$i = 0;
						
						foreach($sauvegardes as $valeur){
							if(strlen($valeur)>=10){
								$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i', '@°@');
								$replace = array ('e','a','i','u','o','c', ' ');
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
				}
				else{
				
				}
			}
			else{
				$this->_stream .= '<span style="color: red;"> / erreur de connexion</span>';
				$this->_result = '<br />><span style="color: red;"> Vous devez vous connecter gr&#226;ce au  mot de passe du fichier de config</span>';
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

				return $contenu;
			}	
			else{
				return $contenu .= '<br />> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
			}
		}
	}