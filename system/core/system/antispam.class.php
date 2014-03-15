<?php
	/**
	 * @file : antispam.class.php
	 * @author : fab@c++
	 * @description : class gérant la lutte contre le spam par requête (IP)
	 * @version : 2.2 bêta
	*/
	
	namespace system{
		class antispam{
			use error, langInstance, general;

			protected $_antispam                       ;
			protected $_xmlValid                = true ; //il peut arriver que le fichier soit endommagé, dans ce cas, on bloque le système
			protected $_domXml                         ;
			protected $_exception               = false;
			
			/**
			 * Crée l'instance de la classe
			 * @access public
			 * @return void
			 * @since 2.0
			*/

			public  function __construct($lang=NULL){
				if($lang==NULL){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->_createLangInstance();

				if(@fopen(ASPAM, 'r+')) {
					if(ANTISPAM == true){
						$this->_domXml = new \DomDocument('1.0', CHARSET);
					
						if($this->_domXml->load(ASPAM)){
							if($this->_exception() == false){
								flock($fp, LOCK_EX);
								$this->_addError('le fichier '.ASPAM.' a bien été chargé', __FILE__, __LINE__, INFORMATION);
								$this->_setConfigQuery();
								$this->_setConfigError();
								$this->_setIp();
								flock($fp, LOCK_UN);
							}
							else{
								$this->_addError('la page appelée est une exception', __FILE__, __LINE__, INFORMATION);
								$this->_exception = true;
							}
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
				else{
					$this->_addError('l\'antispam est en cours de lecture.', __FILE__, __LINE__, WARNING);
				}
			}

			protected function _exception(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('config')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('exceptions')->item(0);
			
				$markup3Xml = $markupXml->getElementsByTagName('exception');

				foreach ($markup3Xml as $cle => $val) {
					if($_GET['controller'] == $val->getAttribute('controller') && $_GET['action'] == $val->getAttribute('action')){
						return true;
					}
				}

				return false;
			}

			protected function _setConfigQuery(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('queryip')->item(0);

				$this->_antispam['antispams']['config']['queryip']['number'] = $node2Xml->getAttribute('number');
				$this->_antispam['antispams']['config']['queryip']['duration'] = $node2Xml->getAttribute('duration');
			}

			protected function _setConfigError(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('config')->item(0);
				$markupXml = $node2Xml->getElementsByTagName('error')->item(0);
				$this->_antispam['antispams']['config']['error']['template']['src'] = $markupXml->getAttribute('template');
			
				$markup3Xml = $markupXml->getElementsByTagName('variable');

				foreach ($markup3Xml as $cle => $val) {
					$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['type'] = $val->getAttribute('type');
					$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['name'] = $val->getAttribute('name');
					$this->_antispam['antispams']['config']['error']['template']['variable'][$val->getAttribute('name')]['value'] = $val->getAttribute('value');
				}
			}

			protected function _setIp(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('ips')->item(0);
				$ips = $node2Xml->getElementsByTagName('ip');

				foreach ($ips as $cle => $val) {
					$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['number'] = $val->getAttribute('number');
					$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['since'] = $val->getAttribute('since');
					$this->_antispam['antispams']['ips'][$val->getAttribute('ip')]['ip'] = $val->getAttribute('ip');
				}
			}

			public function check(){
				if($this->_exception == false){
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
									$t = new template($this->_antispam['antispams']['config']['error']['template']['src'], 'GCantispamerror', 0);		
									foreach($this->_antispam['antispams']['config']['error']['template']['variable'] as $cle => $val){
										if($val['type'] == 'var'){
											$t->assign(array($val['name']=>$val['value']));
										}
										else{
											$t->assign(array($val['name']=>$this->useLang($val['value'])));
										}
									}
									$t -> show();

									$this->_addError($this->getIp() .' : L\'IP  a dépassé le nombre de requêtes autorisée sur une période donnée pour la page '.$_GET['controller'].'/'.$_GET['action'], __FILE__, __LINE__, ERROR);
									return false;
								}
							}
							
						}
						else{
							$this->_setIpXml();
							return true;
						}
					}
					else{
						return true;
					}
				}
				else{
					return true;
				}
			}

			protected function _setIpXml(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('ips')->item(0);

				$this->_antispam['antispams']['ips'][$this->getIp()]['number'] = 1;
				$this->_antispam['antispams']['ips'][$this->getIp()]['since'] = time();
				$this->_antispam['antispams']['ips'][$this->getIp()]['type'] = $this->getIp();

				$markupXml = $this->_domXml->createElement('ip');
				$markupXml->setAttribute("ip", $this->getIp());
				$markupXml->setAttribute("number", 1);
				$markupXml->setAttribute("since", $this->_antispam['antispams']['ips'][$this->getIp()]['since']);
				$node2Xml->appendChild($markupXml);
				$this->_domXml->save(ASPAM);
			}

			protected function _updateIpXml(){
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('ips')->item(0);
				$sentences = $node2Xml->getElementsByTagName('ip');

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
				$nodeXml = $this->_domXml->getElementsByTagName('antispams')->item(0);
				$node2Xml = $nodeXml->getElementsByTagName('ips')->item(0);
				$sentences = $node2Xml->getElementsByTagName('ip');

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
				$this->_langInstance = new lang($this->_lang);
			}
			
			public function useLang($sentence, $var = array(), $template = lang::USE_NOT_TPL){
				return $this->_langInstance->loadSentence($sentence, $var, $template);
			}

			/**
			 * Desctructeur
			 * @access public
			 * @since 2.0
			*/
			
			public  function __destruct(){
			}
		}
	}