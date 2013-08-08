<?php
	/**
	 * @file : cronGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers crons
	 * @version : 2.0 bêta
	*/
	
	class cronGc {
		use errorGc, domGc, generalGc;                            //trait
		
		public function __construct($lang = 'fr'){
			$this->_lang=$lang;
			$this->_createLangInstance();

			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(CRON)){				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('crons')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('cron');

				foreach($this->_markupXml as $sentence){
					if (($sentence->getAttribute("executed") + $sentence->getAttribute("time")) < (time()) || $sentence->getAttribute("time") == 0){
						$sentence->setAttribute("executed", time());
						$this->_domXml->save(CRON);
						//le cron doit être réexécuté : on le reexecute et on
						$rubrique = $sentence->getAttribute("rubrique");
						$this->_setRubrique($sentence->getAttribute("rubrique")); //on inclut les fichiers necéssaire à l'utilisation d'une rubrique

						if(class_exists($rubrique)){
							$class = new $rubrique($this->_lang);
							$class->setNameModel($rubrique);
							ob_start ();
								$class->init();
								if(is_callable(array($rubrique, 'action'.ucfirst($sentence->getAttribute("action"))))){
									$action = 'action'.ucfirst($sentence->getAttribute("action"));
									$class->$action();
									$this->_addError('CRON : Appel du contrôleur "action'.ucfirst($sentence->getAttribute("action")).'" de la rubrique "'.$rubrique.'" réussi', __FILE__, __LINE__, INFORMATION);
								}
								else{
									$this->_addError('CRON : L\'appel de l\'action "action'.ucfirst($_GET['action']).'" de la rubrique "'.$rubrique.'" a échoué.', __FILE__, __LINE__, WARNING);
								}
								$class->end();
							$this->_output = ob_get_contents();
							ob_get_clean();
						}
						else{
							$this->_addError('CRON : L\'appel de la rubrique "'.$rubrique.'" a échoué.', __FILE__, __LINE__, ERROR);
						}

						$this->setErrorLog(LOG_CRONS, '['.$sentence->getAttribute("action")."]\n[".$this->_output."]");
					}
				}
			}
			else{
				$this->_addError('Le fichier des tâches crons "'.CRON.'" n\'a pas pu être chargé', __FILE__, __LINE__, ERROR);
			}
		}

		private function _setRubrique($rubrique){
			if(file_exists(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php')){
				if(file_exists(MODEL_PATH.$rubrique.MODEL_EXT.'.php')){
					require_once(MODEL_PATH.$rubrique.MODEL_EXT.'.php');
					$this->_addError('CRON : Chargement des fichiers "'.RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php" et "'.MODEL_PATH.$rubrique.MODEL_EXT.'.php"', __FILE__, __LINE__, INFORMATION);
				}
				require_once(RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php');
				return true;
			}
			else{ 
				$this->_addError('CRON : '.$this->useLang('rubriquenotfound', array('rubrique' => $rubrique)), __FILE__, __LINE__, ERROR);
				$this->_addError('CRON : Echec lors du chargement des fichiers "'.RUBRIQUE_PATH.$rubrique.RUBRIQUE_EXT.'.php" et "'.MODEL_PATH.$rubrique.MODEL_EXT.'.php"', __FILE__, __LINE__, ERROR);
				return false;
			}
		}

		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence, $var = array()){
			return $this->_langInstance->loadSentence($sentence, $var);
		}
		
		public function __destruct(){
		}	
	}