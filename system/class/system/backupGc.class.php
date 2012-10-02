<?php
	/**
	 * @file : backupGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les backups de code directement dans le fw
	 * @version : 2.0 bêta
	*/
	
	class backupGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait

		public  function __construct($lang=""){
			$this->_langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
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