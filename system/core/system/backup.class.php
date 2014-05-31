<?php
	/**
	 * @file : backup.class.php
	 * @author : fab@c++
	 * @description : class gérant les backups de code directement dans le fw
	 * @version : 2.3 Bêta
	*/
	
	namespace system{
		class backup{
			use error, general;

			/**
			 * Crée l'instance de la classe
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public  function __construct(){
			}

			/**
			 * ajoute un nouveau backup
			 * @param string $path : répertoire ou fichier à sauvegarder
			 * @param string $nom : nom de la sauvegarde
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function addBackup($path = '', $nom = ''){ //path est le répertoire ou le fichier à sauvegarder
				if(file_exists($path)){
					if(!file_exists(BACKUP_PATH.$nom.'.zip')){
						$zip = new \helper\zip(BACKUP_PATH.$nom.'.zip');
						$zip->putFileToZip($path, \helper\zip::PUTDIR);
						return true;
					}
					else{
						$this->_addError('le backup de nom "'.$nom.'" existe déjà', __FILE__, __LINE__, ERROR);
						return false;
					}
				}
				else{
					$this->_addError('le répertoire ou le fichier '.$path.' n\'existe pas où n\'est pas accessible', __FILE__, __LINE__, ERROR);
					return false;
				}		
			}

			/**
			 * supprime un backup
			 * @param string $nom : nom du backup
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public function delBackup($nom = ''){
				if(file_exists(BACKUP_PATH.$nom.'.zip')){
					unlink(BACKUP_PATH.$nom.'.zip');
					return true;
				}
				else{
					$this->_addError('le backup de nom "'.$nom.'" n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * affiche le contenu d'un backup : juste le nom des fichiers, afficher le contenu serait trop long
			 * @param string $nom : nom du backup
			 * @access public
			 * @return string or array
			 * @since 2.0
			*/

			public function seeBackup($nom = ''){ //on donne le nom du zip sans l'extension
				if(file_exists(BACKUP_PATH.$nom.'.zip')){
					$zip = new \helper\zip(BACKUP_PATH.$nom.'.zip');
					return $zip->getContentZip();
				}
				else{
					$this->_addError('le backup de nom "'.$nom.'" n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * installe un backup dans le répertoire voulu
			 * @param string $nom : nom du backup
			 * @param string $to : répertoire de destination
			 * @access public
			 * @return bool
			 * @since 2.0
			*/

			public function installBackup($nom = '', $to = ''){ //on donne le nom du zip sans l'extension
				if(file_exists(BACKUP_PATH.$nom.'.zip')){
					$zip = new \helper\zip(BACKUP_PATH.$nom.'.zip');
					$dir = new \helper\dir($to);

					if($dir->getExist() == true){
						if($zip->putFileToFtp($to, \helper\zip::PUTDIR) == true){
							return true;
						}else{
							return false;
						}
					}
					else{
						$this->_addError('le répertoire de destination "'.$to.'" n\'existe pas', __FILE__, __LINE__, ERROR);
						return false;
					}

					return $zip->getContentZip();
				}
				else{
					$this->_addError('le backup de nom "'.$nom.'" n\'existe pas', __FILE__, __LINE__, ERROR);
					return false;
				}
				return true;
			}

			/**
			 * retourne un array contenant la liste des backups
			 * @access public
			 * @return array
			 * @since 2.0
			*/

			public function listBackup(){ //liste tous les backups
				$dir = new \helper\dir(BACKUP_PATH);

				if($dir->getExist() == true){
					return $dir->getDirArbo();
				}
				else{
					$this->_addError('le répertoire des backups '.BACKUP_PATH.' n\'est pas accessible', __FILE__, __LINE__, ERROR);
					return false;
				}
			}

			/**
			 * retourne les erreurs sous une forme de type terminal
			 * @access public
			 * @return string
			 * @since 2.0
			*/

			public function getError(){
				$result = "";

				foreach ($this->_error as $value) {
					$result .= '<br />><span style="color: red"> '.$value.'</span>';
				}

				return $result;
			}
			
			public  function __destruct(){
			}
		}
	}