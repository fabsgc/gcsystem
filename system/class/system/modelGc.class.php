<?php
	/**
	 * @file : modelGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la partie model. abstraite
	 * @version : 2.0 bêta
	*/
	
	abstract class modelGc{
		use errorGc, langInstance, generalGc, urlRegex; //trait

		protected $bdd                                ; //contient la connexion sql
		
		public  function __construct($lang="", $bdd){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
			if(CONNECTBDD == true) {$this->bdd=$bdd; }
		}
		
		public function init(){
			
		}
			
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		public function getLang(){
			return $this->_lang;
		}
		
		public function setLang($lang){
			$this->_lang=$lang;
			$this->_langInstance->setLang($this->_lang);
		}
		
		public  function __desctuct(){
		}
	}