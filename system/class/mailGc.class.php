<?php
	/*\
	 | ------------------------------------------------------
	 | @file : mailGc.class.php
	 | @author : fab@c++
	 | @description : class générant des mails
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class mailGc{
		use errorGc;                           			    //trait fonctions génériques
		
		protected $_destinataire                          ; //email du destinataire
		protected $_message                               ; //message
		protected $_piece                       = array() ; //liste des pièces jointes
		
		public  function __construct(){
		}
		
		public  function __desctuct(){
		
		}
	}
?>