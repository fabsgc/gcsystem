<?php
	/**
	 * @file : generalGc.class.php
	 * @author : fab@c++
	 * @description : traits
	 * @version : 2.0 bêta
	*/

	trait generalGc{
		public function windowInfo($Title, $Content, $Time, $Redirect, $lang="fr"){
			?>
				<link href="asset/css/default.css" rel="stylesheet" type="text/css" media="screen, print, handheld" />
			<?php
			$tpl = new templateGC(GCSYSTEM_PATH.'GCtplGc_windowInfo', 'tplGc_windowInfo', 0, $lang);
			
			$tpl->assign(array(
				'title'=>$Title,
				'content'=>$Content,
				'redirect'=>$Redirect,
				'time'=>$Time,
			));
				
			$tpl->show();
		}
		
		public function blockInfo($Title, $Content, $Time, $Redirect, $lang="fr"){
			$tpl = new templateGC(GCSYSTEM_PATH.'GCtplGc_blockInfo', 'tplGc_blockInfo', 0, $lang);
			
			$tpl->assign(array(
				'title'=>$Title,
				'content'=>$Content,
				'redirect'=>$Redirect,
				'time'=>$Time,
			));
				
			$tpl->show();
		}
		
		public function setErrorLog($file, $message){
			$file = fopen(LOG_PATH.$file.LOG_EXT, "a+");
			fputs($file, date("d/m/Y à H:i ! : ",time()).$message."\n");
		}
		
		public function sendMail($email, $message_html, $sujet, $envoyeur){
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)){
				$passage_ligne = "\r\n";
			}
			else{
				$passage_ligne = "\n";
			}
	 
			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			
			//=====Création du header de l'e-mail.
			$header = "From: \"".$envoyeur."\"<contact@legeekcafe.com>".$passage_ligne;
			$header.= "Reply-to: \"".$envoyeur."\" <contact@legeekcafe.com>".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
			 
			//=====Création du message.
			$message = $passage_ligne.$boundary.$passage_ligne;
			
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			
			//=====Envoi de l'e-mail.
			mail($email,$sujet,$message,$header);
			//==========		
		}
	}
	
	trait errorGc{
		protected $_error              = array() ; //array contenant toutes les erreurs enregistrées
		
		public function showError(){
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		protected function _addError($error){
			array_push($this->_error, $error);
		}
    }
	
	trait langInstance{
		protected $_lang                                        ; //gestion des langues via des fichiers XML
		protected $_langInstance                                ; //instance de la class langGc
		
		public function getLangClient(){
			if(!array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) || !$_SERVER['HTTP_ACCEPT_LANGUAGE'] ) { return DEFAULTLANG; }
			else{
				$langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
				$langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
				$langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
				return $langcode['0'];
			}
		}
    }