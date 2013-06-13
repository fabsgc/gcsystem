<?php
	/**
	 * @file : modelGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la partie model. abstraite
	 * @version : 2.0 bêta
	*/
	
	abstract class modelGc{
		use errorGc, langInstance, generalGc, urlRegex, domGc, errorPerso ; //trait

		protected $bdd                                ; //contient la connexion sql
		
		final public  function __construct($lang="", $bdd){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();			
			if(CONNECTBDD == true) {$this->bdd=$bdd; }
		}
		
		public function init(){
		}
			
		final protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		final protected function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		final protected function getLang(){
			return $this->_lang;
		}
		
		final protected function setLang($lang){
			$this->_lang=$lang;
			$this->_langInstance->setLang($this->_lang);
		}
		
		public  function __destruct(){
		}
	}