<?php
	/**
	 * @file : antispamGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la lutte contre le spam par requête (IP)
	 * @version : 2.0 bêta
	*/
	
	class antispamGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait
		
		public  function __construct($lang=NULL){
			if($lang==NULL){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		public  function __desctuct(){
		}
	}