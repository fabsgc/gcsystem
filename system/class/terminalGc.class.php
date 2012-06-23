<?php
	/*\
	 | ------------------------------------------------------
	 | @file : terminalGc.class.php
	 | @author : fab@c++
	 | @description : class gérant les fichiers compressés
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class terminalGc{
		public $command                       ; //contenu à traiter
		public $commandExplode                ; //contenu à traiter
		public $result                        ='/ commande non reconnu'; //resultat du traitement
		public $dossier                       ; //dossier
		public $fichier                       ; //fichier
		
		public  function __construct($command){
			$this->command = $command;
			$this->command = substr($this->command,11,strlen($this->command)); 
			$this->commandExplode = explode(' ', trim($this->command)); 
		}
		
		public function parse(){
			if(preg_match('#add rubrique (.+)#', $this->command)){
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
				$this->result = '<br /><span style="color: black;">----</span>> la rubrique <u>'.$this->commandExplode[2].'</u> a bien été créée';
			}
			
			if(preg_match('#delete rubrique (.+)#', $this->command)){
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
				
				$this->result = '<br /><span style="color: black;">----</span>> la rubrique <u>'.$this->commandExplode[2].'</u> a bien été supprimée';
			}
			
			if(preg_match('#add template (.+)#', $this->command)){
				$monfichier = fopen(TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT, 'a');
				fclose($monfichier);
				$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->commandExplode[2].TEMPLATE_EXT;
				$this->result = '<br /><span style="color: black;">----</span>> le template <u>'.$this->commandExplode[2].'</u> a bien été créé';
			}
			
			if(preg_match('#list template#', $this->command)){				
				if($this->dossier = opendir(TEMPLATE_PATH)){
					while(false !== ($this->fichier = readdir($this->dossier))){
						if(is_file(TEMPLATE_PATH.$this->fichier)){
							$this->command .= '<br /><span style="color: black;">----</span>> '.TEMPLATE_PATH.$this->fichier.'';
						}
					}
				}
				$this->result = '<br /><span style="color: black;">----</span>> fichiers de template listés';
			}
			
			if(preg_match('#list included#', $this->command)){				
				foreach(get_included_files() as $val){
					$this->command .= '<br /><span style="color: black;">----</span>> '.$val;
				}
				$this->result = '<br /><span style="color: black;">----</span>> fichiers inclus';
			}
			
			if(preg_match('#clear cache#', $this->command)){
				if($this->dossier = opendir(CACHE_PATH)){
					while(false !== ($this->fichier = readdir($this->dossier))){
						if(is_file(CACHE_PATH.$this->fichier)){
							unlink(CACHE_PATH.$this->fichier);
							$this->command .= '<br /><span style="color: black;">----</span>> '.CACHE_PATH.$this->fichier.'';
						}
					}
				}
				$this->result = '<br /><span style="color: black;">----</span>> le cache a bien été vidé';
			}
			
			if(preg_match('#clear log#', $this->command)){
				if($this->dossier = opendir(LOG_PATH)){
					while(false !== ($this->fichier = readdir($this->dossier))){
						if(is_file(LOG_PATH.$this->fichier)){
							unlink(LOG_PATH.$this->fichier);
							$this->command .= '<br /><span style="color: black;">----</span>> '.LOG_PATH.$this->fichier.'';
						}
					}
				}
				$this->result = '<br /><span style="color: black;">----</span>> le log a bien été vidé';
			}
			
			return '> '.$this->command.' '.$this->result;
		}
	}
?>