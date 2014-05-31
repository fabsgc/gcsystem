<?php
	/**
	 * @file : model.class.php
	 * @author : fab@c++
	 * @description : class gérant la partie model. abstraite
	 * @version : 2.3 Bêta
	*/
	
	namespace system{
		abstract class model{
			use error, langInstance, general, urlRegex, errorPerso; //trait

			public $bdd                                ; //contient la connexion sql
			
			final public  function __construct($lang = "", $bdd){
				if($lang==""){ 
					$this->lang=$this->getLangClient(); 
				} else { 
					$this->lang=$lang; 

				}
				$this->_createLangInstance();

				if(CONNECTBDD == true) {
					$this->bdd = $bdd; 
				}

				$this->event = new eventManager();
			}
			
			public function init(){
			}
			
			protected function end(){	
			}
				
			final protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			final public function useLang($sentence, $var = array()){
				return $this->_langInstance->loadSentence($sentence, $var);
			}
			
			final public function getLang(){
				return $this->_lang;
			}
			
			final public function setLang($lang){
				$this->_lang=$lang;
				$this->_langInstance->setLang($this->_lang);
			}
			
			public  function __destruct(){
			}
		}
	}