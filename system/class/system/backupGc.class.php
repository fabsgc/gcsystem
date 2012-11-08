<?php
	/**
	 * @file : backupGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les backups de code directement dans le fw
	 * @version : 2.0 bêta
	*/
	
	class backupGc{
		use errorGc, domGc, generalGc;                  //trait

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
					$zip = new zipGc(BACKUP_PATH.$nom.'.zip');
					$zip->putFileToZip($path, zipGc::PUTDIR);

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
		 * @return void
		 * @since 2.0
		*/

		public function seeBackup($nom = ''){ //on donne le nom du zip sans l'extension
			if(file_exists(BACKUP_PATH.$nom.'.zip')){
				$zip = new zipGc(BACKUP_PATH.$nom.'.zip');

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
		 * @return void
		 * @since 2.0
		*/

		public function installBackup($nom = '', $to = ''){ //on donne le nom du zip sans l'extension
			return true;
		}

		/**
		 * retourne un array contenant la liste des backups
		 * @access public
		 * @return array
		 * @since 2.0
		*/

		public function listBackup(){ //liste tous les backups
		}

		/**
		 * retourne les erreurs sous une forme de type terminal
		 * @access public
		 * @return string
		 * @since 2.0
		*/

		public function getError(){
			$result = "";

			foreach ($this->_error as $key => $value) {
				$result .= '<br />><span style="color: red"> '.$value.'</span>';
			}

			return $result;
		}
		
		public  function __destruct(){
		}
	}