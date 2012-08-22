<?php
	/**
	 * @file : installGc.class.php
	 * @author : fab@c++
	 * @description : class gérant l'installation de rubriques externes
	 * @version : 2.0 bêta
	*/

	class installGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait
		
		protected $_file                             ;
		protected $_zip                              ;
		protected $_zipContent              = array(); 
		protected $_conflit                 = true   ; //true = pas de conflits, false = conflits

		public  function __construct($file = '', $lang = 'fr'){
			$this->_setFile($file);
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
		}

		public function check(){
			if($this->_zip->getIsExist()==true){
				$this->_zipContent = $this->_zip->getContentFileZip();

				//on check si le fichier install.xml est valide
				$this->_domXml = new DomDocument('1.0', CHARSET);

				if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
					//on check les conflits dans les fichiers à installer
					foreach ($this->_zipContent as $key => $value) {
						
					}

					//on check les conflits dans les paramètres des fichiers de config

					//on check les conflits dans les tables sql à installer
				}
				else{
					$this->_conflit = false;
					$this->_addError('le fichier install.xml est endommagé. L\'installation de la rubrique a échouée', __FILE__, __LINE__, ERROR);
					return false;
				}
			}
			else{
				return false;
			}
		}

		public function install(){
			if($this->_zip->getIsExist()==true && $this->_conflit == true){
			}
			else{
				return false;
			}
		}

		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}

		protected function _setFile($file){
			$this->_zip = new zipGc($file);
		}

		public  function __destruct(){
		}
	}