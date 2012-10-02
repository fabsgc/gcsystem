<?php
	/**
	 * @file : errorPersoGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les erreurs personnalisées de façon plus propre et plus simple
	 * @version : 2.0 bêta
	*/

	class errorPersoGc{
		use errorGc, langInstance, domGc;                                //trait

		public  function __construct($lang=""){
			$this->_langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
		}

		public function errorPerso($id, $var = array()){

		}

		public function errorPersoTpl($id, $var = array()){

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