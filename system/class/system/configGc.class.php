<?php
	/**
	 * @file : configGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le fichier de config de l'application
	 * @version : 2.0 bêta
	*/
	
	class configGc {
		use errorGc, domGc;                            //trait
		
		public function __construct(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(APPCONFIG)){				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('definitions')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('define');

				foreach($this->_markupXml as $sentence){
					if (!defined(strtoupper(CONST_APP_PREFIXE.strval($sentence->getAttribute("id"))))){
						define(CONST_APP_PREFIXE.strtoupper(strval($sentence->getAttribute("id"))).'', strval($sentence->getAttribute("value")));
					}
				}
			}
		}
		
		public function __destruct(){
		}	
	}