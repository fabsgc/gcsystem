<?php
	/*\
	 | ------------------------------------------------------
	 | @file : mail.class.php
	 | @author : fab@c++
	 | @description : class gérant l'envoi de mails
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace helper{
		class mail extends \system\constMime{
			use \system\error;
			
			protected $_backLine                    = "\r\n"                            ; //passage à la ligne en fonction du destinataire
			protected $_sender                      = array('mail', 'mail@mail.com')    ; //nom de l'expediteur et email de l'expediteur
			protected $_reply                       = array('mail', 'mail@mail.com')    ; //email de reponse et email de reponse
			protected $_receiver                    = array()                           ; //emails des destinataires
			protected $_message                     = array()                           ; //message
			protected $_attachment                  = array()                           ; //liste des pièces jointes
			protected $_isAttachment                = false                             ; //le message contient des pièces jointes
			protected $_boundary                                                        ;
			protected $_boundaryAlt                                                     ;
			protected $_fileTransfert               = 'multipart/alternative'           ; //prévient du contenu qui va être envoyé
			protected $_priority                    = '3'                               ; //prévient du contenu qui va être envoyé
			protected $_charset                     = 'UTF-8'                           ; //charset à utiliser
			protected $_cc                          = array()                           ; //copie carbone
			protected $_bcc                         = array()                           ; //copie carbone invisible
			protected $_formatHtml                  = true                              ;
			protected $_reception                   = array()                           ;
			protected $_lang                        = ''                                ;
			
			const MSG_TEMPLATE                      = 0                                 ;
			const MSG_TXT                           = 1                                 ;
			
			const PIECENAME                         = 'attachment'                      ;
			
			const FORMATHTML                            = true                          ;
			const FORMATTEXT                            = false                         ;
			
			/**
			 * Constructeur de la classe. Initialisation des paramètres
			 * @access	public
			 * @param $info array
			 *		sender : l'expéditeur du mail : array(nom, email),
			 *		receiver : les destinataires du mail : string/array : email ou array(email, email, email),
			 *		cc : Idem,
			 *		bcc : Idem,
			 *		reply : adresse de réponse : array(nom, email),
			 *		reception : adresse de confirmation de lecture : string
			 *		charset : encodage,
			 *		priority : priorité : 1 à 5
			 *		subject : sujet du message : string,
			 *		format : html/txt
			 * @since 2.0
			*/

			public  function __construct($infos = array()){
				foreach($infos as $cle => $info){
					switch($cle){
						case 'sender' :
							if(is_array($info)){
								$this->_sender = $info;
							}
							else{
								$this->_sender = array($info, $info);
							}
						break;
						
						case 'receiver':
							array_push($this->_receiver, $info);
						break;
						
						case 'cc':
							array_push($this->_cc, $info);
						break;
						
						case 'bcc':
							array_push($this->_bcc, $info);
						break;

						case 'reply':
							if(is_array($info)){
								$this->_reply = $info;
							}
							else{
								$this->_reply = array($info, $info);
							}
						break;
						
						case 'reception':
							$this->_reception = $info;
						break;
						
						case 'subject':
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
			
			/**
			 * Modifie le caractère de retour à la ligne en fonction du mail entré en paramètre
			 * @access	protected
			 * @param $mail string : mail
			 * @return string
			 * @since 2.0
			*/

			protected function _setBackLine($mail){
				if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){
					return  "\r\n";
				}
				else{
					return "\n";
				}
			}
			
			/**
			 * Permet d'ajouter du texte au message. Le texte est ajouté à la suite à chaque fois
			 * @access	public
			 * @param $text string : message à ajouter
			 * @return void
			 * @since 2.0
			*/

			public function addText($text){
				array_push($this->_message, $text);
			}
			
			/**
			 * Ajouter un template au message
			 * @access	public
			 * @param $template string : chemin vers le fichier de template
			 * @param $vars array : liste des variables
			 * @param $lang string : langue à utiliser
			 * @return void
			 * @since 2.0
			*/

			public function addTemplate($template, $vars = array(), $lang = DEFAULTLANG){
				$tpl = new \system\template($template, 'templatemail', '0', $lang);
				
				foreach ($vars as $cle => $var){
					$tpl->assign(array(
						$cle => $var
					));
				}
				
				$tpl->setShow(false);
				
				$message = $tpl->show();
				array_push($this->_message, $message);
			}
			
			/**
			 * Ajouter un fichier en pièce jointe
			 * @access	public
			 * @param $file string : chemin vers le fichier sur le serveur
			 * @param $name string : nom du fichier
			 * @param $mime string : type mime
			 * @return void
			 * @since 2.0
			*/

			public function addFile($file, $name='attachment', $mime = self::EXT_TXT){
				$this->_fileTransfert = 'multipart/mixed';
				$this->_isAttachment = true;
				$file = new file($file);

				if($file->isReadable()){
					$message = $file->getFileContent();
				}
				else{
					$message = file::NOREAD;
				}
				
				if($name == self::PIECENAME){ $name = $name.uniqid(); }
				if(isset($this->_attachment[$name])){ $name = $name.uniqid();}
				
				$this->_attachment[$name] = array(chunk_split(base64_encode($message)), $mime);
			}
			
			/**
			 * Ajoute des destinataires
			 * @access	public
			 * @param $receiver string/array : les destinataires du mail : email ou array(email, email, email),
			 * @return void
			 * @since 2.0
			*/

			public function addReceiver($receiver){
				array_push($this->_receiver, $receiver);
			}
			
			/**
			 * Ajoute des cc
			 * @access	public
			 * @param $cc string/array : les cc du mail : email ou array(email, email, email),
			 * @return void
			 * @since 2.0
			*/

			public function addCc($cc){
				array_push($this->_cc, $cc);
			}
			
			/**
			 * Ajoute des bcc
			 * @access	public
			 * @param $bcc string/array : les bcc du mail : email ou array(email, email, email),
			 * @return void
			 * @since 2.0
			*/

			public function addBcc($bcc){
				array_push($this->_bcc, $bcc);
			}
			
			/**
			 * Envoi du mail
			 * @access	public
			 * @return bool
			 * @since 2.0
			*/

			public function send(){
				$contenu = '';
				//=====Envoi de l'e-mail.
				foreach($this->_message as $message){ 
					$contenu .=$message; 
				}
				
				foreach($this->_receiver as $receiver){
					$this->_backLine = $this->_setBackLine($receiver);
					
					//=====Création du header de l'e-mail.
					$header = "From: \"".$this->_sender[0]."\" <".$this->_sender[1].">".$this->_backLine;
					$header.= "Reply-to: \"".$this->_reply[0]."\" <".$this->_reply[1].">".$this->_backLine;
					$header.= "MIME-Version: 1.0".$this->_backLine;
					foreach($this->_cc as $cc){
						$header.= "Cc: ".$cc."".$this->_backLine;
					}
					foreach($this->_bcc as $bcc){
						$header.= "Bcc: ".$bcc."".$this->_backLine;
					}
					foreach($this->_reception as $reception){
						$header.= "DispositionNotificationTo: ".$reception."".$this->_backLine;
					}
					$header.= "Content-Type: ".$this->_fileTransfert.";".$this->_backLine." boundary=\"".$this->_boundary."\"".$this->_backLine;
					//==========
					 
					//=====Création du message.
					$message = $this->_backLine."--".$this->_boundary.$this->_backLine;
					$message.= "Content-Type: multipart/alternative;".$this->_backLine." boundary=\"".$this->_boundaryAlt."\"".$this->_backLine;
					$message.= $this->_backLine."--".$this->_boundaryAlt.$this->_backLine;
					
					if($this->_formatHtml == self::FORMATHTML){
						$message.= "Content-Type: text/html; charset=\"".$this->_charset."\"".$this->_backLine;
						$message.= "Content-Transfer-Encoding: 8bit".$this->_backLine;
						$message.= $this->_backLine.$contenu.$this->_backLine;
					}
					elseif($this->_formatHtml == self::FORMATTEXT){
						$message.= "Content-Type: text/plain; charset=\"".$this->_charset."\"".$this->_backLine;
						$message.= "Content-Transfer-Encoding: 8bit".$this->_backLine;
						$message.= $this->_backLine.$contenu.$this->_backLine;
					}
					//==========
					 
					//=====On ferme la boundary alternative.
					$message.= $this->_backLine."--".$this->_boundaryAlt."--".$this->_backLine;
					//==========
					
					foreach($this->_attachment as $cle => $valeur){
						$message.= $this->_backLine."--".$this->_boundary.$this->_backLine;
						$message.= "Content-Type: ".$valeur[1]."; name=\"".$cle."\"".$this->_backLine;
						$message.= "Content-Transfer-Encoding: base64".$this->_backLine;
						$message.= "Content-Disposition: attachment; filename=\"".$cle."\"".$this->_backLine;
						$message.= $this->_backLine.$valeur[0].$this->_backLine.$this->_backLine;
					}
					
					$message.= $this->_backLine."--".$this->_boundary."--".$this->_backLine; 
					
					mail($receiver, $this->_sujet, $message, $header);
				}
				return true;
			}
			
			public  function __destruct(){
			}
		}
	}