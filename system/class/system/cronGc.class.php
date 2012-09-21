<?php
	/**
	 * @file : cronGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les fichiers crons
	 * @version : 2.0 bêta
	*/
	
	class cronGc {
		use errorGc, domGc;                            //trait
		
		public function __construct(){
			$this->_domXml = new DomDocument('1.0', CHARSET);
			if($this->_domXml->load(CRON)){				
				$this->_nodeXml = $this->_domXml->getElementsByTagName('crons')->item(0);
				$this->_markupXml = $this->_nodeXml->getElementsByTagName('cron');

				foreach($this->_markupXml as $sentence){
					if ($sentence->getAttribute("executed") < (time + $sentence->getAttribute("time"))){
						
					}
				}
			}
		}
		
		public function __destruct(){
		}	
	}