<?php
	/*\
	 | ------------------------------------------------------
	 | @file : rubrique.class.php
	 | @author : fab@c++
	 | @description : class mère de l'application
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class rubrique implements general{
		
		/* --- infos d'en tete -- */
		
		private $doctype;
		private $title;
		private $metaContentType;
		private $metaKeyword;
		private $metaDescription;
		private $metaRobot;
		private $metaGoogleSite;
		private $openSearch;
		private $js = array();
		private $css = array();
		private $jsInFile = array();
		private $rss = array();
		private $contentMarkupBody;
		private $localisation;
		private $otherHeader = array();
		
		private $lang; // gestion des langues via des fichiers XML
		private $langInstance;
		/* --- permet d'affiche le doctype et l'entete (avant la balise body) et </body></html> -- */
		
		private $header;
		private $footer;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct($lang=""){
			$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
			$this->doctype.="\n";
			$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
			$this->title='Page Web';
			$this->metaContentType='text/html; charset='.CHARSET;
			$this->metaKeyword='';
			$this->metaDescription='';
			$this->metaRobot='index,follow';
			$this->metaGoogleSite='';
			$this->openSearch='';
			$this->js= array ('script.js');
			$this->css= array ('default.css');
			$this->jsInFile = array('inpage.js');
			$this->otherHeader = array();
			$this->langInstance;
			$this->createLangInstance();
			if($lang==""){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
		}
		
		/* ---------- CONNEXION A LA BASE DE DONNEES --------- */
		
			public function connectDatabase($db){
				foreach ($db as $d){
					switch ($d['extension']){
						case 'pdo':
							try{
								$sql_connect[''.$d['database'].''] = new PDO('mysql:host='.$d['hostname'].';dbname='.$d['database'], $d['username'], $d['password']);
							}
							catch (PDOException $e){
								$this->setErrorLog('errors.log', 'Une exception a été lancée. Message d\'erreur lors de la connexion à une base de données : '.$e.'');
							}	
						break;
						
						case 'mysqli':
							$sql_connect[''.$d['database'].''] = new mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
						break;
						
						case 'mysql':
							$sql_connect[''.$d['database'].''] = mysql_connect($d['hostname'], $d['username'], $d['password']);
							$sql_connect[''.$d['database'].''] = mysql_select_db($d['database']);
						break;
						
						default :
							$this->setErrorLog('errors.log', 'L\'extension de cette connexion n\'est pas gérée');
						break;
					}
				}
				return $sql_connect;
			}
			
			private function createLangInstance(){
				$this->langInstance = new lang($this->lang);
			}
			
			public function useLang($sentence){
				return $this->langInstance->loadSentence($sentence);
			}
			
			public function GzipinitOutputFilter() {
				ob_start('ob_gzhandler');
				register_shutdown_function('ob_end_flush');
			}
			
			public function setView($name, $var){
				$GLOBALS[''.$name.'']=$var;
			}
			
			public function destroyVarView($name){
				unset($GLOBALS[''.$name.'']);
			}
					
			public function setErrorLog($file, $message){
				$file = fopen(LOG_PATH.$file, "a+");
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
		
		/* ---------- SETTER --------- */
		
		public function setInfo($info=array()){
			foreach($info as $cle=>$info){
				switch($cle){
					case'doctype':
						switch($info){
							case 'xhtml11':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
							break;
							
							case 'xhtml1-strict':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
							break;
							
							case 'xhtml1-trans':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
							break;
							
							case 'xhtml1-frame':
								$this->doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
								$this->doctype.="\n";
								$this->doctype.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >';
							break;
							
							case 'html5':
								$this->doctype='<!DOCTYPE html>';
								$this->doctype.="\n";
								$this->doctype.='<html lang="fr">';
							break;
						}
					break;
					
					case 'title': 
						$this->title=$info;
					break;

					case 'type': 
						$this->metacontenttype=$info;
					break;

					case 'key':
						$this->metakeyword=$info;
					break;

					case 'description':
						$this->metadescription=$info;
					break;

					case 'metarobot':
						$this->metarobot=$info;
					break;

					case 'metagooglesite':
						$this->metagooglesite=$info;
					break;

					case 'opensearch':
						$this->opensearch=$info;
					break;

					case 'js':
						$this->js=$info;
					break;

					case 'css':
						$this->css=$info;
					break;

					case 'jsinfile':
						$this->jsinfile=$info;
					break;

					case 'rss':
						$this->rss=$info;
					break;

					case 'contentmarkupbody':
						$this->contentmarkupbody=$info;
					break;

					case 'otherheader':
						$this->otherheader=$info;
					break;
					
					case 'localisation':
						$this->localisation=$info;
					break;
					
					case 'lang':
						$this->lang=$info;
					break;
				}
			}
		}
		
		public function setDoctype($Doctype){
			$this->doctype=$Doctype;
		}
		
		public function setTitle($Title){
			$this->title=$Title;
		}
		
		public function setMetaContentType($Type){
			$this->metaContentType=$Type;
		}
		
		public function setMetaKeyword($Key){
			$this->metaKeyword=$Key;
		}
		
		public function setMetaDescription($Description){
			$this->metaDescription=$Description;
		}
		
		public function setMetaRobot($MetaRobot){
			$this->metaRobot=$MetaRobot;
		}
		
		public function setMetaGoogleSite($MetaGoogleSite){
			$this->metaGoogleSite=$MetaGoogleSite;
		}   

		public function setOpenSearch($OpenSearch){
			$this->openSearch=$OpenSearch;
		} 

		public function setRss($Rss){
			$this->rss=$Rss;
		}   		
		
		public function setJs($Js){
			$this->js=$Js;
		}   
		
		public function setCss($Css){
			$this->css=$Css;
		}   
		
		public function setJsInFile($JsInFile){
			$this->jsInFile=$JsInFile;
		}
		
		public function setLocalisation($Localisation){
			$this->localisation=$Localisation;
		}
		
		public function setContentMarkupBody($ContentMarkupBody){
			$this->contentMarkupBody=$ContentMarkupBody;
		}
		
		public function SetotherHeader($OtherHeader){
			$this->otherHeader=$OtherHeader;
		} 
		
		public function setLang($Lang){
			$this->lang=$Lang;
			$this->langInstance->setLang($this->lang);
		} 
		
		/* ---------- GETTER --------- */
		
		public function getDoctype(){
			return $this->doctype;
		}
		
		public function getTitle(){
			return $this->title;
		}
		
		public function getMetaContentType(){
			return $this->metaContentType;
		}
		
		public function getMetaKeyword(){
			return $this->metaKeyword;
		}
		
		public function getMetaDescription(){
			return $this->metaDescription;
		}
		
		public function getMetaRobot(){
			return $this->metaRobot;
		}
		
		public function getMetaGoogleSite(){
			return $this->metaGoogleSite;
		}   

		public function getOpenSearch(){
			return $this->openSearch;
		}     	 
		
		public function getJs(){
			return $this->js;
		}   
		
		public function getCss(){
			return $this->css;
		}   
		
		public function getJsInFile(){
			return $this->jsInFile;
		}
		
		public function getContentMarkupBody(){
			return $this->contentMarkupBody;
		}
		
		public function getOtherHeader(){
			return $this->otherHeader;
		}  
		
		public function getLocalisation(){
			return $this->localisation;
		}
		
		public function getLang(){
			return $this->lang;
		} 
		
		public  function __desctuct(){
		
		}
		
		/* ---------- FONCTIONS ------------- */
		
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
		
		public function windowInfo($Title, $Content, $Time, $Redirect, $lang="fr"){
			?>
				<link href="asset/css/default.css" rel="stylesheet" type="text/css" media="screen, print, handheld" />
			<?php
			$tpl = new templateGC('tplGc_windowInfo', 'tplGc_windowInfo', 0, $lang);
			
			$tpl->assign(array(
				'title'=>$Title,
				'content'=>$Content,
				'redirect'=>$Redirect,
				'time'=>$Time,
			));
				
			$tpl->show();
		}
		
		public function blockInfo($Title, $Content, $Time, $Redirect, $lang="fr"){
			$tpl = new templateGC('tplGc_blockInfo', 'tplGc_blockInfo', 0, $lang);
			
			$tpl->assign(array(
				'title'=>$Title,
				'content'=>$Content,
				'redirect'=>$Redirect,
				'time'=>$Time,
			));
				
			$tpl->show();
		}
		
		public function affHeader(){
			$this->header.=$this->doctype."\n";
			$this->header.="  <head>\n";
			$this->header.="    <title>".($this->title)."</title>\n";
			$this->header.="    <meta http-equiv=\"Content-Type\" content=\"".$this->metaContentType."\" />\n";
			$this->header.="    <meta http-equiv=\"content-language\" content=\"fr\"/>\n";
			$this->header.="    <meta name=\"keywords\" content=\"".$this->metaKeyword."\"/>\n";
			$this->header.="    <meta name=\"description\" content=\"".$this->metaDescription."\" />\n";
			$this->header.="    <meta name=\"robots\" content=\"".$this->metaRobot."\" />\n";
			$this->header.="    <meta name=\"geo.placename\" content=\"".$this->localisation."\" />\n";
			
			if($this->metaGoogleSite!=""){
				$this->header.="    <meta name=\"google-site-verification\" content=\"".$this->metaGoogleSite."\" />\n";
			}
			if($this->openSearch!=""){
				$this->header.="    <link href=\"".$this->openSearch."\"  rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"open search\" />\n";
			}
			
			if(is_file(FAVICON_PATH)){
				$this->header.="     <link rel=\"icon\" type=\"image/png\" href=\"".FAVICON_PATH."\" />\n";
			}
			
			foreach($this->js as $element){
				if(is_file(JS_PATH.$element)){
					$this->header.="    <script type=\"text/javascript\" src=\"".JS_PATH.$element."\" ></script> \n";
				}
			}
			foreach($this->css as $element){
				if(!preg_match('#http:#', $element)){
					if(is_file(CSS_PATH.$element)){
						$this->header.="    <link href=\"".CSS_PATH.$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
					}
				}
				else{
					$this->header.="    <link href=\"".$element."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen, print, handheld\" />\n";
				}	
			}
			
			foreach($this->jsInFile as $element){
				$this->header.="    <script type=\"text/javascript\">\n";
				if(is_file(JS_PATH.$element)){
					$fichier=JS_PATH.$element;
					$contenu = fread(fopen($fichier, "r"), filesize($fichier));
					$this->header.="    ".$contenu."\n";
				}
				$this->header.="    </script>\n";
			}
			
			foreach($this->rss as $element){
				if(is_file($element)){
					$this->header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
				else{
					$this->header.="    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$element."\" href=\"".$element."\" />\n";
				}
			}
			
			if($this->otherHeader){
				foreach($this->otherHeader as $element){
					$this->header.="    ".$element."\n";
				}
			}
			
			$this->header.="  </head>\n";
			
			if($this->contentMarkupBody!=""){
				$this->header.="  <body ".$this->contentMarkupBody.">\n";
			}
			else{
				$this->header.="  <body>\n";
			}
			
			return $this->header;			
		}   
		
		public function affFooter(){
			if(ENVIRONMENT == 'development'){
				$appdev = new appDev($this->lang);
			}
			
			$this->footer="  </body>\n</html>";
			return $this->footer;
		}
		
		public function genererToken(){
			$token = uniqid(rand(), true);
			return $token;
		}
		
		public function affTemplate($nom_template){
			if(is_file(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT)) { 
				include(TEMPLATE_PATH.$nom_template.TEMPLATE_EXT);
			} 
			else { 
				$this->setErrorLog('errors.log', 'Le template '.$nom_template.' n\'a pas été trouvé');
			}
		}
	}
?>