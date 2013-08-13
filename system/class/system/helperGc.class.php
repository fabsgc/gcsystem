<?php
	/**
	 * @file : helperGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les helpers ajoutés par le projet
	 * @version : 2.0 bêta
	*/
	
	class helperGc {
		use errorGc, domGc;                            //trait
		
		protected $_include   = array();
		
		public function __construct(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(HELPER)){
				$this->_nodeXml = $this->_domXml->getElementsByTagName('helpers')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('helper');

				foreach($this->_markupXml as $sentence){
					switch($sentence->getAttribute("type")){
						case 'helper':
							$this->_include[''.strval($sentence->getAttribute("name")).''] = array(
								'access' => CLASS_PATH.CLASS_HELPER_PATH.strval($sentence->getAttribute("access")),
								'enabled' => strval($sentence->getAttribute("enabled")),
								'include' => strval($sentence->getAttribute("include")));
						break;
						
						case 'lib':
							$this->_include[''.strval($sentence->getAttribute("name")).''] = array(
								'access' => LIB_PATH.strval($sentence->getAttribute("access")),
								'enabled' => strval($sentence->getAttribute("enabled")),
								'include' => strval($sentence->getAttribute("include")));
						break;
					}
				}
				
				foreach($this->_include as $cle => $val){
					if($val['enabled'] == 'true'){
						if($val['include'] == '*'){ //on inclut tout
							if(file_exists($val['access'])){
								require_once($val['access']);
								$this->_addError('Le helper '.$val['access'].' a bien été inclu.', __FILE__, __LINE__, INFORMATION);
							}
							else{
								$this->_addError('Le helper '.$val['access'].' est inaccessible.', __FILE__, __LINE__, ERROR);
							}
						}
						elseif(preg_match('#no\[(.*)\]#isU', $val['include'])){ //on vérifie si la rubrique n'en fait pas partie
							$rubrique = array();
							$rubrique = explode(',',  preg_replace('#no\[(.*)\]#isU', '$1', $val['include']));
							
							if(!in_array($_GET['rubrique'], $rubrique)){
								if(file_exists($val['access'])){
									require_once($val['access']);
								}
								else{
									$this->_addError('Le helper '.$val['access'].' est inaccessible.', __FILE__, __LINE__, ERROR);
								}
							}
						}
						elseif(preg_match('#yes\[(.*)\]#isU', $val['include'])){ //on vérifie si la rubrique en fait partie
							$rubrique = array();
							$rubrique = explode(',',  preg_replace('#yes\[(.*)\]#isU', '$1', $val['include']));
							
							if(in_array($_GET['rubrique'], $rubrique)){
								if(file_exists($val['access'])){
									require_once($val['access']);
								}
								else{
									$this->_addError('Le helper '.$val['access'].' est inaccessible.', __FILE__, __LINE__, ERROR);
								}
							}
						}
					}
				}
			}
		}
		
		public function __destruct(){
		}	
	}