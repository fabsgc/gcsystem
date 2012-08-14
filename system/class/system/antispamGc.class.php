<?php
	/**
	 * @file : antispamGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la lutte contre le spam par requête (IP)
	 * @version : 2.0 bêta
	*/
	
	class antispamGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait

		protected $_firewall                       ;
		
		public  function __construct($lang=NULL){
			if($lang==NULL){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();

			if(ANTISPAM == true){
				$this->_domXml = new DomDocument('1.0', CHARSET);
				
				if($this->_domXml->load(ASPAM)){
					$this->_addError('le fichier '.ASPAM.' a bien été chargé', __FILE__, __LINE__, INFORMATION);
					$this->_setConfigQuery();
					$this->_setConfigError();
					$this->_setIp();
				}
				else{
					$this->_addError('le fichier '.ASPAM.' n\'a pas pu être chargé', __FILE__, __LINE__, ERROR);
				}
			}
			else{
				$this->_addError('le mode antispam est désactivé.', __FILE__, __LINE__, WARNING);
			}
		}

		protected function _setConfigQuery(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('queryip')->item(0);

			$this->_firewall['antispams']['config']['queryip']['number'] = $this->_node2Xml->getAttribute('number');
			$this->_firewall['antispams']['config']['queryip']['duration'] = $this->_node2Xml->getAttribute('duration');
		}

		protected function _setConfigError(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('queryip')->item(0);

			$this->_firewall['antispams']['config']['queryip']['number'] = $this->_node2Xml->getAttribute('number');
			$this->_firewall['antispams']['config']['queryip']['duration'] = $this->_node2Xml->getAttribute('duration');
		}

		protected function _setIp(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);
			$ips = $this->_node2Xml->getElementsByTagName('ip');

			foreach ($ips as $cle => $val) {
				$this->_firewall['antispams']['ips'][$val->getAttribute('ip')]['number'] = $val->getAttribute('number');
				$this->_firewall['antispams']['ips'][$val->getAttribute('ip')]['since'] = $val->getAttribute('since');
				$this->_firewall['antispams']['ips'][$val->getAttribute('ip')]['ip'] = $val->getAttribute('ip');
			}
		}

		public function check(){
			if(isset($this->_firewall['antispams']['ips'][$this->getIp()])){
				print_r($this->_firewall);
			}
			else{
				$this->_setIpXml();
				return true;
			}
		}

		protected function _setIpXml(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);

			$this->_firewall['antispams']['ips'][$this->getIp()]['number'] = 1;
			$this->_firewall['antispams']['ips'][$this->getIp()]['since'] = time();
			$this->_firewall['antispams']['ips'][$this->getIp()]['type'] = $this->getIp();

			$this->_markupXml = $this->_domXml->createElement('ip');
			$this->_markupXml->setAttribute("ip", $this->getIp());
			$this->_markupXml->setAttribute("number", 1);
			$this->_markupXml->setAttribute("since", $this->_firewall['antispams']['ips'][$this->getIp()]['since']);
			$this->_node2Xml->appendChild($this->_markupXml);
			$this->_domXml->save(ASPAM);
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