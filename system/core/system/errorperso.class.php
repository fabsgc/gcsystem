<?php
	/*\
	 | ------------------------------------------------------
	 | @file : errorperso.class.php
	 | @author : fab@c++
	 | @description : class gérant les erreurs personnalisées de façon plus propre et plus simple
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class errorperso{
			use error, langInstance;

			protected                $_templateInstance = '' ;
			protected                $_templatedefault  = '' ;

			/**
			 * constructeur
			 * @access	public
			 * @param $lang string : langue
			 * @since 2.0
			 */
			public  function __construct($lang=""){
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();
			}

			/**
			 * retourne une erreur perso
			 * @access	public
			 * @param $id int : id de l'erreur
			 * @param $var array : variables
			 * @return boolean
			 * @since 2.0
			*/

			public function errorPerso($id = 0, $var = array()){
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->load(ERRORPERSO)){
					$nodeXml = $domXml->getElementsByTagName('errorperso')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('errors')->item(0);
					$nodeXml = $nodeXml->getElementsByTagName('error');

					foreach ($nodeXml as $key => $value) {
						if($value->hasAttribute('id') && $value->getAttribute('id') == $id){
							if(!$value->hasAttribute('template')){
								$this->_templatedefault = $this->_getTemplateDefault();
							}
							else{
								$this->_templatedefault = $value->getAttribute('template');
							}

							$this->_templateInstance = new template($this->_templatedefault, 'errorperso', 0, $this->_lang);
							$this->_templateInstance->setShow(false);

							$node2Xml = $value->getElementsByTagName('var');

							foreach ($node2Xml as $value2) {
								if($value2->hasAttribute('type') && $value2->hasAttribute('id'))
								switch($value2->getAttribute('type')){
									case 'var':
										$this->_templateInstance->assign($value2->getAttribute('id'), $value2->nodeValue);
									break;

									case 'lang':
										$this->_templateInstance->assign($value2->getAttribute('id'), $this->useLang($value2->nodeValue));
									break;
								}
							}

							foreach ($var as $ke2 => $value2) {
								switch($value2['type']){
									case 'var':
										$this->_templateInstance->assign($ke2, $value2['value']);
									break;

									case 'lang':
										$this->_templateInstance->assign($ke2, $this->useLang($value2['value']));
									break;
								}
							}

							return $this->_templateInstance->show();
						}
					}
				}
				else{
					$this->_addError('Le fichier de configuration des erreurs personnalisées '.ERRORPERSO.' est endommagé', __FILE__, __LINE__, FATAL);
					return false;
				}
			}

			protected function _getTemplateDefault(){
				$domXml = new \DomDocument('1.0', CHARSET);

				if($domXml->load(ERRORPERSO)){
					return $domXml->getElementsByTagName('errorperso')->item(0)
						->getElementsByTagName('config')->item(0)
						->getElementsByTagName('templatedefault')->item(0)
						->getAttribute('name');
				}
				else{
					$this->_addError('Le fichier de configuration des erreurs personnalisées '.ERRORPERSO.' est endommagé', __FILE__, __LINE__, FATAL);
					return false;
				}
			}

			protected function _createLangInstance(){
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}

			public  function __destruct(){

			}
		}
	}