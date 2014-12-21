<?php
	/*\
	 | ------------------------------------------------------
	 | @file : cron.class.php
	 | @author : fab@c++
	 | @description : class gérant les fichiers crons
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class cron {
			use error, general;
			
			public function __construct($lang = 'fr'){
				$this->_lang=$lang;
				$this->_createLangInstance();

				$devTool = $GLOBALS['appDev']->getShow();

				if(@fopen(CRON, 'r+')) {
					$this->domXml = new \DomDocument('1.0', CHARSET);
					if($this->domXml->load(CRON)){
						if($this->exception() == false){
							$nodeXml = $this->domXml->getElementsByTagName('crons')->item(0)->getElementsByTagName('actions')->item(0);
							$markupXml = $nodeXml->getElementsByTagName('cron');

							foreach($markupXml as $sentence){
								if (($sentence->getAttribute("executed") + $sentence->getAttribute("time")) < (time()) || $sentence->getAttribute("time") == 0){
									$sentence->setAttribute("executed", time());
									$this->domXml->save(CRON);

									$controller = $sentence->getAttribute("controller");
									$this->_setRubrique($sentence->getAttribute("controller")); //on inclut les fichiers necéssaires à l'utilisation d'un contrôleur

									if(class_exists($controller)){
										$class = new $controller($this->_lang);
										$class->setNameModel($controller);
										ob_start("ob_gzhandler");
											$class->init();
											if(is_callable(array($controller, 'action'.ucfirst($sentence->getAttribute("action"))))){
												$action = 'action'.ucfirst($sentence->getAttribute("action"));
												$class->$action();
												$this->_addError('CRON : Appel du contrôleur "action'.ucfirst($sentence->getAttribute("action")).'" du contrôleur "'.$controller.'" réussi', __FILE__, __LINE__, INFORMATION);
											}
											else{
												$this->_addError('CRON : L\'appel de l\'action "action'.ucfirst($_GET['action']).'" du contrôleur "'.$controller.'" a échoué.', __FILE__, __LINE__, WARNING);
											}
											$class->end();
										$this->_output = ob_get_contents();
										ob_get_clean();
									}
									else{
										$this->_addError('CRON : L\'appel du contrôleur "'.$controller.'" a échoué.', __FILE__, __LINE__, ERROR);
									}

									$this->setErrorLog(LOG_CRONS, '['.$sentence->getAttribute("action")."]\n[".$this->_output."]");
								}
							}
						}
						else{
							$this->_addError('la page appelante est une exception', __FILE__, __LINE__, INFORMATION);
							$this->_exception = true;
						}
					}
					else{
						$this->_addError('Le fichier des tâches crons "'.CRON.'" n\'a pas pu être chargé', __FILE__, __LINE__, ERROR);
					}
				}
				else{
					$this->_addError('le fichier des tâches crons est en cours de lecture.', __FILE__, __LINE__, WARNING);
				}

				$GLOBALS['appDev']->setShow($devTool);
			}

			public function exception(){
				$nodeXml = $this->domXml->getElementsByTagName('crons')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('config')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('exceptions')->item(0);
			
				$markup3Xml = $markupXml->getElementsByTagName('exception');

				foreach ($markup3Xml as $val) {
					if($_GET['controller'] == $val->getAttribute('controller') && $_GET['action'] == $val->getAttribute('action')){
						return true;
					}
				}

				return false;
			}

			private function _setRubrique($controller){
				if(file_exists(CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php')){
					if(file_exists(MODEL_PATH.$controller.MODEL_EXT.'.php')){
						require_once(MODEL_PATH.$controller.MODEL_EXT.'.php');
						$this->_addError('CRON : Chargement des fichiers "'.CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php" et "'.MODEL_PATH.$controller.MODEL_EXT.'.php"', __FILE__, __LINE__, INFORMATION);
					}
					require_once(CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php');
					return true;
				}
				else{ 
					$this->_addError('CRON : '.$this->useLang('gc_controllernotfound', array('controller' => $controller)), __FILE__, __LINE__, FATAL);
					$this->_addError('CRON : Echec lors du chargement des fichiers "'.CONTROLLER_PATH.$controller.CONTROLLER_EXT.'.php" et "'.MODEL_PATH.$controller.MODEL_EXT.'.php"', __FILE__, __LINE__, FATAL);
					return false;
				}
			}

			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}
			
			public function __destruct(){
			}	
		}
	}