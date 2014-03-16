<?php
	/**
	 * @file : terminal.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers compressés
	 * @version : 2.2 bêta
	*/

	namespace system{
		class terminal{
			use error, langInstance, general;

			protected $_command                       ; //contenu à traiter
			protected $_stream                        ; //contenu à afficher
			protected $_commandExplode                ; //contenu à traiter
			protected $_result                        = '/ <span style="color: red;">commande non reconnue. Tapez <strong>help</strong> pour avoir la liste des commandes valides</span>'; //resultat du traitement
			protected $_dossier                       ; //dossier
			protected $_fichier                       ; //fichier
			protected $_forbidden                     ; //fichiers interdits
			protected $_updateFile                    ; //fichiers pour la mise à jour
			protected $_bdd                           ; //connexion sql

			public  function __construct($command, $bdd, $lang = 'fr'){
				$this->_lang=$lang;
				$this->_bdd=$bdd;
				$this->_createLangInstance();
				
				$this->_commandExplode = explode(' ', trim($command));
				$this->_command = '<span style="color: gold;"> '.$command.'</span>';
				$this->_forbidden = array(
					MODEL_PATH.'terminal'.MODEL_EXT.'.php', MODEL_PATH.'index'.MODEL_EXT.'.php', FUNCTION_GENERIQUE, CONTROLLER_PATH.'index'.CONTROLLER_EXT.'.php', CONTROLLER_PATH.'terminal'.CONTROLLER_EXT.'.php',
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCcontroller'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT,
					TEMPLATE_PATH.ERRORDOCUMENT_PATH.'httpError'.TEMPLATE_EXT,
	                CLASS_CRON, CLASS_INSTALL, CLASS_ANTISPAM, CLASS_FIREWALL, CLASS_CONTROLLER, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_ENGINE, CLASS_LOG, CLASS_CACHE, CLASS_TEMPLATE,CLASS_LANG, CLASS_appDev, CLASS_TERMINAL, CLASS_ERROR_PERSO,
					LANG_PATH.'nl'.LANG_EXT, LANG_PATH.'fr'.LANG_EXT, LANG_PATH.'en'.LANG_EXT,
					CLASS_PATH.CLASS_HELPER_PATH.'ftp.class.php', CLASS_PATH.CLASS_HELPER_PATH.'bbcode.class.php', CLASS_PATH.CLASS_HELPER_PATH.'captcha.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'date.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dir.class.php', CLASS_PATH.CLASS_HELPER_PATH.'download.class.php', CLASS_PATH.CLASS_HELPER_PATH.'feed.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'file.class.php', CLASS_PATH.CLASS_HELPER_PATH.'mail.class.php', CLASS_PATH.CLASS_HELPER_PATH.'modo.class.php', CLASS_PATH.CLASS_HELPER_PATH.'pagination.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'picture.class.php', CLASS_PATH.CLASS_HELPER_PATH.'sql.class.php', CLASS_PATH.CLASS_HELPER_PATH.'text.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'upload.class.php', CLASS_PATH.CLASS_HELPER_PATH.'zip.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'antispam.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'appDev.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'controller.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'cache.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'config.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'exception.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'firewall.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'engine.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'general.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'install.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'lang.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'log.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'model.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'pluginGc.class.php',
					CLASS_EXCEPTION, CLASS_PATH.CLASS_SYSTEM_PATH.'backup.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'router.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'template.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'terminal.class.php',
					ROUTE, MODOCONFIG, APPCONFIG, HELPER, FIREWALL, ASPAM, ADDON, CRON, ERRORPERSO
				);

				$this->_updateFile = array(
					CONTROLLER_PATH.'terminal'.CONTROLLER_EXT.'.php',
					'web.config.php',
					'index.php',
					LIB_PATH.'FormsGC/formsGC.class.php', LIB_PATH.'FormsGC/formsGCValidator.class.php',
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCerror'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCerrorperso'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GClang'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCmaintenance'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCmodel'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCnewcontroller'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCpagination'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCcontroller'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCspam'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystem'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCsystemDev'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCterminal'.TEMPLATE_EXT, TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,
					TEMPLATE_PATH.GCSYSTEM_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,
	                TEMPLATE_PATH.ERRORDOCUMENT_PATH.'httpError'.TEMPLATE_EXT,
					CLASS_EXCEPTION, CLASS_BACKUP, CLASS_CRON, CLASS_INSTALL, CLASS_ANTISPAM, CLASS_FIREWALL, CLASS_CONTROLLER, CLASS_ROUTER, CLASS_AUTOLOAD, CLASS_GENERAL_INTERFACE,CLASS_ENGINE,CLASS_LOG,CLASS_CACHE, CLASS_TEMPLATE, CLASS_LANG, CLASS_appDev, CLASS_TERMINAL, CLASS_ERROR_PERSO,
					LANG_PATH.'nl'.LANG_EXT, LANG_PATH.'fr'.LANG_EXT, LANG_PATH.'en'.LANG_EXT,
					CLASS_PATH.CLASS_HELPER_PATH.'ftp.class.php', CLASS_PATH.CLASS_HELPER_PATH.'bbcode.class.php', CLASS_PATH.CLASS_HELPER_PATH.'captcha.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'date.class.php', CLASS_PATH.CLASS_HELPER_PATH.'dir.class.php', CLASS_PATH.CLASS_HELPER_PATH.'download.class.php', CLASS_PATH.CLASS_HELPER_PATH.'feed.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'file.class.php', CLASS_PATH.CLASS_HELPER_PATH.'mail.class.php', CLASS_PATH.CLASS_HELPER_PATH.'modo.class.php', CLASS_PATH.CLASS_HELPER_PATH.'pagination.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'picture.class.php', CLASS_PATH.CLASS_HELPER_PATH.'sql.class.php', CLASS_PATH.CLASS_HELPER_PATH.'text.class.php',
					CLASS_PATH.CLASS_HELPER_PATH.'upload.class.php', CLASS_PATH.CLASS_HELPER_PATH.'zip.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'antispam.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'appDev.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'controller.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'cache.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'config.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'exception.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'firewall.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'engine.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'general.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'install.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'lang.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'log.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'model.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'pluginGc.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'backup.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'router.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'template.class.php', CLASS_PATH.CLASS_SYSTEM_PATH.'terminal.class.php',
					CLASS_PATH.CLASS_SYSTEM_PATH.'terminal.class.php'
				); // liste des fichiers systèmes à updater
			}

			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
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
					if(preg_match('#add controller (.+)#', $this->_command)){
						$this->_commandExplode[2] = html_entity_decode(htmlspecialchars_decode($this->_commandExplode[2]));
						$this->_commandExplode[2] = preg_replace('#\.#isU', '', $this->_commandExplode[2]);
						
						if(!in_array(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
							if(!file_exists(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
								$monfichier = fopen(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php', 'a');						
									$t= new template(GCSYSTEM_PATH.'GCcontroller', 'GCcontroller', '0');
									$t->assign(array(
										'controller'=> $this->_commandExplode[2]
									));
									$t->setShow(FALSE);
									fputs($monfichier, $t->show());
								fclose($monfichier);
							}
							
							if(!file_exists(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
								$monfichier = fopen(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', 'a');						
									$t= new template(GCSYSTEM_PATH.'GCmodel', 'GCmodel', '0');
									$t->assign(array(
										'controller'=> ucfirst($this->_commandExplode[2])
									));
									$t->setShow(FALSE);
									fputs($monfichier, $t->show());
								fclose($monfichier);
							}
							
							$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
							$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php';

							$this->_result = '<br />> <span style="color: chartreuse;">le contrôleur <u>'.$this->_commandExplode[2].'</u> a bien été créée</span>';
							
							$domXml = new \DomDocument('1.0', CHARSET);
							if($domXml->load(ROUTE)){						
								$nodeXml = $domXml->getElementsByTagName('routes')->item(0);
								$sentences = $nodeXml->getElementsByTagName('route');
					
								$controller = false;
								
								foreach($sentences as $sentence){
									if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
										$controller = true;
									}
								}
								
								if($controller == false){
									$markupXml = $domXml->createElement('route');
									$markupXml->setAttribute("id", $this->_commandExplode[2]);
									
									if(isset($this->_commandExplode[3])){
										$markupXml->setAttribute("url", "/".$this->_commandExplode[3].'/');
									}
									else{
										$markupXml->setAttribute("url", "/".$this->_commandExplode[2].'/');
									}
									
									$markupXml->setAttribute("controller", $this->_commandExplode[2]);
									
									if(isset($this->_commandExplode[4])){
										if($this->_commandExplode[4] == 'empty'){
											$markupXml->setAttribute("action", '');
										}
										else{
											$markupXml->setAttribute("action", $this->_commandExplode[4]);
										}
									}
									else{
										$markupXml->setAttribute("action", "");
									}
									
									if(isset($this->_commandExplode[5])){
										if($this->_commandExplode[5] == 'empty'){
											$markupXml->setAttribute("vars", '');
										}
										else{
											$markupXml->setAttribute("vars", $this->_commandExplode[5]);
										}
									}
									else{
										$markupXml->setAttribute("vars", '');
									}

									if(isset($this->_commandExplode[8])){
										if(!is_int($this->_commandExplode[8] == 'empty') || $this->_commandExplode[8] < 0){
											$markupXml->setAttribute("cache", '0');
										}
										else{
											$markupXml->setAttribute("cache", $this->_commandExplode[8]);
										}
									}
									else{
										$markupXml->setAttribute("cache", '0');
									}
								
									$nodeXml->appendChild($markupXml);
									$domXml->save(ROUTE);
								}
							}
							
							$domXml = new \DomDocument('1.0', CHARSET);
					
							if($domXml->load(FIREWALL)){
								$nodeXml = $domXml->getElementsByTagName('security')->item(0);
								$node2Xml = $nodeXml->getElementsByTagName('firewall')->item(0);
								$node3Xml = $node2Xml->getElementsByTagName('access')->item(0);
								
								$sentences = $node3Xml->getElementsByTagName('url');
								
								$controller = false;
								
								foreach($sentences as $sentence){
									if ($sentence->getAttribute("id") == $this->_commandExplode[2]){
										$controller = true;
									}
								}
								
								if($controller == false){
									$markupXml = $domXml->createElement('url');
									$markupXml->setAttribute("id", $this->_commandExplode[2]);
									
									if(isset($this->_commandExplode[6])){
										$markupXml->setAttribute("connected", $this->_commandExplode[6]);
									}
									else{
										$markupXml->setAttribute("connected", '*');
									}
									
									if(isset($this->_commandExplode[7])){
										$markupXml->setAttribute("access", $this->_commandExplode[7]);
									}
									else{
										$markupXml->setAttribute("access", '*');
									}
									
									$node3Xml->appendChild($markupXml);
									$domXml->save(FIREWALL);
								}
							}
						}
						else{
							$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
							$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					elseif(preg_match('#delete controller (.+)#', $this->_command)){
						if(!in_array(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
							if(file_exists(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && is_readable(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php')){
								unlink(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php');
								$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
							}
							
							if(file_exists(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php') && is_readable(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php')){
								unlink(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php');
								$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php';
							}
							
							$domXml = new \DomDocument('1.0', CHARSET);
							
							if($domXml->load(ROUTE)){							
								$nodeXml = $domXml->getElementsByTagName('routes')->item(0);
								$sentences = $nodeXml->getElementsByTagName('route');

								foreach($sentences as $sentence){
									if($sentence->getAttribute('controller') == $this->_commandExplode[2]){
										$this->_dom2Xml = new \DomDocument('1.0', CHARSET);

										if($this->_dom2Xml->load(FIREWALL)){
											$node2Xml = $this->_dom2Xml->getElementsByTagName('security')->item(0);
											$node2Xml = $node2Xml->getElementsByTagName('firewall')->item(0);
											$node2Xml = $node2Xml->getElementsByTagName('access')->item(0);
											$sentences2 = $node2Xml->getElementsByTagName('url');

											$this->_removeChild(FIREWALL, $this->_dom2Xml, $node2Xml, $sentences2, "id", $sentence->getAttribute('id'));
										}
									}
								}

								$this->_removeChild(ROUTE, $domXml, $nodeXml, $sentences, "controller", $this->_commandExplode[2]);
							}

							$this->_result = '<br />><span style="color: chartreuse;"> la controller <u>'.$this->_commandExplode[2].'</u> a bien été supprimée</span>';
						}
						else{
							$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
							$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					elseif(preg_match('#add template (.+)#', $this->_command)){
						if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
							//d'abord on créé les répertoires non existants
							$dirs = explode('/', TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT);
							array_pop($dirs);

							$dirCreated = '';

							foreach ($dirs as $key => $value) {
								if(!is_dir(TEMPLATE_PATH.$dirCreated.$value.'/')){
									mkdir(TEMPLATE_PATH.$dirCreated.$value.'/');

									$dirCreated .= $value.'/';
								}
							}

							//ensuite on créé le fichier
							$monfichier = fopen(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, 'a');
							fclose($monfichier);
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.$this->_commandExplode[2].'</u> a bien été créé</span>';
						}
						else{
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					elseif(preg_match('#list template#', $this->_command)){
						$this->_mkmap(TEMPLATE_PATH);
						$this->_result = '<br />><span style="color: chartreuse;"> fichiers de template listés</span>';
					}
					elseif(preg_match('#list cache#', $this->_command)){
						$this->_mkmap(CACHE_PATH);
						$this->_result = '<br />><span style="color: chartreuse;"> fichiers de cache listés</span>';
					}
					elseif(preg_match('#list backup#', $this->_command)){
						$backup = new backup();
						$files = $backup->listBackup(); //on récupère les chemins vers tous les fichiers

						$i = 0;

						if($files != false){
							foreach ($files as $key => $value) {
								$file = new file($value);
								$date = new date($this->_lang);

								if($file->getExist() == true){
									if($file->getFileExt() == 'zip'){
										$zip = new zip($value);
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;"><u>'.$file->getName().'</u>, dernière modification : <u>'.$date->getDate($file->getLastUpdate(), date::DATE_JMA_HMS_FR).'</u>, poids : <u>'.($zip->getFilesCompressedSize()/1000).' Ko</u></span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;"><u>'.$file->getName().'</u>, dernière modification : <u>'.$date->getDate($file->getLastUpdate(), date::DATE_JMA_HMS_FR).'</u>, poids : <u>'.($zip->getFilesCompressedSize()/1000).' Ko</u></span>';
											$i=0;
										}	
									}		
								}
							}
							$this->_result = '<br />><span style="color: chartreuse;"> backups listés</span>';
						}
						else{
							$this->_stream .= $backup->getError();
							$this->_result = '<br />><span style="color: chartreuse;"> les backups n\'ont pas pu être listés</span>';
						}
					}
					elseif(preg_match('#delete template (.+)#', $this->_command)){
						if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
							if(is_file(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT) && file_exists(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT) && is_readable(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT)){
								unlink(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT);
								$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
								$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.'</u> a bien été supprimé</span>';
							}
							else{
								$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
								$this->_result = '<br />><span style="color: red;"> Ce template n\'existe pas ou n\'est pas accessible</span>';
							}
						}
						else{
							$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT;
							$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					elseif(preg_match('#set template (.+) (.+)#', $this->_command)){
						if(!in_array(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, $this->_forbidden)){
							if(is_file(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT) && file_exists(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT) && is_readable(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT)){
								if(!file_exists(TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT)){
									rename(TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT, TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT);
									$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.' -> '.TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT;
									$this->_result = '<br />><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->_commandExplode[2].TEMPLATE_EXT.'</u> a bien été renommé en <u>'.TEMPLATE_PATH.$this->_commandExplode[3].'</u></span>';
								}
								else{
									$this->_stream .= '<br />> '.TEMPLATE_PATH.$this->_commandExplode[3].TEMPLATE_EXT;
									$this->_result = '<br />><span style="color: red;"> Un template porte déjà le même nom</span>';
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
					elseif(preg_match('#set controller (.+) (.+)#', $this->_command)){
						if(!in_array(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php', $this->_forbidden) && !in_array(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', $this->_forbidden)){
							if(is_file(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php')  && file_exists(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && is_readable(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') || is_file(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php') && file_exists(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && is_readable(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php')){
								if(!file_exists(CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php') || !file_exists(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php')){
									if(is_file(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php')  && file_exists(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && is_readable(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && !file_exists(CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php')){
										rename(MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php', MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php');
										$this->_stream .= '<br />> '.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php'.' -> '.MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php';
										
										if(is_file(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && file_exists(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && is_readable(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php') && !file_exists(CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php')){
											rename(CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php', CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php');
											$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php'.' -> '.CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php';
										}
										
										$this->_result = '<br />><span style="color: chartreuse;"> le fichier <u>'.MODEL_PATH.$this->_commandExplode[2].MODEL_EXT.'.php'.'</u> a bien été renommé en <u>'.MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php'.'</u></span>';
									}
									
									$domXml = new \DomDocument('1.0', CHARSET);
									
									if($domXml->load(ROUTE)){		
										$nodeXml = $domXml->getElementsByTagName('routes')->item(0);
										$sentences = $nodeXml->getElementsByTagName('route');
							
										foreach($sentences as $sentence){
											if ($sentence->getAttribute("controller") == $this->_commandExplode[2]){
												$sentence->setAttribute("controller", $this->_commandExplode[3]);
												$domXml->save(ROUTE);

											}
										}
									}
									
									$data = file_get_contents(CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php');
									$data = preg_replace('#class '.$this->_commandExplode[2].' extends controller#isU',
														  'class '.$this->_commandExplode[3].' extends controller', $data);
									file_put_contents(CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php', $data);
									
									$data = file_get_contents(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php');
									$data = preg_replace('#class manager'.ucfirst($this->_commandExplode[2]).' extends model#isU',
														  'class manager'.ucfirst($this->_commandExplode[3]).' extends model', $data);
									file_put_contents(MODEL_PATH.$this->_commandExplode[3].MODEL_EXT.'.php', $data);

									$this->_result = '<br />><span style="color: chartreuse;"> le contrôleur <u>'.$this->_commandExplode[2].'</u> a bien été modifiée en <u>'.$this->_commandExplode[3].'</u> et ses options ont été modifiées</span>';
								}
								else{
									$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[3].CONTROLLER_EXT.'.php';
									$this->_result = '<br />><span style="color: red;"> Un contrôleur porte déjà le même nom</span>';
								}
							}
							else{
								$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
								$this->_result = '<br />><span style="color: red;"> Ce contrôleur n\'existe pas</span>';
							}
						}
						else{
							$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_commandExplode[2].CONTROLLER_EXT.'.php';
							$this->_result = '<br />><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					elseif(preg_match('#list controller#', $this->_command)){
						if($this->_dossier = opendir(CONTROLLER_PATH)){
							$this->_stream .= '<br />>####################### CONTROLLER';
							while(false !== ($this->_fichier = readdir($this->_dossier))){
								if(is_file(CONTROLLER_PATH.$this->_fichier) && $this->_fichier!='.htaccess'){
									$this->_stream .= '<br />> '.CONTROLLER_PATH.$this->_fichier.'';
								}
							}
						}
						if($this->_dossier = opendir(MODEL_PATH)){
							$this->_stream .= '<br />>####################### MODEL';
							while(false !== ($this->_fichier = readdir($this->_dossier))){
								if(is_file(MODEL_PATH.$this->_fichier) && $this->_fichier!='.htaccess'){
									$this->_stream .= '<br />> '.MODEL_PATH.$this->_fichier.'';
								}
							}
						}

						$this->_result = '<br />><span style="color: chartreuse;"> fichiers des controller listés</span>';
					}
					elseif(preg_match('#list included#', $this->_command)){				
						foreach(get_included_files() as $val){
							$this->_stream .= '<br />> '.$val;
						}
						$this->_result = '<br />><span style="color: chartreuse;"> fichiers inclus listés</span>';
					}
					elseif(preg_match('#clear cache#', $this->_command)){
						if($this->_dossier = opendir(CACHE_PATH)){
							while(false !== ($this->_fichier = readdir($this->_dossier))){
								if(is_file(CACHE_PATH.$this->_fichier) && $this->_fichier!='.htaccess' && file_exists(CACHE_PATH.$this->_fichier) && is_readable(CACHE_PATH.$this->_fichier)){
									unlink(CACHE_PATH.$this->_fichier);
									$this->_stream .= '<br />> '.CACHE_PATH.$this->_fichier.'';
								}
							}
						}
						$this->_result = '<br />><span style="color: chartreuse;"> le cache a bien été vidé</span>';
					}
					elseif(preg_match('#clear log#', $this->_command)){
						if($this->_dossier = opendir(LOG_PATH)){
							while(false !== ($this->_fichier = readdir($this->_dossier))){
								if(is_file(LOG_PATH.$this->_fichier) && file_exists(LOG_PATH.$this->_fichier) && is_readable(LOG_PATH.$this->_fichier)){
									unlink(LOG_PATH.$this->_fichier);
									$this->_stream .= '<br />> '.LOG_PATH.$this->_fichier.LOG_EXT;
								}
							}
						}
						$this->_result = '<br />><span style="color: chartreuse;"> le log a bien été vidé</span>';
					}
					elseif(preg_match('#install add-on (.*) (.*)#', $this->_command)){
						$install = new install($this->_commandExplode[2], $this->_bdd, $this->_commandExplode[3], $this->_lang);

						$install->check();

						if($install->getConflit() == true){
							$this->_stream .= $install->install();

							$this->_stream .= '<br />> <span style="color: chartreuse;">'.($install->getReadMe()).'</span>';
							$this->_result = '<br />><span style="color: chartreuse;"> L\'add-on '.$this->_commandExplode[2].' a bien été installé</span>';
						}
						else{
							$i = 0;
									
							foreach($install->getError() as $valeur){
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

							$this->_result = '<br />><span style="color: red;"> L\'add-on '.$this->_commandExplode[2].' n\'a pas pu être installé</span>';
						}
					}
					elseif(preg_match('#uninstall add-on (.*)#', $this->_command)){
						$install = new install();
						$install->checkUninstall($this->_commandExplode[2]);

						if($install->getConflitUninstall() == true){
							$this->_result = '<br />><span style="color: chartreuse;"> L\'add-on d\'id '.$this->_commandExplode[2].' a bien été désinstallé</span>';
							$this->_stream .= $install->uninstall($this->_commandExplode[2]);
						}
						else{
							$i = 0;
									
							foreach($install->getError() as $valeur){
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

							$this->_result = '<br />><span style="color: red;"> L\'add-on d\'id '.$this->_commandExplode[2].' n\'a pas pu être désinstallé</span>';
						}
					}
					elseif(preg_match('#add backup (.*) (.*)#', $this->_command)){
						$backup = new backup();

						if($this->_commandExplode[2] == 'root'){
							$this->_commandExplode[2] ='./';
						}

						if($backup->addBackup($this->_commandExplode[2], $this->_commandExplode[3])){
							$this->_stream .= '<br />><span style="color: chartreuse"> backup <u>'.$this->_commandExplode[2].'</u> sous le nom de <u>'.$this->_commandExplode[3].'</u> réussie</span>';
							$this->_result = '<br />><span style="color: chartreuse;"> le backup a bien été créé</span>';
						}
						else{
							$this->_stream .= $backup->getError();
							$this->_result = '<br />><span style="color: red;"> le backup n\'a pas pu être créé</span>';
						}
					}
					elseif(preg_match('#install backup (.*) (.*)#', $this->_command)){
						$backup = new backup();

						if($this->_commandExplode[3] == 'root'){ 
							$this->_commandExplode[2] = './';
						}
						
						if($backup->installBackup($this->_commandExplode[2], $this->_commandExplode[3])){
							$this->_result = '<br />><span style="color: chartreuse;"> le backup <u>'.$this->_commandExplode[2].'</u> a bien été installé dans le répertoire <u>'.$this->_commandExplode[3].'</u></span>';
						}
						else{
							$this->_stream .= $backup->getError();
							$this->_result = '<br />><span style="color: chartreuse;"> le backup n\'a pas pu être installé</span>';
						}
					}
					elseif(preg_match('#delete backup (.*)#', $this->_command)){
						$backup = new backup();
						if($backup->delBackup($this->_commandExplode[2])){
							$this->_stream .= '<br />><span style="color: chartreuse"> backup <u>'.$this->_commandExplode[2].'</u> supprimé</span>';
							$this->_result = '<br />><span style="color: chartreuse;"> le backup a bien été supprimé</span>';
						}
						else{
							$this->_stream .= $backup->getError();
							$this->_result = '<br />><span style="color: chartreuse;"> le backup n\'a pas pu être créé</span>';
						}
					}
					elseif(preg_match('#help#', $this->_command)){
						$this->_stream .= '<br />> add controller nom (l\'url par défaut est le nom du contrôleur; cette url sera créée à condition que l\'id (nom) ne soit pas déjà utilisé';
						$this->_stream .= '<br />> set controller nom nouveaunom';
						$this->_stream .= '<br />> delete controller nom';
						$this->_stream .= '<br />> add template nom';
						$this->_stream .= '<br />> set template nom nouveaunom';
						$this->_stream .= '<br />> delete template nom';
						$this->_stream .= '<br />> list template';
						$this->_stream .= '<br />> list included';
						$this->_stream .= '<br />> list controller';
						$this->_stream .= '<br />> list cache';
						$this->_stream .= '<br />> list backup';
						$this->_stream .= '<br />> clear cache';
						$this->_stream .= '<br />> clear log';
						$this->_stream .= '<br />> clear';
						$this->_stream .= '<br />> install add-on folder base';
						$this->_stream .= '<br />> uninstall add-on id';
						$this->_stream .= '<br />> see log nomdulogsansextansion';
						$this->_stream .= '<br />> see route';
						$this->_stream .= '<br />> see helper';
						$this->_stream .= '<br />> see app';
						$this->_stream .= '<br />> see firewall';
						$this->_stream .= '<br />> see antispam';
						$this->_stream .= '<br />> see installed';
						$this->_stream .= '<br />> see cron';
						$this->_stream .= '<br />> see add-on';
						$this->_stream .= '<br />> see file nom';
						$this->_stream .= '<br />> see backup nom';
						$this->_stream .= '<br />> add backup chemin nom';
						$this->_stream .= '<br />> delete backup nom';
						$this->_stream .= '<br />> install backup nom to';
						$this->_stream .= '<br />> update';  
						$this->_stream .= '<br />> update updater';
						$this->_stream .= '<br />> changepassword nouveaumdp';
						$this->_stream .= '<br />> connect mdp';
						$this->_stream .= '<br />> disconnect';
						$this->_stream .= '<br />> help';
						$this->_result  = '<br />><span style="color: chartreuse;"> liste des commandes</span>';
					}
					elseif(preg_match('#update updater#', $this->_command)){
						$this->_stream .= $this->_updater();
						$this->_result = '<br />><span style="color: chartreuse;"> updater à jour</span><meta http-equiv="refresh" content="1; URL=#">';
					}
					elseif(preg_match('#update#', $this->_command)){
						$this->_stream .= $this->_update();
						$this->_result = '<br />><span style="color: chartreuse;"> framework à jour</span>';
					}
					elseif(preg_match('#disconnect#', $this->_command) && $this->_mdp==false){
						$this->_result = '<br />><span style="color: chartreuse;"> Vous avez été déconnecté(e)</span>';
						$_SESSION['GC_terminalMdp'] = 0;
					}
					elseif(preg_match('#changepassword (.+)#', $this->_command)){
						$sauvegarde = file_get_contents('web.config.php');
						$sauvegarde = preg_replace("`define\('TERMINAL_MDP', '(.+)'\)`isU", 'define(\'TERMINAL_MDP\', \''.$this->_commandExplode[1].'\')',  $sauvegarde);
						file_put_contents('web.config.php', $sauvegarde);
						$this->_result = '<br />><span style="color: chartreuse;"> Le mot de passe a bien été modifié'.$sauvegarde.'</span>';
					}
					elseif(preg_match('#see file (.+)#', $this->_command)){
						if(is_file($this->_commandExplode[2]) && file_exists($this->_commandExplode[2]) && is_readable($this->_commandExplode[2]) && $this->_commandExplode[2] != 'web.config.php'){
							$sauvegarde = file_get_contents($this->_commandExplode[2]);
							$sauvegardes = explode("\n", $sauvegarde);
									
							$i = 0;
									
							foreach($sauvegardes as $key => $valeur){
								$search = array ();
								$replace = array ();
								$valeur = preg_replace($search, $replace, $valeur);
								if($i == 0){
									$this->_stream .= '<br />> '.$key.'. <span style="color: chartreuse;">'.(htmlspecialchars($valeur)).'</span>';
									$i=1;
								}
								else{
									$this->_stream .= '<br />> '.$key.'. <span style="color: red;">'.(htmlspecialchars($valeur)).'</span>';
									$i=0;
								}							
							}

							$this->_result = '<br />><span style="color: chartreuse;"> Le fichier <strong>'.$this->_commandExplode[2].'</strong> a bien été affiché</span>';
						}
						else{
							$this->_result = '<br />><span style="color: red;"> Le fichier <strong>'.$this->_commandExplode[2].'</strong> n\'existe pas.</span>';
						}
					}
					elseif(preg_match('#see backup (.*)#', $this->_command)){
						$backup = new backup();
						$liste = $backup->seeBackup($this->_commandExplode[2]);

						$i = 0;

						if($liste != false){
							foreach($liste as $key => $value){
								if($i == 0){
									$this->_stream .= '<br />> <span style="color: chartreuse;">'.$value.'</span>';
									$i=1;
								}
								else{
									$this->_stream .= '<br />> <span style="color: red;">'.$value.'</span>';
									$i=0;
								}								
							}

							$this->_result = '<br />><span style="color: chartreuse;"> le backup '.$this->_commandExplode[2].' a bien été affiché</span>';
						}
						else{
							$this->_stream .= $backup->getError();
							$this->_result = '<br />><span style="color: chartreuse;"> le backup n\'a pas pu être affiché</span>';
						}
					}
					elseif(preg_match('#see (.+)#', $this->_command)){
						switch($this->_commandExplode[1]){
							case 'log':
								if(is_file(LOG_PATH.$this->_commandExplode[2].LOG_EXT) && file_exists(LOG_PATH.$this->_commandExplode[2].LOG_EXT) && is_readable(LOG_PATH.$this->_commandExplode[2].LOG_EXT)){
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de log <strong>'.LOG_PATH.$this->_commandExplode[2].LOG_EXT.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de log <strong>'.LOG_PATH.$this->_commandExplode[2].LOG_EXT.'</strong> n\'existe pas</span>';
								}
							break;
							
							case 'route':
								if(is_file(ROUTE) && file_exists(ROUTE) && is_readable(ROUTE)){
									$sauvegarde = file_get_contents(ROUTE);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de route <strong>'.ROUTE.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de route <strong>'.ROUTE.'</strong> n\'existe pas ce qui est étonnant</span>';
								}
							break;

							case 'errorperso':
								if(is_file(ERRORPERSO) && file_exists(ERRORPERSO) && is_readable(ERRORPERSO)){
									$sauvegarde = file_get_contents(ERRORPERSO);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier <strong>'.ERRORPERSO.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier <strong>'.ERRORPERSO.'</strong> n\'existe pas ce qui est étonnant</span>';
								}
							break;

							case 'installed':
								if(is_file(ADDON) && file_exists(ADDON) && is_readable(ADDON)){
									$sauvegarde = file_get_contents(ADDON);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier listant les plugins installés <strong>'.ADDON.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier listant les add-ons installés <strong>'.ADDON.'</strong> n\'existe pas ce qui est étonnant</span>';
								}
							break;

							case 'cron':
								if(is_file(CRON) && file_exists(CRON) && is_readable(CRON)){
									$sauvegarde = file_get_contents(CRON);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier listant les crons <strong>'.CRON .'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier listant les crons <strong>'.CRON .'</strong> n\'existe pas ce qui est étonnant</span>';
								}
							break;

							case 'add-on':
								$domXml = new \DomDocument('1.0', CHARSET);

								if($domXml->load(ADDON)){
									$nodeXml = $domXml->getElementsByTagName('installed')->item(0);
									$nodeXml = $nodeXml->getElementsByTagName('install');

									$i = 0;

									foreach ($nodeXml as $key => $value){
										if($i == 0){
											$this->_stream .= '<br />> <span style="color: chartreuse;">'.$value->getAttribute('id').' / '.$value->getAttribute('name').'</span>';
											$i=1;
										}
										else{
											$this->_stream .= '<br />> <span style="color: red;">'.$value->getAttribute('id').' / '.$value->getAttribute('name').'</span>';
											$i=0;
										}
									}

									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier listant les add-ons installés <strong>'.ADDON.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier listant les add-ons installés <strong>'.ADDON.'</strong> est endommagé</span>';
								}
							break;
							
							case 'helper':
								if(is_file(HELPER) && file_exists(HELPER) && is_readable(HELPER)){
									$sauvegarde = file_get_contents(HELPER);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de helpers <strong>'.HELPER.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de helpers <strong>'.HELPER.'</strong> n\'existe pas. Vous devriez vite le récupérer</span>';
								}
							break;
							
							case 'app':
								if(is_file(APPCONFIG) && file_exists(APPCONFIG) && is_readable(APPCONFIG)){
									$sauvegarde = file_get_contents(APPCONFIG);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de config <strong>'.APPCONFIG.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de config <strong>'.APPCONFIG.'</strong> n\'existe pas. Vous devriez vite le récupérer</span>';
								}
							break;
							
							case 'firewall':
								if(is_file(FIREWALL) && file_exists(FIREWALL) && is_readable(FIREWALL)){
									$sauvegarde = file_get_contents(FIREWALL);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de sécurité <strong>'.FIREWALL.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de sécurité <strong>'.FIREWALL.'</strong> n\'existe pas. Vous devriez vite le récupérer si vous voulez disposer d\'un pare feu</span>';
								}
							break;

							case 'antispam':
								if(is_file(ASPAM) && file_exists(ASPAM) && is_readable(ASPAM)){
									$sauvegarde = file_get_contents(ASPAM);
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
									
									$this->_result = '<br />><span style="color: chartreuse;"> Le fichier de configuration de l\'antispam <strong>'.ASPAM.'</strong> a bien été affiché</span>';
								}
								else{
									$this->_result = '<br />><span style="color: red;"> Le fichier de configuration de l\'antispam <strong>'.ASPAM.'</strong> n\'existe pas. Vous devriez vite le récupérer si vous voulez disposer d\'un système d\'anti spam</span>';
								}
							break;
						}
					}
				}
				else{
					$this->_result = '<br />><span style="color: red;"> Erreur de connexion. Vous devez vous connecter grâce au  mot de passe du fichier de config</span>';
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
					$suppr = "";

					$sauvegarde = file_get_contents('web.config.php');
					$sauvegarde = preg_replace('`(.*)parametres de connexion a la base de donnees(.*)`isU', '$2', $sauvegarde);

					foreach($this->_updateFile as $file){				
						$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.$file);
						$fp = fopen($file, "w");
						$headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);
						$contenu .= '<br />> <span style="color: chartreuse;">'.$file.' : '.$headers.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.$file.'</span>';
					}

					$suppr = file_get_contents('web.config.php');
					$suppr = preg_replace('`(.*)(parametres de connexion a la base de donnees)(.*)`is', '$1parametres de connexion a la base de donnees', $suppr);
					
					if($suppr!="" && $sauvegarde!=""){
						file_put_contents('web.config.php', $suppr);
						file_put_contents('web.config.php', $sauvegarde, FILE_APPEND);
					}

					return $contenu;
				}	
				else{
					return $contenu .= '<br />> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
				}
			}

			public  function __destruct(){
			}
		}
	}