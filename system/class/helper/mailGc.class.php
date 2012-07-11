<?php
	/**
	 * @file : mailGc.class.php
	 * @author : fab@c++
	 * @description : class générant des mails
	 * @version : 2.0 bêta
	*/
	
	class mailGc{
		use errorGc;                           			    //trait
		
		protected $_passagLigne                            ; //passage à la ligne en fonction du destinataire
		protected $_destinataire                = array() ; //email du destinataire
		protected $_expediteur                            ; //email de l'expediteur
		protected $_message                     = array() ; //message
		protected $_piece                       = array() ; //liste des pièces jointes
		
		public  function __construct($infos = array()){
			foreach($infos as $info){
				switch($info){
					case 'mail_expediteur' :
					break;
					
					case 'mail_destinataire':
					break;
					
					case 'mail_destinataire':
					break;
					
					case 'mail_format':
					break;
				}
			}
		}
		
		protected function _setPassageLigne(){
		
		}
		
		public  function __desctuct(){
		
		}
	}
?>