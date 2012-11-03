<?php
	/**
	 * @file : backupGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les backups de code directement dans le fw
	 * @version : 2.0 bêta
	*/
	
	class backupGc{
		use errorGc, domGc, generalGc;                  //trait

		/* le nom du bakcup est constitué du nom voulu par l'utilisateur précédé du timestamp */

		public  function __construct($lang=""){
			$this->_langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
		}

		public function addBackup($path = '', $nom = ''){ //path est le répertoire ou le fichier à sauvegarder
			return true;
		}

		public function delBackup($nom = ''){ // ici le nom est nom_timestamp pas d'extension
			return true;
		}

		public function seeBackup($nom = ''){ //on donne le nom du zip sans l'extension
			return true;
		}

		public function installBackup($nom = '', $to = ''){ //on donne le nom du zip sans l'extension
			return true;
		}

		public function listBackup(){ //liste tous les backups
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		public  function __destruct(){
		}
	}