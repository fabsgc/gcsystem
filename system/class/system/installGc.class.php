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
		protected $_forbiddenFile           = array();
		protected $_forbiddenDir            = array();

		public  function __construct($file = '', $lang = 'fr'){
			$this->_setFile($file);

			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();

			$this->_forbiddenFile = array(
				ROUTE, MODOCONFIG, APPCONFIG, PLUGIN, FIREWALL, ASPAM, 
				MODEL_PATH.'index'.MODEL_EXT.'.php', MODEL_PATH.'terminal'.MODEL_EXT.'.php',
				RUBRIQUE_PATH.'index'.RUBRIQUE_EXT.'.php', RUBRIQUE_PATH.'terminal'.RUBRIQUE_EXT.'.php', FUNCTION_GENERIQUE

			);

			$this->_forbiddenDir = array(
				'system/class/system/', 'system/class/lang/', 'system/class/log/', 'asset/image/GCsystem',
				'asset/'
			);
		}

		public function getConflit(){
			return $this->_conflit;
		}

		public function check(){
			if($this->_zip->getIsExist()==true){
				$this->_zipContent = $this->_zip->getContentFileZip();

				//on check si le fichier install.xml est valide
				$this->_domXml = new DomDocument('1.0', CHARSET);
				if($this->_domXml->loadXml($this->_zipContent['install.xml'])){
					//on check les conflits dans les fichiers à installer
					foreach ($this->_zipContent as $key => $value) {
						if(is_file($key) && file_exists($key)){
							$this->_conflit = false;
							$this->_addError('le fichier '.$key.' existe déjà dans le projet courant. Le remplacer risque de provoquer des disfonctionnement. ', __FILE__, __LINE__, ERROR);
						}
						elseif(is_file($key) && !file_exists($key)){
							$this->_conflit = false;
							$this->_addError('le fichier '.$key.' existe déjà dans le projet courant. Le remplacer risque de provoquer des disfonctionnement. ', __FILE__, __LINE__, ERROR);
						}
						elseif(is_file($key) && !file_exists($key)){
							$this->_conflit = false;
							$this->_addError('le fichier '.$key.' existe déjà dans le projet courant. Le remplacer risque de provoquer des disfonctionnement. ', __FILE__, __LINE__, ERROR);
						}
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
				$this->_conflit = false;
				$this->_addError('le fichier zip est endommagé ou inaccessible. L\'installation de la rubrique a échouée', __FILE__, __LINE__, ERROR);
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