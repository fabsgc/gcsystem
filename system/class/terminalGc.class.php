<?php
	/*\
	 | ------------------------------------------------------
	 | @file : terminalGc.class.php
	 | @author : fab@c++
	 | @description : class g&#233;rant les fichiers compress&#233;s
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/

	class terminalGc{
		private $command                       ; //contenu à traiter
		private $commandExplode                ; //contenu à traiter
		private $result                        ='/ <span style="color: red;">commande non reconnu. Tapez help pour avoir la liste des commandes valides</span>'; //resultat du traitement
		private $dossier                       ; //dossier
		private $fichier                       ; //fichier
		private $forbidden                     ; //fichier interdit
		private $updateFile                    ; //fichier interdit
		private $updateDir                     ; //fichier interdit

		public  function __construct($command){
			$this->commandExplode = explode(' ', trim($command));
			$this->command = '<span style="color: gold;"> '.$command.'</span>';
			$this->forbidden = array(
				RUBRIQUE_PATH.'index.php', INCLUDE_PATH.'index'.INCLUDE_PATH.'.php', SQL_PATH.'index'.SQL_PATH.'.php', FORMS_PATH.'index'.FORMS_PATH.'.php',
				RUBRIQUE_PATH.'terminal.php',
				TEMPLATE_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.'GCmaintenance'.TEMPLATE_EXT,TEMPLATE_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_FEED, CLASS_JS, CLASS_TEXT, CLASS_DATE, CLASS_DOWNLOAD, CLASS_UPDLOAD, CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE,CLASS_LOG,CLASS_CACHE,CLASS_CAPTCHA,CLASS_EXCEPTION,CLASS_TEMPLATE,CLASS_LANG,CLASS_FILE,CLASS_DIR,CLASS_PICTURE,CLASS_SQL,CLASS_appDevGc,CLASS_ZIP,CLASS_ZIP,CLASS_BBCODE,CLASS_MODO,CLASS_TERMINAL,
			);
			$this->updateFile = array(
				RUBRIQUE_PATH.'terminal.php',
				'web.config.php',
				'index.php',
				LIB_PATH.'FormsGC/formsGC.class.php', LIB_PATH.'FormsGC/formsGCValidator.class.php',
				TEMPLATE_PATH.'GCbbcodeEditor'.TEMPLATE_EXT, TEMPLATE_PATH.'GCsystem'.TEMPLATE_EXT,TEMPLATE_PATH.'GCmaintenance'.TEMPLATE_EXT,TEMPLATE_PATH.'GCtplGc_blockInfo'.TEMPLATE_EXT,TEMPLATE_PATH.'GCsystemDev'.TEMPLATE_EXT,TEMPLATE_PATH.'GCtplGc_windowInfo'.TEMPLATE_EXT,TEMPLATE_PATH.'GCterminal'.TEMPLATE_EXT,TEMPLATE_PATH.'GCterminal'.TEMPLATE_EXT,
				CLASS_FEED, CLASS_JS, CLASS_TEXT, CLASS_DATE, CLASS_DOWNLOAD, CLASS_UPDLOAD ,CLASS_GENERAL_INTERFACE,CLASS_RUBRIQUE,CLASS_LOG,CLASS_CACHE,CLASS_CAPTCHA,CLASS_EXCEPTION,CLASS_TEMPLATE,CLASS_LANG,CLASS_FILE,CLASS_DIR,CLASS_PICTURE,CLASS_SQL,CLASS_appDevGc,CLASS_ZIP,CLASS_ZIP,CLASS_BBCODE,CLASS_MODO,CLASS_TERMINAL,
				LANG_PATH.'nl'.LANG_EXT, LANG_PATH.'fr'.LANG_EXT, LANG_PATH.'en'.LANG_EXT, 
			); // liste des fichiers syst&#232;mes à updater
		}

		public function parse(){
			if((preg_match('#connect (.+)#', $this->command) && isset($_SESSION['GC_terminalMdp']) && $_SESSION['GC_terminalMdp']==0) || (preg_match('#connect (.+)#', $this->command) && empty($_SESSION['GC_terminalMdp']))){
				if(TERMINAL_MDP == $this->commandExplode[1]){
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> Le mot de passe est correct</span>';
					$_SESSION['GC_terminalMdp'] = 1;
				}
				else{
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Le mot de passe est incorrect</span>';
				}
			}
			elseif(isset($_SESSION['GC_terminalMdp']) && $_SESSION['GC_terminalMdp']==1){
				if(preg_match('#add rubrique (.+)#', $this->command)){
					if(!array_search(RUBRIQUE_PATH.$this->commandExplode[2].'.php', $this->forbidden)){
						$monfichier = fopen(RUBRIQUE_PATH.$this->commandExplode[2].'.php', 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->commandExplode[2].'.php';
						$monfichier = fopen(INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php', 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php';
						$monfichier = fopen(SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php', 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php';
						$monfichier = fopen(FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php', 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php';
						$this->result = '<br /><span style="color: black;">----</span>> <span style="color: chartreuse;">la rubrique <u>'.$this->commandExplode[2].'</u> a bien &#233;t&#233; cr&#233;&#233;e</span>';
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->commandExplode[2].'.php';
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#delete rubrique (.+)#', $this->command)){
					if(!array_search(RUBRIQUE_PATH.$this->commandExplode[2].'.php', $this->forbidden)){
						if(is_file(RUBRIQUE_PATH.$this->commandExplode[2].'.php')){
							unlink(RUBRIQUE_PATH.$this->commandExplode[2].'.php');
							$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->commandExplode[2].'.php'.'';
						}
						if(is_file(INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php')){
							unlink(INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php');
							$this->command .= '<br /><span style="color: black;">----</span>> '.INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php';
						}
						if(is_file(SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php')){
							unlink(SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php');
							$this->command .= '<br /><span style="color: black;">----</span>> '.SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php';
						}
						if(is_file(FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php')){
							unlink(FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php');
							$this->command .= '<br /><span style="color: black;">----</span>> '.FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php';
						}

						$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> la rubrique <u>'.$this->commandExplode[2].'</u> a bien &#233;t&#233; supprim&#233;e</span>';
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->commandExplode[2].'.php';
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#add template (.+)#', $this->command)){
					if(!array_search(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, $this->forbidden)){
						$monfichier = fopen(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le template <u>'.$this->commandExplode[2].'</u> a bien &#233;t&#233; cr&#233;&#233;</span>';
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#add class (.+)#', $this->command)){
					if(!array_search(CLASS_PATH.$this->commandExplode[2].'.class.php', $this->forbidden)){
						$monfichier = fopen(CLASS_PATH.$this->commandExplode[2].'.class.php', 'a');
						fclose($monfichier);
						$this->command .= '<br /><span style="color: black;">----</span>> '.CLASS_PATH.$this->commandExplode[2].'.class.php';
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le fichier class <u>'.CLASS_PATH.$this->commandExplode[2].'.class.php'.'</u> a bien &#233;t&#233; cr&#233;&#233;</span>';
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.CLASS_PATH.$this->commandExplode[2].'.class.php';
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#list template#', $this->command)){		
					if($this->dossier = opendir(TEMPLATE_PATH)){
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(TEMPLATE_PATH.$this->fichier) && $this->fichier!='.htaccess'){
								$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->fichier.'';
							}
						}
					}
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> fichiers de template list&#233;s</span>';
				}
				elseif(preg_match('#delete template (.+)#', $this->command)){
					if(!array_search(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, $this->forbidden)){
						if(is_file(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT)){
							unlink(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT);
							$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
							$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT.'</u> a bien &#233;t&#233; supprim&#233;</span>';
						}
						else{
							$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
							$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Ce template n\'existe pas</span>';
						}
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#rename template (.+) (.+)#', $this->command)){
					if(!array_search(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, $this->forbidden)){
						if(is_file(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT)){
							if(!is_file(TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT)){
								rename(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT);
								$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT.' -> '.TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT;
								$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le template <u>'.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT.'</u> a bien &#233;t&#233; r&#233;nomm&#233; en <u>'.TEMPLATE_PATH.$this->commandExplode[3].'</u></span>';
							}
							else{
								$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT;
								$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Un template porte d&#233;jà le même nom</span>';
							}
						}
						else{
							$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
							$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Ce template n\'existe pas</span>';
						}
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#rename rubrique (.+) (.+)#', $this->command)){
					if(!array_search(RUBRIQUE_PATH.$this->commandExplode[2].'.php', $this->forbidden)){
						if(!array_search(RUBRIQUE_PATH.$this->commandExplode[2].'.php', $this->forbidden)){
							if(is_file(RUBRIQUE_PATH.$this->commandExplode[2].'.php')){
								if(!is_file(RUBRIQUE_PATH.$this->commandExplode[3].'.php')){
									if(is_file(RUBRIQUE_PATH.$this->commandExplode[2].'.php') && !is_file(RUBRIQUE_PATH.$this->commandExplode[3].'.php')){
										rename(RUBRIQUE_PATH.$this->commandExplode[2].'.php', RUBRIQUE_PATH.$this->commandExplode[3].'.php');
										$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->commandExplode[2].'.php'.' -> '.RUBRIQUE_PATH.$this->commandExplode[3].'.php';
										$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le fichier <u>'.RUBRIQUE_PATH.$this->commandExplode[2].'.php'.'</u> a bien &#233;t&#233; r&#233;nomm&#233; en <u>'.RUBRIQUE_PATH.$this->commandExplode[3].'.php'.'</u></span>';
									}
									if(is_file(INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php') && !is_file(INCLUDE_PATH.$this->commandExplode[3].FUNCTION_EXT.'.php')){
										rename(INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php', INCLUDE_PATH.$this->commandExplode[3].FUNCTION_EXT.'.php');
										$this->command .= '<br /><span style="color: black;">----</span>> '.INCLUDE_PATH.$this->commandExplode[2].FUNCTION_EXT.'.php'.' -> '.INCLUDE_PATH.$this->commandExplode[3].FUNCTION_EXT.'.php';
									}
									if(is_file(SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php') && !is_file(SQL_PATH.$this->commandExplode[3].SQL_EXT.'.php')){
										rename(SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php', SQL_PATH.$this->commandExplode[3].SQL_EXT.'.php');
										$this->command .= '<br /><span style="color: black;">----</span>> '.SQL_PATH.$this->commandExplode[2].SQL_EXT.'.php'.' -> '.SQL_PATH.$this->commandExplode[3].SQL_EXT.'.php';
									}
									if(is_file(FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php') && !is_file(FORMS_PATH.$this->commandExplode[3].FORMS_EXT.'.php')){
										rename(FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php', FORMS_PATH.$this->commandExplode[3].FORMS_EXT.'.php');
										$this->command .= '<br /><span style="color: black;">----</span>> '.FORMS_PATH.$this->commandExplode[2].FORMS_EXT.'.php'.' -> '.FORMS_PATH.$this->commandExplode[3].FORMS_EXT.'.php';
									}

									$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> la rubrique <u>'.$this->commandExplode[2].'</u> a bien &#233;t&#233; r&#233;nomm&#233;e en <u>'.$this->commandExplode[3].'</u></span>';
								}
								else{
									$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT;
								$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Une rubrique porte d&#233;jà le même nom</span>';
								}
							}
							else{
								$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
								$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Cette rubrique n\'existe pas</span>';
							}
						}
						else{
							$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[3].TEMPLATE_EXT;
							$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
						}
					}
					else{
						$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
						$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> La modification de ce fichier est interdite</span>';
					}
				}
				elseif(preg_match('#list rubrique#', $this->command)){
					if($this->dossier = opendir(RUBRIQUE_PATH)){
						$this->command .= '<br /><span style="color: black;">----</span>>####################### RUBRIQUE';
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(RUBRIQUE_PATH.$this->fichier) && $this->fichier!='.htaccess'){
								$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->fichier.'';
							}
						}
					}
					if($this->dossier = opendir(INCLUDE_PATH)){
						$this->command .= '<br /><span style="color: black;">----</span>>####################### FUNCTION';
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(INCLUDE_PATH.$this->fichier) && $this->fichier!='.htaccess'){
								$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->fichier.'';
							}
						}
					}
					if($this->dossier = opendir(SQL_PATH)){
						$this->command .= '<br /><span style="color: black;">----</span>>####################### SQL';
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(SQL_PATH.$this->fichier) && $this->fichier!='.htaccess'){
								$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->fichier.'';
							}
						}
					}
					if($this->dossier = opendir(FORMS_PATH)){
						$this->command .= '<br /><span style="color: black;">----</span>>####################### FORMS';
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(FORMS_PATH.$this->fichier) && $this->fichier!='.htaccess'){
								$this->command .= '<br /><span style="color: black;">----</span>> '.RUBRIQUE_PATH.$this->fichier.'';
							}
						}
					}
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> fichiers de rubrique list&#233;s</span>';
				}
				elseif(preg_match('#list included#', $this->command)){				
					foreach(get_included_files() as $val){
						$this->command .= '<br /><span style="color: black;">----</span>> '.$val;
					}
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> fichiers inclus list&#233;s</span>';
				}
				elseif(preg_match('#clear cache#', $this->command)){
					if($this->dossier = opendir(CACHE_PATH)){
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(CACHE_PATH.$this->fichier)){
								unlink(CACHE_PATH.$this->fichier);
								$this->command .= '<br /><span style="color: black;">----</span>> '.CACHE_PATH.$this->fichier.'';
							}
						}
					}
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le cache a bien &#233;t&#233; vid&#233;</span>';
				}
				elseif(preg_match('#clear log#', $this->command)){
					if($this->dossier = opendir(LOG_PATH)){
						while(false !== ($this->fichier = readdir($this->dossier))){
							if(is_file(LOG_PATH.$this->fichier)){
								unlink(LOG_PATH.$this->fichier);
								$this->command .= '<br /><span style="color: black;">----</span>> '.LOG_PATH.$this->fichier.'';
							}
						}
					}
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> le log a bien &#233;t&#233; vid&#233;</span>';
				}
				elseif(preg_match('#help#', $this->command)){
					$this->command .= '<br /><span style="color: black;">----</span>> add rubrique nom';
					$this->command .= '<br /><span style="color: black;">----</span>> delete rubrique nom';
					$this->command .= '<br /><span style="color: black;">----</span>> rename rubrique nom nouveaunom';
					$this->command .= '<br /><span style="color: black;">----</span>> add template nom';
					$this->command .= '<br /><span style="color: black;">----</span>> delete template nom';
					$this->command .= '<br /><span style="color: black;">----</span>> rename template nom nouveaunom';
					$this->command .= '<br /><span style="color: black;">----</span>> add class nom';
					$this->command .= '<br /><span style="color: black;">----</span>> list template';
					$this->command .= '<br /><span style="color: black;">----</span>> list included';
					$this->command .= '<br /><span style="color: black;">----</span>> list rubrique';
					$this->command .= '<br /><span style="color: black;">----</span>> clear cache';
					$this->command .= '<br /><span style="color: black;">----</span>> clear log';
					$this->command .= '<br /><span style="color: black;">----</span>> clear';
					$this->command .= '<br /><span style="color: black;">----</span>> update';
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> liste des commandes</span>';
				}
				elseif(preg_match('#update updater#', $this->command)){
					$this->command .= $this->updater();
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> updater à jour</span>';
				}
				elseif(preg_match('#update#', $this->command)){
					$this->command .= $this->update();
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> framework à jour</span>';
				}
				elseif(preg_match('#disconnect#', $this->command) && $this->mdp==false){
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> Vous avez &#233;t&#233; d&#233;connect&#233;</span>';
					$_SESSION['GC_terminalMdp'] = 0;
				}
				elseif(preg_match('#changepassword (.+)#', $this->command)){
					$sauvegarde = file_get_contents('web.config.php');
					$sauvegarde = preg_replace("`define\('TERMINAL_MDP', '(.+)'\)`isU", 'define(\'TERMINAL_MDP\', \''.$this->commandExplode[1].'\')',  $sauvegarde);
					file_put_contents('web.config.php', $sauvegarde);
					$this->result = '<br /><span style="color: black;">----</span>><span style="color: chartreuse;"> Le mot de passe a bien &#233;t&#233; modifi&#233;'.$sauvegarde.'</span>';
				}
			}
			else{
				$this->command .= '<span style="color: red;"> / erreur de connexion</span>';
				$this->result = '<br /><span style="color: black;">----</span>><span style="color: red;"> Vous devez vous connecter gr&#226;ce au  mot de passe du fichier de config</span>';
			}

			return '> '.$this->command.' '.$this->result;
		}

		private function updater(){
			if(curl_init()){
				$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.CLASS_TERMINAL);
				$fp = fopen(CLASS_TERMINAL, "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
				return $contenu .= '<br /><span style="color: black;">----</span>> <span style="color: chartreuse;">'.CLASS_TERMINAL.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.CLASS_TERMINAL.'</span>';
			}
			else{
				return $contenu .= '<br /><span style="color: black;">----</span>> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
			}
		}

		private function update(){
			if(curl_init()){
				$contenu = "";
				$sauvegarde ="";
				$sauvegarde2 ="";
				$suppr = "";
				$suppr2 = "";

				$sauvegarde = file_get_contents('web.config.php');
				$sauvegarde = preg_replace('`(.*)parametres de connexion a la base de donnees(.*)`isU', '$2', $sauvegarde);
				$sauvegarde2 = file_get_contents('index.php');
				$sauvegarde2 = preg_replace('`(.*)articulation du site web(.*)`isU', '$2', $sauvegarde2);

				foreach($this->updateFile as $file){				
					$ch = curl_init('https://raw.github.com/fabsgc/GCsystem/master/'.$file);
					$fp = fopen($file, "w");
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
					$contenu .= '<br /><span style="color: black;">----</span>> <span style="color: chartreuse;">'.$file.'</span> -> <span style="color: red;">https://raw.github.com/fabsgc/GCsystem/master/'.$file.'</span>';
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
				return $contenu .= '<br /><span style="color: black;">----</span>> <span style="color: red;">Vous devez activer l\'extension C_URL dans le php.ini pour pouvoir utiliser la fonction update';
			}
		}
	}