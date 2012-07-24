<?php
	/**
	 * @file : configGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le fichier de config de l'application
	 * @version : 2.0 bêta
	*/
	
	class configGc {
		use errorGc;                            //trait
		
		protected $_domXml             ;
		protected $_nodeXml            ;
		protected $_markupXml          ;
		
		public function __construct(){
			$this->_domXml = new DomDocument('1.0', 'iso-8859-15');
			if($this->_domXml->load(APPCONFIG)){
				$this->_addError('fichier ouvert : '.ROUTE);
				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('definitions')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('define');

				foreach($this->_markupXml as $sentence){
					if (!defined(strtoupper(strval($sentence->getAttribute("id"))))){
						define('USER_'.strtoupper(strval($sentence->getAttribute("id"))).'', strval($sentence->getAttribute("value")));
					}
				}
			}
			else{
				$this->_addError('Le fichier '.APPCONFIG.' n\'a pas pu être ouvert');
			}
		}
		
		public function __destruct(){
		}	
	}
?>