<?php
	/**
	 * @file : generalGc.class.php
	 * @author : fab@c++
	 * @description : traits
	 * @version : 2.0 bêta
	*/

	trait generalGc{
		final protected function setErrorLog($file, $message){
			if(LOG_ENABLED == true){
				$file = fopen(LOG_PATH.$file.LOG_EXT, "a+");
				fputs($file, date("d/m/Y \a H:i:s ! : ",time()).$message."\n");
			}
		}
		
		final protected function sendMail($email, $message_html, $sujet, $envoyeur){
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
			return mail($email,$sujet,$message,$header);
			//==========		
		}
		
		final protected function getIp(){
			return $_SERVER['REMOTE_ADDR'];
		}
	
		final protected function getQuery(){
			return $_SERVER['QUERY_STRING'];
		}
		
		final protected function getPhpSelf(){
			return $_SERVER['PHP_SELF'];
		}
		
		final protected function getHost(){
			return $_SERVER['HTTP_HOST'];
		}
		
		final protected function getUri(){
			return $_SERVER['REQUEST_URI'];
		}
		
		final protected function getReferer(){
			if(isset($_SERVER['HTTP_REFERER'])){
				return $_SERVER['HTTP_REFERER'];
			}
			else{
				return false;
			}
		}
		
		final protected function getServerName(){
			return $_SERVER['SERVER_NAME'];
		}
		
		final protected function addHeader($header){
            header($header);
        }

        final protected function errorHttp($error, $titre){
        	$t= new templateGC(ERRORDOCUMENT_PATH.'httpError', $error, '0', $this->_lang);
        	$t->setShow(false);
			$t->assign(array(
				'url' => substr($this->getUri(), strlen(FOLDER), strlen($this->getUri())),
				'message' => $titre
			));
			return $t->show();
        }
		
		final protected function redirect404(){
			$this->addHeader('HTTP/1.1 404 Not Found');
			echo $this->errorHttp('404', $this->useLang('404'));
        }
		
		final protected function redirect500(){
			$this->addHeader('HTTP/1.1 500 internal error');
			echo $this->errorHttp('500', $this->useLang('500'));
			exit();
        }
		
		final protected function redirect403(){
			$this->addHeader('HTTP/1.1 403 Access Forbidden');
			echo $this->errorHttp('403', $this->useLang('403'));
			exit();
        }
	}
	
	trait errorGc{
		protected $_error              = array() ; //array contenant toutes les erreurs enregistrées
		
		final protected function showError(){
			$erreur = "";
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}

		final protected function _addError($error, $fichier = __FILE__, $ligne = __LINE__, $type = INFORMATION){
			if(LOG_ENABLED == true){
				array_push($this->_error, $error);
				$file = fopen(LOG_PATH.LOG_SYSTEM.LOG_EXT, "a+");
				fputs($file, date("d/m/Y \a H:i:s ! : ",time()).'['.$type.'] fichier '.$fichier.' ligne '.$ligne.' '.$error."\n");
				fclose($file);
			}
		}

		final protected function _addErrorHr(){
			if(LOG_ENABLED == true){
				$file = fopen(LOG_PATH.LOG_SYSTEM.LOG_EXT, "a+");
				fputs($file, "##### END OF EXECUTION ####################################################################################################\n");
				fclose($file);
			}
		}
    }
	
	trait langInstance{
		protected $_lang                              = 'fr'    ; //gestion des langues via des fichiers XML
		protected $_langInstance                                ; //instance de la class langGc
		
		final protected function getLangClient(){
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
	
	trait urlRegex{
		final protected function getUrl($id, $var = array()){
			if(REWRITE == true){
				$domXml = new DomDocument('1.0', 'iso-8859-15');
				if($domXml->load(ROUTE)){
					$this->_addError('url "'.$id.'" | fichier ouvert : '.ROUTE, __FILE__, __LINE__, INFORMATION);
				
					$nodeXml = $domXml->getElementsByTagName('routes')->item(0);
					$markupXml = $nodeXml->getElementsByTagName('route');
					
					$rubrique = "";
					$result   = "";
					
					foreach($markupXml as $sentence){	
						if ($sentence->getAttribute("id") == $id){
							$url = preg_replace('#\((.*)\)#isU', '<($1)>',  $sentence->getAttribute("url"));
							$urls = explode('<', $url);
							$i=0;
							foreach($urls as $url){
								if(preg_match('#\)>#', $url)){
									$result.= preg_replace('#\((.*)\)>#U', $var[$i], $url);
									$i++;
								}
								else{
									$result.=$url;
								}
							}

							$result = preg_replace('#\\\.#U', '.', $result);
							return FOLDER.$result;
						}
					}
				}
				else{
					$this->_addError('Le fichier '.ROUTE.' n\'a pas pu être ouvert', __FILE__, __LINE__, ERROR);
				}
			}
			else{
				$url = preg_replace('#\((.*)\)#isU', '<($1)>',  $regex);
				$urls = explode('<', $url);
				$i=0;

				foreach($urls as $url){
					if(preg_match('#\)>#', $url)){
					$result.= preg_replace('#\((.*)\)>#U', $var[$i], $url);
					$i++;
					}
					else{
						$result.=$url;
					}
				}

			 	$result = preg_replace('#\/#U', '', $result);
			 	$result = preg_replace('#\\\.#U', '.', $result);
			 	return $result;
			}
		}
	}
	
	trait domGc{
		protected $_domXml                                  ;
		protected $_channelXml                              ;
		protected $_itemXml                                 ;
		protected $_nodeXml                                 ;
		protected $_node2Xml                                ;
		protected $_node3Xml                                ;
		protected $_markupXml                               ;
		protected $_markup2Xml                              ;
		protected $_markup3Xml                              ;
		protected $_textXml                                 ;
		protected $_text2Xml                                ;
		protected $_text3Xml                                ;

		final private function _removeChild($fichier, &$dom, &$parent, &$list, $attribut, $valeur){
			foreach($list as $sentence){
				
				if($sentence->getAttribute($attribut) == $valeur){
					$parent->removeChild($sentence);
					$this->_removeChild($fichier, $dom, $parent, $list, $attribut, $valeur);
					$dom->save($fichier);
				}
			}
		}
	}
	
	abstract class constMime{
		const EXT_ZIP                   = 'application/gzip'                         ;
		const EXT_GZ                    = 'application/x-gzip'                       ;
		const EXT_PDF                   = 'application/pdf'                          ;
		const EXT_JS                    = 'application/javascript'                   ;
		const EXT_OGG                   = 'application/ogg'                          ;
		const EXT_EXE                   = 'application/octet-stream'                 ;
		const EXT_DOC                   = 'application/msword'                       ;
		const EXT_XLS                   = 'application/vnd.ms-excel'                 ;
		const EXT_PPT                   = 'application/vnd.ms-powerpoint'            ;
		const EXT_DEFAULT               = 'application/force-download'               ;
		const EXT_XML                   = 'application/xml'                          ;
		const EXT_FLASH                 = 'application/x-shockwave-flash'            ;
		const EXT_JSON                  = 'application/json'                         ;
		const EXT_PNG                   = 'image/png'                                ;
		const EXT_GIF                   = 'image/gif'                                ;
		const EXT_JPG                   = 'image/jpeg'                               ;
		const EXT_TIFF                  = 'image/tiff'                               ;
		const EXT_ICO                   = 'image/vnd.microsoft.icon'                 ;
		const EXT_SVG                   = 'image/svg+xml'                            ;
		const EXT_JPEG                  = 'image/jpeg'                               ;
		const EXT_TXT                   = 'text/plain'                               ;
		const EXT_HTM                   = 'text/html'                                ;
		const EXT_HTML                  = 'text/html'                                ;
		const EXT_CSV                   = 'text/csv'                                 ;
		const EXT_MPEGAUDIO             = 'audio/mpeg'                               ;
		const EXT_MP3                   = 'audio/mpeg'                               ;
		const EXT_RPL                   = 'audio/vnd.rn-realaudio'                   ;
		const EXT_WAV                   = 'audio/x-wav'                              ;
		const EXT_MPEG                  = 'video/mpeg'                               ;
		const EXT_MP4                   = 'video/mp4'                                ;
		const EXT_QUICKTIME             = 'video/quicktime'                          ;
		const EXT_WMV                   = 'video/x-ms-wmv'                           ;
		const EXT_AVI                   = 'video/x-msvideo'                          ;
		const EXT_FLV                   = 'video/x-flv'                              ;
		const EXT_ODT                   = 'application/vnd.oasis.opendocument.text'                                     ;
		const EXT_ODTCALC               = 'application/vnd.oasis.opendocument.spreadsheet'                              ;
		const EXT_ODTPRE                = 'application/vnd.oasis.opendocument.presentation'                             ;
		const EXT_ODTGRA                = 'application/vnd.oasis.opendocument.graphics'                                 ;
		const EXT_XLS2007               = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'           ;
		const EXT_DOC2007               = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'     ;
		const XUL                       = 'application/vnd.mozilla.xul+xml'                                             ;
		CONST TAR                       = 'application/x-tar'                                                           ;
		CONST TGZ                       = 'application/x-tar'                                                           ;
	}

	trait errorPerso{
		final protected function errorPerso($id, $var = array(), $lang = ''){
			if(lang != ''){
				$error = new errorPersoGc($lang);
				echo $error->errorPerso($id, $var);
			}
			else{
				$error = new errorPersoGc($this->_lang);
				echo $error->errorPerso($id, $var);
			}
		}
	}

	trait helperLoader{
		final protected function loadHelper($helper){
			if(!is_array($helper)){
				$helper = array($helper);
			}

			foreach ($helper as $helpers) {
				if(file_exists($helpers) && is_file($helpers)){
					if(!in_array($helper, get_included_files())){
						require_once($helpers);
						$this->_addError('Le helper '.$helpers.' a bien été inclu.', __FILE__, __LINE__, INFORMATION);
					}
				}
				else if(file_exists(CLASS_PATH.CLASS_HELPER_PATH.$helpers.'.class.php') && is_file(CLASS_PATH.CLASS_HELPER_PATH.$helpers.'.class.php')){
					if(!in_array(CLASS_PATH.CLASS_HELPER_PATH.$helpers.'.class.php', get_included_files())){
						require_once(CLASS_PATH.CLASS_HELPER_PATH.$helpers.'.class.php');
						$this->_addError('Le helper '.CLASS_PATH.CLASS_HELPER_PATH.$helpers.'.class.php'.' a bien été inclu.', __FILE__, __LINE__, INFORMATION);
					}
				}
				else{
					$this->_addError('Le helper '.$helpers.' est inacessible.', __FILE__, __LINE__, ERROR);
				}
			}
		}
	}