<?php
	/**
	 * @file : antispamGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la lutte contre le spam par requête (IP)
	 * @version : 2.0 bêta
	*/
	
	class antispamGc{
		use errorGc, langInstance, domGc, generalGc;                  //trait

		protected $_antispam                       ;
		protected $_xmlValid                = true ; //il peut arriver que le fichier soit endommage, dans ce cas, on bloque le systeme
		
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
					$this->_xmlValid = false;
					$this->_addError('le fichier '.ASPAM.' n\'a pas pu être chargé', __FILE__, __LINE__, ERROR);
				}
			}
			else{
				$this->_addError('le mode antispam est désactivé.', __FILE__, __LINE__, WARNING);
			}
		}

		public function getAntispamArray(){
			if(isset($this->_antispam['antispams'])){
				return $this->_antispam['antispams'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfig(){
			if(isset($this->_antispam['antispams']['config'])){
				return $this->_antispam['antispams']['config'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigQueryIp(){
			if(isset($this->_antispam['antispams']['config']['queryip'])){
				return $this->_antispam['config']['queryip'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigQueryIpNumber(){
			if(isset($this->_antispam['antispams']['config']['queryip']['number'])){
				return $this->_antispam['antispams']['config']['queryip']['number'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigQueryIpDuration(){
			if(isset($this->_antispam['antispams']['config']['queryip']['duration'])){
				return $this->_antispam['antispams']['antispams']['config']['queryip']['duration'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigError(){
			if(isset($this->_antispam['antispams']['config']['error'])){
				return $this->_antispam['antispams']['config']['error'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplate(){
			if(isset($this->_antispam['antispams']['config']['error']['template'])){
				return $this->_antispam['antispams']['config']['error']['template'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateSrc(){
			if(isset($this->_antispam['antispams']['config']['error']['template']['src'])){
				return $this->_antispam['antispams']['config']['error']['template']['src'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateVars(){
			if(isset($this->_antispam['antispams']['config']['error']['template']['variable'])){
				return $this->_antispam['antispams']['config']['error']['template']['variable'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateVar($nom){
			if(isset($this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.''])){
				return $this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.''];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateVarType($nom){
			if(isset($this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['type'])){
				return $this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['type'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateVarName($nom){
			if(isset($this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['name'])){
				return $this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['name'];
			}
			else{
				return false;
			}
		}

		public function getAntispamConfigErrorTemplateVarValue($nom){
			if(isset($this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['value'])){
				return $this->_antispam['antispams']['config']['error']['template']['variable'][''.$nom.'']['value'];
			}
			else{
				return false;
			}
		}

		public function getAntispamIps(){
			if(isset($this->_antispam['antispams']['ips'])){
				return $this->_antispam['antispams']['ips'];
			}
			else{
				return false;
			}
		}

		public function getAntispamIp($nom){
			if(isset($this->_antispam['antispams']['ips'][''.$nom.''])){
				return $this->_antispam['antispams']['ips'][''.$nom.''];
			}
			else{
				return false;
			}
		}

		public function getAntispamIpNumber($nom){
			if(isset($this->_antispam['antispams']['ips'][''.$nom.'']['number'])){
				return $this->_antispam['antispams']['ips'][''.$nom.'']['number'];
			}
			else{
				return false;
			}
		}

		public function getAntispamIpSince($nom){
			if(isset($this->_antispam['antispams']['ips'][''.$nom.'']['since'])){
				return $this->_antispam['antispams']['ips'][''.$nom.'']['since'];
			}
			else{
				return false;
			}
		}

		public function getAntispamIpIp($nom){
			if(isset($this->_antispam['antispams']['ips'][''.$nom.'']['ip'])){
				return $this->_antispam['antispams']['ips'][''.$nom.'']['ip'];
			}
			else{
				return false;
			}
		}

		protected function _setConfigQuery(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('queryip')->item(0);

			$this->_antispam['antispams']['config']['queryip']['number'] = $this->_node2Xml->getAttribute('number');
			$this->_antispam['antispams']['config']['queryip']['duration'] = $this->_node2Xml->getAttribute('duration');
		}

		protected function _setConfigError(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('config')->item(0);
			$this->_markupXml = $this->_node2Xml->getElementsByTagName('error')->item(0);
			$this->_antispam['antispams']['config']['error']['template']['src'] = $this->_markupXml->getAttribute('template');
		
			$this->_markup3Xml = $this->_markupXml->getElementsByTagName('variable');

			foreach ($this->_markup3Xml as $cle => $val) {
				$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['type'] = $val->getAttribute('type');
				$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['name'] = $val->getAttribute('name');
				$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['value'] = $val->getAttribute('value');
			}
		}

		protected function _setIp(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);
			$ips = $this->_node2Xml->getElementsByTagName('ip');

			foreach ($ips as $cle => $val) {
				$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['number'] = $val->getAttribute('number');
				$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['since'] = $val->getAttribute('since');
				$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['ip'] = $val->getAttribute('ip');
			}
		}

		public function check(){
			if($this->_xmlValid == true){
				if(isset($this->_antispam['antispams']['ips'][$this->getIp()])){
					if(($this->_antispam['antispams']['ips'][$this->getIp()]['since'] + $this->_antispam['antispams']['config']['queryip']['duration'] < time())){
						$this->_updateIpXml();

						return true;
					}
					else{
						if($this->_antispam['antispams']['ips'][$this->getIp()]['number'] < $this->_antispam['antispams']['config']['queryip']['number']){
							$this->_updateNumberXml($this->_antispam['antispams']['ips'][$this->getIp()]['number']+1, $this->_antispam['antispams']['ips'][$this->getIp()]['since']);

							return true;
						}
						else{
							$t = new templateGc($this->_antispam['antispams']['config']['error']['template']['src'], 'GCantispamerror', 0);		
							foreach($this->_antispam['antispams']['config']['error']['template']['variable'] as $cle => $val){
								if($val['type'] == 'var'){
									$t->assign(array($val['name']=>$val['value']));
								}
								else{
									$t->assign(array($val['name']=>$this->useLang($val['value'])));
								}
							}
							$t -> show();

							$this->_addError($this->getIp() .' : L\'IP  a dépassé le nombre de requêtes autorisée sur une période donnée pour la page '.$_GET['rubrique'].'/'.$_GET['action'], __FILE__, __LINE__, ERROR);
							return false;
						}
					}
					
				}
				else{
					$this->_setIpXml();
					return true;
				}
			}
		}

		protected function _setIpXml(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);

			$this->_antispam['antispams']['ips'][$this->getIp()]['number'] = 1;
			$this->_antispam['antispams']['ips'][$this->getIp()]['since'] = time();
			$this->_antispam['antispams']['ips'][$this->getIp()]['type'] = $this->getIp();

			$this->_markupXml = $this->_domXml->createElement('ip');
			$this->_markupXml->setAttribute("ip", $this->getIp());
			$this->_markupXml->setAttribute("number", 1);
			$this->_markupXml->setAttribute("since", $this->_antispam['antispams']['ips'][$this->getIp()]['since']);
			$this->_node2Xml->appendChild($this->_markupXml);
			$this->_domXml->save(ASPAM);
		}

		protected function _updateIpXml(){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);
			$sentences = $this->_node2Xml->getElementsByTagName('ip');

			$this->_antispam['antispams']['ips'][$this->getIp()]['number'] = 1;
			$this->_antispam['antispams']['ips'][$this->getIp()]['since'] = time();
			$this->_antispam['antispams']['ips'][$this->getIp()]['type'] = $this->getIp();

			foreach($sentences as $sentence){
				if ($sentence->getAttribute("ip") == $this->getIp()){
					$sentence->setAttribute("ip", $this->getIp());
					$sentence->setAttribute("number", 1);
					$sentence->setAttribute("since", $this->_antispam['antispams']['ips'][$this->getIp()]['since']);
				}
			}
			$this->_domXml->save(ASPAM);
		}

		protected function _updateNumberXml($number, $time){
			$this->_nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
			$this->_node2Xml = $this->_nodeXml->getElementsByTagName('ips')->item(0);
			$sentences = $this->_node2Xml->getElementsByTagName('ip');

			$this->_antispam['antispams']['ips'][$this->getIp()]['number'] = $number;
			$this->_antispam['antispams']['ips'][$this->getIp()]['since'] = $time;
			$this->_antispam['antispams']['ips'][$this->getIp()]['type'] = $this->getIp();

			foreach($sentences as $sentence){
				if ($sentence->getAttribute("ip") == $this->getIp()){
					$sentence->setAttribute("ip", $this->getIp());
					$sentence->setAttribute("number", $number);
					$sentence->setAttribute("since", $time);
				}
			}
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