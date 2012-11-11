<?php
	/**
	 * @file : errorPersoGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les erreurs personnalisées de façon plus propre et plus simple
	 * @version : 2.0 bêta
	*/

	class errorPersoGc{
		use errorGc, langInstance, domGc;                                //trait

		protected                $_templateInstance = '';
		protected                $_templatedefault = '' ;

		public  function __construct($lang=""){
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
		}

		public function errorPerso($id = '0', $var = array()){
			$this->_domXml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->load(ERRORPERSO)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('errorperso')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('errors')->item(0);
				$this->_nodeXml = $this->_nodeXml->getElementsByTagName('error');

				foreach ($this->_nodeXml as $key => $value) {
					if($value->hasAttribute('id') && $value->getAttribute('id') == $id){
						if(!$value->hasAttribute('template')){
							$this->_templatedefault = $this->_getTemplateDefault();
						}
						else{
							$this->_templatedefault = $value->getAttribute('template');
						}

						$this->_templateInstance = new templateGc($this->_templatedefault, 'errorperso', 0, $this->_lang);
						$this->_templateInstance->setShow(false);

						$this->_node2Xml = $value->getElementsByTagName('var');

						foreach ($this->_node2Xml as $key2 => $value2) {
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
				$this->_addError('Le fichier de configuration des erreurs personnalisées '.ERRORPERSO.' est endommagé', __FILE__, __LINE__, ERROR);
				return false;
			}
		}

		protected function _getTemplateDefault(){
			$this->_domXml = new DomDocument('1.0', CHARSET);

			if($this->_domXml->load(ERRORPERSO)){
				return $this->_domXml->getElementsByTagName('errorperso')->item(0)
					->getElementsByTagName('config')->item(0)
					->getElementsByTagName('templatedefault')->item(0)
					->getAttribute('nom');
			}
			else{
				$this->_addError('Le fichier de configuration des erreurs personnalisées '.ERRORPERSO.' est endommagé', __FILE__, __LINE__, ERROR);
				return false;
			}
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