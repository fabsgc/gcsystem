<?php
	/**
	 * @file : mailGc.class.php
	 * @author : fab@c++
	 * @description : class générant des mails
	 * @version : 2.0 bêta
	*/
	
	class mailGc extends constMime{
		use errorGc                                                                 ;
		
		protected $_passageLigne                = "\r\n"                            ; //passage à la ligne en fonction du destinataire
		protected $_expediteur                  = array('mail', 'mail@mail.com')    ; //nom de l'expediteur et email de l'expediteur
		protected $_reply                       = array('mail', 'mail@mail.com')    ; //email de reponse et email de reponse
		protected $_destinataire                = array('mail@mail.com')            ; //emails des destinataires
		protected $_message                     = array()                           ; //message
		protected $_piece                       = array()                           ; //liste des pièces jointes
		protected $_isPiece                     = false                             ; //le message contient des pièces jointes
		protected $_boundary                                                        ;
		protected $_boundaryAlt                                                     ;
		protected $_fileTransfert               = 'multipart/alternative'           ; //prévient du contenu qui va être envoyé
		protected $_priority                    = '3'                               ; //prévient du contenu qui va être envoyé
		protected $_charset                     = 'UTF-8'                           ; //charset à utiliser
		protected $_cc                          = array()                           ; //copie carbone
		protected $_bcc                         = array()                           ; //copie carbone invisible
		protected $_formatHtml                  = true                              ;
		protected $_reception                   = array()                           ;
		
		const MSG_TEMPLATE                      = 0                                 ;
		const MSG_TXT                           = 1                                 ;
		
		const PIECENAME                         = 'pièce jointe'                    ;
		
		const FORMATHTML                            = true                          ;
		const FORMATTEXT                            = false                         ;
		
		public  function __construct($infos = array()){
			foreach($infos as $cle => $info){
				switch($cle){
					case 'expediteur' :
						if(is_array($info)){
							$this->_expediteur = $info;
						}
						else{
							$this->_expediteur = array('mail', 'mail@mail.com');
						}
					break;
					
					case 'destinataire':
						if(is_array($info)){
							$this->_destinataire = $info;
						}
						else{
							array_push($this->_destinataire, $info);
						}
					break;
					
					case 'cc':
						if(is_array($info)){
							$this->_cc = $info;
						}
						else{
							array_push($this->_cc, $info);
						}
					break;
					
					case 'bcc':
						if(is_array($info)){
							$this->_bcc = $info;
						}
						else{
							array_push($this->_bcc, $info);
						}
					break;

					case 'reply':
						if(is_array($info)){
							$this->_reply = $info;
						}
						else{
							$this->_reply = array('mail', 'mail@mail.com');
						}
					break;
					
					case 'reception':
						if(is_array($info)){
							$this->_reception = $info;
						}
						else{
							array_push($this->_bcc, $info);
						}
					break;
					
					case 'sujet':
						$this->_sujet = $info;
					break;
					
					case 'charset':
						$this->_charset = $info;
					break;
					
					case 'priority':
						$this->_priority = intval($info);
					break;
					
					case 'format':
						switch($info){
							case 'html':
								$this->_formatHtml = true;
							break;
							
							case 'text':
								$this->_formatHtml = false;
							break;
							
							default:
								$this->_formatHtml = true;
							break;
						}
					break;
				}
			}
			
			$this->_boundary = '-----='.md5(rand());
			$this->_boundaryAlt = '-----='.md5(rand());
		}
		
		protected function _setPassageLigne($mail){
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){
				return  "\r\n";
			}
			else{
				return "\n";
			}
		}
		
		public function addText($texte){
			array_push($this->_message, $texte);
		}
		
		public function addTemplate($template, $vars = array(), $lang = DEFAULTLANG){
			$tpl = new templateGC($template, templatemail, '0', $this->_lang);
			
			foreach ($vars as $cle => $var){
				$tpl->assign(array(
					$cle => $var
				));
			}
			
			$tpl->setShow(false);
			
			$message = $tpl->show();
			array_push($this->_message, $message);
		}
		
		public function addFile($file, $name='pièce jointe', $mime = self::EXT_TXT){
			$this->_fileTransfert = 'multipart/mixed';
			$this->_isPiece = true;
			$file = new fileGc($file);
			
			if($file->iseReadable()){
				$message = $file->getFileContent();
			}
			else{
				$message = fileGc::NOREAD;
			}
			
			if($name == self::PIECENAME){ $name = $name.uniqid(); }
			if(isset($this->_piece[$name])){ $name = $name.uniqid();}
			
			$this->_piece[$name] = array(chunk_split(base64_encode($message)), $mime);
		}
		
		public function addDestinataire($destinataire){
			if(is_array($destinataire)){
				$this->_destinataire = $destinataire;
			}
			else{
				array_push($this->_destinataire, $destinataire);
			}
		}
		
		public function addCc($destinataire){
			if(is_array($destinataire)){
				$this->_cc = $destinataire;
			}
			else{
				array_push($this->_cc, $destinataire);
			}
		}
		
		public function addBcc($destinataire){
			if(is_array($destinataire)){
				$this->_bcc = $destinataire;
			}
			else{
				array_push($this->_bcc, $destinataire);
			}
		}
		
		public function send(){
			$contenu = '';
			//=====Envoi de l'e-mail.
			foreach($this->_message as $message){ $contenu .=$message; }
			
			foreach($this->_destinataire as $destinataire){
				$this->_passageLigne = $this->_setPassageLigne($destinataire);
				
				//=====Création du header de l'e-mail.
				$header = "From: \"".$this->_expediteur[0]."\" <".$this->_expediteur[1].">".$this->_passageLigne;
				$header.= "Reply-to: \"".$this->_reply[0]."\" <".$this->_reply[1].">".$this->_passageLigne;
				$header.= "MIME-Version: 1.0".$this->_passageLigne;
				foreach($this->_cc as $cc){
					$header.= "Cc: ".$cc."".$this->_passageLigne;
				}
				foreach($this->_bcc as $bcc){
					$header.= "Bcc: ".$bcc."".$this->_passageLigne;
				}
				foreach($this->_reception as $reception){
					$header.= "DispositionNotificationTo: ".$reception."".$this->_passageLigne;
				}
				$header.= "Content-Type: ".$this->_fileTransfert.";".$this->_passageLigne." boundary=\"".$this->_boundary."\"".$this->_passageLigne;
				//==========
				 
				//=====Création du message.
				$message = $this->_passageLigne."--".$this->_boundary.$this->_passageLigne;
				$message.= "Content-Type: multipart/alternative;".$this->_passageLigne." boundary=\"".$this->_boundaryAlt."\"".$this->_passageLigne;
				$message.= $this->_passageLigne."--".$this->_boundaryAlt.$this->_passageLigne;
				
				if($this->_formatHtml == self::FORMATHTML){
					$message.= "Content-Type: text/html; charset=\"".$this->_charset."\"".$this->_passageLigne;
					$message.= "Content-Transfer-Encoding: 8bit".$this->_passageLigne;
					$message.= $this->_passageLigne.$contenu.$this->_passageLigne;
				}
				elseif($this->_formatHtml == self::FORMATTEXT){
					$message.= "Content-Type: text/plain; charset=\"".$this->_charset."\"".$this->_passageLigne;
					$message.= "Content-Transfer-Encoding: 8bit".$this->_passageLigne;
					$message.= $this->_passageLigne.$contenu.$this->_passageLigne;
				}
				//==========
				 
				//=====On ferme la boundary alternative.
				$message.= $this->_passageLigne."--".$this->_boundaryAlt."--".$this->_passageLigne;
				//==========
				
				foreach($this->_piece as $cle => $valeur){
					$message.= $this->_passageLigne."--".$this->_boundary.$this->_passageLigne;
					$message.= "Content-Type: ".$valeur[1]."; name=\"".$cle."\"".$this->_passageLigne;
					$message.= "Content-Transfer-Encoding: base64".$this->_passageLigne;
					$message.= "Content-Disposition: attachment; filename=\"".$cle."\"".$this->_passageLigne;
					$message.= $this->_passageLigne.$valeur[0].$this->_passageLigne.$this->_passageLigne;
				}
				
				$message.= $this->_passageLigne."--".$this->_boundary."--".$this->_passageLigne; 
				
				mail($destinataire, $this->_sujet, $message, $header);
			}
			return true;
		}
		
		public  function __destruct(){
		}
	}