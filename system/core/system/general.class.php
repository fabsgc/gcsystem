<?php
	/*\
	 | ------------------------------------------------------
	 | @file : general.class.php
	 | @author : fab@c++
	 | @description : traits
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		trait general{
			public function setErrorLog($file, $message){
				if(LOG_ENABLED == true){
					$file = fopen(LOG_PATH.$file.LOG_EXT, "a+");
					fputs($file, date("d/m/Y \a H:i:s ! : ",time()).$message."\n");
				}
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
				$header = "From: \"".$envoyeur."\"<contact@mygreatdating.com>".$passage_ligne;
				$header.= "Reply-to: \"".$envoyeur."\" <contact@mygreatdating.com>".$passage_ligne;
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

			public function getMethod(){
				return $_SERVER['REQUEST_METHOD'];
			}

			public function isXmlHttpRequest(){
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					return true;
				}
				else{
					return false;
				}
			}

			public function errorHttp($error, $titre){
				$t= new template(ERRORDOCUMENT_PATH.'httpError', $error, '0', $this->_lang);
				$t->setShow(false);
				$t->assign(array(
					'url' => substr($this->getUri(), strlen(FOLDER), strlen($this->getUri())),
					'message' => $titre
				));
				return $t->show();
			}
			
			public function redirect404(){
				$this->addHeader('HTTP/1.1 404 Not Found');
				echo $this->errorHttp('404', $this->useLang('gc_404'));
			}
			
			public function redirect500(){
				$this->addHeader('HTTP/1.1 500 internal error');
				echo $this->errorHttp('500', $this->useLang('gc_500'));
				exit();
			}
			
			public function redirect403(){
				$this->addHeader('HTTP/1.1 403 Access Forbidden');
				echo $this->errorHttp('403', $this->useLang('gc_403'));
				exit();
			}

			private function _removeChild($fichier, &$dom, &$parent, &$list, $attribut, $valeur){
				foreach($list as $sentence){
					
					if($sentence->getAttribute($attribut) == $valeur){
						$parent->removeChild($sentence);
						$this->_removeChild($fichier, $dom, $parent, $list, $attribut, $valeur);
						$dom->save($fichier);
					}
				}
			}

			private function rrmdir($dir) {
				if (is_dir($dir)) {
					$objects = scandir($dir);
						foreach ($objects as $object) {
							if ($object != "." && $object != "..") {
								if (filetype($dir."/".$object) == "dir") 
									$this->rrmdir($dir."/".$object); 
								else unlink   ($dir."/".$object);
							}
						}
					reset($objects);
					rmdir($dir);
				}
			}

			function minifyHtml($buffer) {
				$search = array(
					'/\>[^\S ]+/s', 
        			'/[^\S ]+\</s',
        			'/\>(\s)+/s',
        			'/(\s)+\</s'
				);

				$replace = array(
					'> ',
					' <',
					'> ',
					' <'
				);

				$buffer = preg_replace($search, $replace, $buffer);

				return $buffer;
			}

			public function printArray($a, $n = 0) {
				if (!is_array($a)) {
					$n = 0;
					echo $a."</li>";
					return;
				}

				foreach($a as $k => $value) {
					if($k != ''){
						if($n != 0){
							echo '<ul>';
						}
						if($k<10){
							echo '<li><strong>'.$k.'</strong> : ';
							$this->printArray($value, $n+1);
						}
						if($n != 0){
							echo '</ul>';
						}
					}
				}
			}

			public function getPhpArraySyntax($array){
				$data = 'array(';

				foreach($array as $key => $value){
					if(is_array($value)){
						$data .= $this->getPhpArraySyntax($value).',';
					}
					else{
						$data .= '"'.$key.'" => "'.str_replace('"','\"',$value).'",'."\n";
					}
				}

				$data = preg_replace('#,$#isU', '', $data);

				return $data.')';
			}

			public function getFunctionArgNames($function = array()) {
				$f = new \ReflectionFunction($function);
				$result = array();
				foreach ($f->getParameters() as $param) {
					$result[] = $param->name;
				}
				return $result;
			}
		}
		
		trait error{
			protected $_error              = array() ; //array contenant toutes les erreurs enregistrées
			
			public function showError(){
				$erreur = "";
				foreach($this->_error as $error){
					$erreur .=$error."<br />";
				}
				return $erreur;
			}

			public function _addError($error, $fichier = __FILE__, $ligne = __LINE__, $type = INFORMATION){
				if(LOG_ENABLED == true){
					array_push($this->_error, $error);
					$file = fopen(LOG_PATH.LOG_SYSTEM.LOG_EXT, "a+");
					fputs($file, date("d/m/Y \a H:i:s ! : ",time()).'['.$type.'] fichier '.$fichier.' ligne '.$ligne.' '.$error."\n");
					fclose($file);

					if(DISPLAY_ERROR_FATAL == true && $type == FATAL){
						echo date("d/m/Y \a H:i:s ! : ",time()).'['.$type.'] fichier '.$fichier.' ligne '.$ligne.' '.$error."<br />";
					}
				}
			}

			public function _addErrorHr(){
				if(LOG_ENABLED == true){
					$file = fopen(LOG_PATH.LOG_SYSTEM.LOG_EXT, "a+");
					fputs($file, "##### END OF EXECUTION OF http://".$this->getHost().$this->getUri()." ####################################################################################################\n");
					fclose($file);
				}
			}
		}

		trait ormFunctions{
			/**
			 * retourne les données sous forme d'entités
			 * @access	public
			 * @param $bdd pdo
			 * @param $data array
			 * @param $entity string
			 * @return	array
			 * @since 2.4
			 */
			final public function ormToEntity($bdd, $data = array(), $entity = ''){
				$entities = array();

				foreach($data as $value){
					if($entity != ''){
						$entityName = '\entity\\'.$entity;
						$entityObject = new $entityName($bdd);

						foreach($value as $key => $value2){
							$entityObject->$key = $value2;
						}
					}
					else{
						$entityObject = new entityMultiple($data);
					}

					array_push($entities, $entityObject);
				}

				return $entities;
			}
		}
		
		trait langInstance{
			protected $_lang                              = 'fr'    ; //gestion des langues via des fichiers XML
			protected $_langInstance                                ; //instance de la class lang
			
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

		trait groupConfig{
			private function _groupGetParent($child, $data){
				$parent = $child->xpath("parent::*");

				if(is_object($parent[0]['name'])){
					foreach ($this->_routeAttribute as $attribute) {
						$name = $attribute['name'];

						if(is_object($parent[0][$name])){
							if($attribute['cache'] == false){
								if($data[$name] != ''){
									$data[$name] = $parent[0][$name]->__toString().$attribute['separator'].$data[$name];
								}
								else{
									$data[$name] = $parent[0][$name]->__toString();
								}
							}
							else{
								$data[$name] = $parent[0][$name]->__toString();
							}
						}
					}

					$data = $this->_groupGetParent($parent[0], $data);
				}
				
				return $data;
			}
		}
		
		trait urlRegex{
			use groupConfig;

			private $_routeAttribute = array();

			private function _initRoute(){
				$this->_routeAttribute = array(
					array('name' => 'name', 'separator' => '.', 'cache' => false),
					array('name' => 'url', 'separator' => '', 'cache' => false),
					array('name' => 'action', 'separator' => '.', 'cache' => false),
					array('name' => 'vars', 'separator' => ',', 'cache' => false),
					array('name' => 'cache', 'separator' => '', 'cache' => true)
				);
			}

			public function getUrl($name, $var = array(), $absolute = false){
				if(REWRITE == true){
					$this->_initRoute();
					$this->_addError('url "'.$name.'" | fichier ouvert : '.ROUTE, __FILE__, __LINE__, INFORMATION);
					
					$xml = simplexml_load_file(ROUTE);
					$routes = $xml->xpath('//route');
					$result = '';

					foreach ($routes as $value) {
						foreach ($this->_routeAttribute as $attribute) {
							$attributeType = $attribute['name'];

							if(is_object($value[$attributeType]))
								$data[$attributeType] = $value[$attributeType]->__toString();
						}

						$data = $this->_groupGetParent($value, $data);
				
						if ($data['name'] == $name){
							$url = preg_replace('#\((.*)\)#isU', '<($1)>',  $data['url']);
							$urls = explode('<', $url);
							$i=0;
							
							foreach($urls as $url){
								if(preg_match('#\)>#', $url)){
									if(count($var) > 0){
										$result.= preg_replace('#\((.*)\)>#U', $var[$i], $url);
										$i++;
									}
								}
								else{
									$result.=$url;
								}
							}

							$result = preg_replace('#\\\.#U', '.', $result);

							if($absolute == false)
								return FOLDER.$result;
							else
								return 'http://'.$_SERVER['HTTP_HOST'].FOLDER.$result;
						}
					}
				}
				else{
					$url = preg_replace('#\((.*)\)#isU', '<($1)>',  $name);
					$urls = explode('<', $url);
					$i=0;

					foreach($urls as $url){
						$result="";

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

					if($absolute == false)
						return $result;
					else
						return 'http://'.$_SERVER['HTTP_HOST'].$result;
				}
			}
		}
		
		abstract class constMime{
			const EXT_ZIP                   = 'application/gzip'                         ;
			const EXT_GZ                    = 'application/x-gzip'                       ;
			const EXT_GZ_COMPRESSED         = 'application/x-zip-compressed'             ;
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
			public function errorPerso($id, $var = array(), $lang = ''){
				if($lang != ''){
					$error = new errorperso($lang);
					echo $error->errorPerso($id, $var);
				}
				else{
					$error = new errorperso($this->_lang);
					echo $error->errorPerso($id, $var);
				}
			}
		}

		trait helperLoader{
			public function loadHelper($helper){
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
						$this->_addError('Le helper '.$helpers.' est inacessible.', __FILE__, __LINE__, FATAL);
					}
				}
			}
		}

		interface eventListener {
			public function implementedEvents();
		}
	}