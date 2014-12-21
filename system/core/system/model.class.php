<?php
	/*\
	 | ------------------------------------------------------
	 | @file : model.class.php
	 | @author : fab@c++
	 | @description : class gérant la partie model. abstraite
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		abstract class model{
			use error, langInstance, general, urlRegex, errorPerso, ormFunctions;

			public $bdd                                ;
			
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
				return $this->lang;
			}
			
			final public function setLang($lang){
				$this->lang=$lang;
				$this->_langInstance->setLang($this->_lang);
			}

			/**
			 * retourne les données sous forme d'entités
			 * @access	public
			 * @param $data array
			 * @param $entity string
			 * @return	array
			 * @since 2.4
			 */
			final public function toEntity($data = array(), $entity = ''){
				return $this->ormToEntity($this->bdd, $data, $entity);
			}
			
			public  function __destruct(){
			}
		}
	}