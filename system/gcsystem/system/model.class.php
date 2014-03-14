<?php
	/**
	 * @file : model.class.php
	 * @author : fab@c++
	 * @description : class gérant la partie model. abstraite
	 * @version : 2.2 bêta
	*/
	
	namespace system{
		abstract class model{
			use error, langInstance, general, urlRegex, errorPerso; //trait

			protected $bdd                                ; //contient la connexion sql
			
			final public  function __construct($lang="", $bdd){
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();			
				if(CONNECTBDD == true) {$this->bdd=$bdd; }
			}
			
			public function init(){
			}
			
			protected function end(){	
			}
				
			final protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
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
	}