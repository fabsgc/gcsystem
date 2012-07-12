<?php
	/**
	 * @file : mailGc.class.php
	 * @author : fab@c++
	 * @description : class générant des mails
	 * @version : 2.0 bêta
	*/
	
	class mailGc{
		use errorGc;                           			    //trait
		
		protected $_passagLigne                              ; //passage à la ligne en fonction du destinataire
		protected $_expediteur                  = array()    ; //nom de l'expediteur et email de l'expediteur
		protected $_reply                       = array()    ; //email de reponse et email de reponse
		protected $_destinataire                = array()    ; //emails des destinataires
		protected $_format                                   ; //format, html ou texte
		protected $_message                     = array()    ; //message
		protected $_piece                       = array()    ; //liste des pièces jointes
		
		const FORMATHTML                        ='text/html' ;
		const FORMATTXT                         ='text/plain';
		
		public  function __construct($infos = array()){
			foreach($infos as $cle => $info){
				switch($cle){
					case 'expediteur' :
					break;
					
					case 'destinataire':
					break;
					
					case 'reply':
						if(is_array($info)){
							$this->_reply = $info;
						}
						else{
							$this->_reply = array('mail', 'mail@mail.com');
						}
					break;
					
					case 'sujet':
						$this->_sujet = $info;
					break;
					
					case 'format':
						switch($info){
							case 'html' :
								$this->_format = self::FORMATHTML;
							break;
							
							case 'texte' :
								$this->_format = self::FORMATTXT;
							break;
							
							default:
								$this->_format = self::FORMATTXT;
							break;
						}
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