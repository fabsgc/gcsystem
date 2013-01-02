<?php
	/**
	 * @file : generalGc.class.php
	 * @author : fab@c++
	 * @description : traits
	 * @version : 2.0 bêta
	*/

	trait generalGc{
		public function windowInfo($Title, $Content, $Time, $Redirect, $lang='fr'){
			$tpl = new templateGC(GCSYSTEM_PATH.'GCtplGc_windowInfo', 'tplGc_windowInfo', 0, $lang);
			
			$tpl->assign(array(
				'title'=>$Title,
				'content'=>$Content,
				'redirect'=>$Redirect,
				'time'=>$Time,
				'css'=>'/asset/css/default.css'
			));
				
			$tpl->show();
		}
		
		public function blockInfo($Title, $Content, $Time, $Redirect, $lang='fr'){
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
			fputs($file, date("d/m/Y \a H:i:s ! : ",time()).$message."\n");
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
			return mail($email,$sujet,$message,$header);
			//==========		
		}
		
		public function getIp(){
			return $_SERVER['REMOTE_ADDR'];
		}
	
		public function getQuery(){
			return $_SERVER['QUERY_STRING'];
		}
		
		public function getPhpSelf(){
			return $_SERVER['PHP_SELF'];
		}
		
		public function getHost(){
			return $_SERVER['HTTP_HOST'];
		}
		
		public function getUri(){
			return $_SERVER['REQUEST_URI'];
		}
		
		public function getReferer(){
			if(isset($_SERVER['HTTP_REFERER'])){
				return $_SERVER['HTTP_REFERER'];
			}
			else{
				return false;
			}
		}
		
		public function getServerName(){
			return $_SERVER['SERVER_NAME'];
		}
		
		public function addHeader($header){
            header($header);
        }

        public function errorHttp($error, $titre){
        	$t= new templateGC(ERRORDUOCUMENT_PATH.'httpError', $error, '0', $this->_lang);
        	$t->setShow(false);
			$t->assign(array(
				'url' => substr($this->getUri(), strlen(FOLDER), strlen($this->getUri())),
				'message' => $titre
			));
			return $t->show();
        }
		
		public function redirect404(){
			$this->addHeader('HTTP/1.1 404 Not Found');
			echo $this->errorHttp('404', $this->useLang('404'));
        }
		
		public function redirect500(){
			$this->addHeader('HTTP/1.1 500 internal error');
			echo $this->errorHttp('500', $this->useLang('500'));
			exit();
        }
		
		public function redirect403(){
			$this->addHeader('HTTP/1.1 403 Access Forbidden');
			echo $this->errorHttp('403', $this->useLang('403'));
			exit();
        }
	}
	
	trait errorGc{
		protected $_error              = array() ; //array contenant toutes les erreurs enregistrées
		
		public function showError(){
			$erreur = "";
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}

		public function __isset($nom){
			$this->_addError('tentative de test "isset" sur un attribut "'.$nom.'" inaccessible', __FILE__,  __LINE__, ERROR);
			return false;
		}

		public function __unset($nom){
			$this->_addError('tentative de suppression de l:\'attribut "'.$nom.'" inaccessible', __FILE__,  __LINE__, ERROR);
		}

		public function __call($nom, $arguments){
			$this->_addError('tentative d\'appel de la méthode "'.$nom.'" inaccessible avec les arguments '.implode(', ', $arguments), __FILE__,  __LINE__, ERROR);
		}

		public static function __callStatic($nom, $arguments){
			$this->_addError('tentative d\'appel de la méthode "'.$nom.'" inaccessible dans un contexte statique avec les arguments '.implode(', ', $arguments), __FILE__,  __LINE__, ERROR);
		}

		public function __invoke($arguments){
			$this->_addError('tentative d\'utilisation de l\'objet en tant que fonction avec les arguments '.implode(', ', $arguments), __FILE__,  __LINE__, ERROR);
		}
		
		protected function _addError($error, $fichier = __FILE__, $ligne = __LINE__, $type = INFORMATION){
			array_push($this->_error, $error);
			$file = fopen(LOG_PATH.'system_errors'.LOG_EXT, "a+");
			fputs($file, date("d/m/Y \a H:i:s ! : ",time()).'['.$type.'] fichier '.$fichier.' ligne '.$ligne.' '.$error."\n");
			fclose($file);
		}

		protected function _addErrorHr(){
			$file = fopen(LOG_PATH.'system_errors'.LOG_EXT, "a+");
			fputs($file, "##### END OF EXECUTION ####################################################################################################\n");
			fclose($file);
		}
    }
	
	trait langInstance{
		protected $_lang                              = 'fr'    ; //gestion des langues via des fichiers XML
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
	
	trait urlRegex{
		public function getUrl($id, $var = array()){
			if(REWRITE == true){
				$domXml = new DomDocument('1.0', 'iso-8859-15');
				if($domXml->load(ROUTE)){
					$this->_addError('fichier ouvert : '.ROUTE, __FILE__, __LINE__, INFORMATION);
				
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

		private function _removeChild($fichier, &$dom, &$parent, &$list, $attribut, $valeur){
			foreach($list as $sentence){
				
				if($sentence->getAttribute($attribut) == $valeur){
					echo $sentence->getAttribute('id');
					$parent->removeChild($sentence);
					$this->_removeChild($fichier, $dom, $parent, $list, $attribut, $valeur);
					$dom->save($fichier);
				}
			}
		}
	}

	trait htmlHeaderGc{
		protected $_doctype            = "<!DOCTYPE html>\n<html lang=\"fr\">"    ;
		protected $_title              = 'page web'                               ;
		protected $_metaContentType    = 'text/html; charset= UTF-8'              ;
		protected $_metaKeyword        = ''                                       ;
		protected $_metaDescription    = ''                                       ;
		protected $_metaRobot          = 'index,follow'                           ;
		protected $_metaGoogleSite     = ''                                       ;
		protected $_metaAuthor         = ''                                       ;
		protected $_metaCopyright      = ''                                       ;
		protected $_openSearch         = ''                                       ;
		protected $_js                 = array('script.js')                       ;
		protected $_css                = array('default.css')                     ;
		protected $_jsInFile           = array('inpage.js')                       ;
		protected $_rss                = array()                                  ;
		protected $_contentMarkupBody  = ''                                       ;
		protected $_localisation       = ''                                       ;
		protected $_otherHeader        = array()                                  ;
		protected $_fbTitle            = ''                                       ;
		protected $_fbDescription      = ''                                       ;
		protected $_fbImage            = ''                                       ;
		protected $_html5              = true                                     ;
		protected $_header                                                        ;
		protected $_footer                                                        ;

		final protected function setInfo($info=array()){
			foreach($info as $cle=>$info){
				switch($cle){
					case'doctype':
						switch($info){
							case 'xhtml11':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-strict':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-trans':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'xhtml1-frame':
								$this->_doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
								$this->_doctype.="\n";
								$this->_doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
								$this->_html5 = false;
							break;
							
							case 'html5':
								$this->_doctype='<!DOCTYPE html>';
								$this->_doctype.="\n";
								$this->_doctype.='<html lang="fr">';
								$this->_html5 = true;
							break;
						}
					break;
					
					case 'title': 
						$this->_title=$info;
					break;

					case 'type': 
						$this->metacontenttype=$info;
					break;

					case 'key':
						$this->metakeyword=$info;
					break;

					case 'description':
						$this->_metaDescription=$info;
					break;

					case 'metarobot':
						$this->_metaRobot=$info;
					break;

					case 'metaauthor':
						$this->_metaAuthor=$info;
					break;
					
					case 'metacopyright':
						$this->_metaCopyright=$info;
					break;
					
					case 'metagooglesite':
						$this->_metaGoogleSite=$info;
					break;

					case 'opensearch':
						$this->_openSearch=$info;
					break;

					case 'js':
						$this->_js=$info;
					break;

					case 'css':
						$this->_css=$info;
					break;

					case 'jsinfile':
						$this->_jsinfile=$info;
					break;

					case 'rss':
						$this->_rss=$info;
					break;

					case 'contentmarkupbody':
						$this->contentmarkupbody=$info;
					break;

					case 'otherheader':
						$this->otherheader=$info;
					break;
					
					case 'localisation':
						$this->_localisation=$info;
					break;
					
					case 'lang':
						$this->_lang=$info;
					break;
					
					case 'fb_title':
						$this->_fbTitle=$info;
					break;
					
					case 'fb_desccription':
						$this->fbDesccription=$info;
					break;
					
					case 'fb_image':
						$this->_fbImage=$info;
					break;
				}
			}
		}
		
		final protected function showHeader(){
			$this->_header.=$this->_doctype."\n";
			$this->_header.="  <head>\n";
			$this->_header.="    <title>".($this->_title)."</title>\n";
			if($this->_html5 == false){ $this->_header.="    <meta http-equiv=\"Content-Type\" content=\"".$this->_metaContentType."\" />\n"; }
				else { $this->_header.="    <meta charset=\"utf-8\" />\n"; }
			if($this->_html5 == false){ $this->_header.="    <meta http-equiv=\"content-language\" content=\"".$this->_lang."\"/>\n"; }
			$this->_header.="    <meta name=\"keywords\" content=\"".$this->_metaKeyword."\"/>\n";
			$this->_header.="    <meta name=\"description\" content=\"".$this->_metaDescription."\" />\n";
			$this->_header.="    <meta name=\"robots\" content=\"".$this->_metaRobot."\" />\n";
			$this->_header.="    <meta name=\"geo.placename\" content=\"".$this->_localisation."\" />\n";
			
			if($this->_fbTitle!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbTitle."\" />\n";
			}
			if($this->_fbDescription!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbDescription."\" />\n";
			}
			if($this->_fbImage!=""){
				$this->_header.="    <meta property=\"og:description\" content=\"".$this->_fbImage."\" />\n";
			}
			if($this->_metaGoogleSite!=""){
				$this->_header.="    <meta name=\"google-site-verification\" content=\"".$this->_metaGoogleSite."\" />\n";
			}
			if($this->_metaAuthor!=""){
				$this->_header.="    <meta name=\"Author\" content=\"".$this->_metaAuthor."\" />\n";
			}
			if($this->_metaCopyright!=""){
				$this->_header.="    <meta name=\"Copyright\" content=\"".$this->_metaCopyright."\" />\n";
			}
			if($this->_openSearch!=""){
				if(is_file($this->_openSearch) && file_exists($this->_openSearch) && is_readable($this->_openSearch)){
					$this->_header.="    <link href=\"".FOLDER.'/'.$this->_openSearch."\"  rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"open search\" />\n";
				}
				else{
					$this->_header.="    <link href=\"".$this->_openSearch."\"  rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"open search\" />\n";
				}
			}
			if(is_file(FAVICON_PATH) && file_exists(FAVICON_PATH) && is_readable(FAVICON_PATH)){
					$this->_header.="     <link rel=\"icon\" type=\"image/png\" href=\"".FOLDER."/".FAVICON_PATH."\" />\n";
			}
			else{
				$this->_header.="     <link rel=\"icon\" type=\"image/png\" href=\"".FAVICON_PATH."\" />\n";
			}
			if(JQUERY==true){
				$this->_header.="    <script type=\"text/javascript\" src=\"".JQUERYFILE."\" ></script> \n";
				$this->_header.="    <script type=\"text/javascript\" src=\"".JQUERYUIJS."\" ></script> \n";
				$this->_header.="    <link href=\"".JQUERYUICSS."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
			}
			if(LESS==true){
				$this->_header.="    <script type=\"text/javascript\" src=\"".LESSFILE."\" ></script> \n";
			}
			if(SYNTAXHIGHLIGHTER==true){
				$this->_header.="    <link href=\"".SHIGHLIGHTER_SHCORE_CSS."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
				$this->_header.="    <link href=\"".SHIGHLIGHTER_SHCOREDEFAULT_CSS."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
				$this->_header.="    <script type=\"text/javascript\" src=\"".SHIGHLIGHTER_SHCORE_JS."\" ></script> \n";
				$this->_header.="    <script type=\"text/javascript\" src=\"".SHIGHLIGHTER_AUTOLOADER_JS."\" ></script> \n";

				$dossier = opendir ('asset/js/syntaxhighligher/language/');

				while ($fichier = readdir ($dossier)){
					if ($fichier != "." && $fichier != ".." && !preg_match('#index#isU', $fichier)){
						$this->_header.="    <script type=\"text/javascript\" src=\"".SHIGHLIGHTER.'language/'.$fichier."\" ></script> \n";
					}       
				}
				closedir ($dossier);
			}
			foreach($this->_js as $element){
				if(!preg_match('#http:#isU', JS_PATH.$element)){
					$this->_header.="    <script type=\"text/javascript\" src=\"".JS_PATH.$element."\" ></script> \n";
				}
				else{
					$this->_header.="    <script type=\"text/javascript\" src=\"".$element."\" ></script> \n";
				}
			}
			foreach($this->_css as $element){
				if(!preg_match('#http:#isU', JS_PATH.$element)){
					if(LESS==true){
						$this->_header.="    <link href=\"".CSS_PATH.$element."\" rel=\"stylesheet/less\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
					else{
						$this->_header.="    <link href=\"".CSS_PATH.$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
				}
				else{
					if(LESS==true){
						$this->_header.="    <link href=\"".$element."\" rel=\"stylesheet/less\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
					else{
						$this->_header.="    <link href=\"".$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
				}	
			}
			foreach($this->_jsInFile as $element){
				$this->_header.="    <script type=\"text/javascript\">\n";
				$this->_header.="    ".file_get_contents(preg_replace('#'.FOLDER.'/#isU', '', JS_PATH).$element)."\n";
				$this->_header.="    </script>\n";
			}
			foreach($this->_rss as $element){
				if(is_file($element)){
					$this->_header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
				else{
					$this->_header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
			}
			if($this->_otherHeader){
				foreach($this->_otherHeader as $element){
					$this->_header.="    ".$element."\n";
				}
			}
			
			$this->_header.="  </head>\n";
			
			if($this->_contentMarkupBody!=""){
				$this->_header.="  <body ".$this->_contentMarkupBody.">\n";
			}
			else{
				$this->_header.="  <body>\n";
			}
			
			return $this->_header;			
		}
		
		final protected function showFooter(){
			if(SYNTAXHIGHLIGHTER == true){
				$this->_footer.="    <script type=\"text/javascript\">";
				$this->_footer.="      SyntaxHighlighter.config.stripBrs = false;";
				$this->_footer.="      SyntaxHighlighter.all();";
				$this->_footer.="    </script>";
			}
			$this->_footer.="  </body>\n</html>";
			return $this->_footer;
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