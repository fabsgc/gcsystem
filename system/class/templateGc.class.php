<?php
	/*\
	 | ------------------------------------------------------
	 | @file : templateGc.class.php
	 | @author : fab@c++
	 | @description : class gérant le moteur de template
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class templateGc{
		protected $file               = "";       //chemin vers le .tpl
		protected $fileCache          = "";       //chemin vers le .compil.tpl
		protected $nom                = "";       //nom du fichier compilé à créer
		protected $content            = "";       //contenu du fichier de template
		protected $contentCompiled    = "";       //contenu du fichier de template
		public $vars                  = array(); //ensemble des variables
		protected $error              = array(); //liste les erreurs
		protected $refParser		  = null;    //contient une réfénrece vers l'instance du parser
		protected $variable		      = "";      //contient des variables
		protected $timeCache		  = 0;       //contient le temps de mise en cache
		protected $timeFile		      = 0;       //contient la date de dernière modif du template
		protected $lang		             ;       
		protected $show		          = true;       
		
		public  function __construct($file="", $nom="", $timecache=0, $lang=""){
			$this->file=TEMPLATE_PATH.$file.TEMPLATE_EXT;
			if(file_exists($this->file) or is_readable($this->file)){
				$handle = fopen($this->file, 'rb');
				$this->content = fread($handle, filesize ($this->file));
				fclose ($this->file);
				
				$this->nom=$nom;
				$this->timeCache=$timecache;
				$this->fileCache=CACHE_PATH.'template_'.$this->nom.'.tpl.compil.php';
				if($lang==""){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
				$this->setParser();
			} 
			else{
				array_push($error, 'le fichier de template spécifié n\'a pas été trouvé.');
				$this->content = "le fichier ne peut pas être lu";
				$this->nom=$nom;
				$this->timeCache=$timecache;
				$this->fileCache=CACHE_PATH.'template_'.$this->nom.'.tpl.compil.php';
				if($lang==""){ $this->lang=$this->getLangClient(); } else { $this->lang=$lang; }
				$this->setParser();
			}
		}
		
		private function getLangClient(){
			$langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
			$langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
			$langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
			$langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
			return $langcode['0'];
		}
		
		protected function setParser(){
			$this->refParser = new templateGcParser($this, $this->lang);
		}
		
		public  function assign($nom, $vars=null){
			if(is_array($nom)) $this->vars = array_merge($this->vars, $nom);
			else $this->vars[$nom] = $vars;
		}

		public  function assignArray($nom, $vars){
			if(isset($this->vars[$nom]) && !is_array($this->vars[$nom])){
				array_push($error, 'Vous avez écrasé une variable par un array.');
			}

			if(strpos($nom, '.')){
				$e = explode('.', $n);
				$b = '$this->vars';
				$c = count($e) -1;
				for ($i=0 ; $i<$c ; $i++) {
					$b .= '[\'' . $e[$i] . '\']';
					$c_b_p = 'count(' . $b . ') - 1';
					$b .= '[' . $c_b_p . ']';
				}
				$b .= '[\'' . $e[$c] . '\'][] = $vars;';
				eval($b);
			}else{
				$this->vars[$nom][] = $vars;
			}
		}
		
		public function compile($contenu){
			return $this->variable.$this->refParser->parse($contenu);
		}
		
		public function saveCache($contenu){
			$file = fopen($this->fileCache, 'w+');
			fputs($file, ($contenu));
			fclose($file);
		}
		
		public function showError(){
			foreach($this->error as $error){
				$this->erreur .=$error."<br />";
			}
			return $this->erreur;
		}

		public function show(){
			if($this->show==true){
				if(is_file($this->fileCache) && $this->timeCache>0){
					$this->timeFile=filemtime($this->fileCache);
					if(($this->timeFile+$this->timeCache)>time()){
						$handle = fopen($this->fileCache, 'rb');
						$content = fread($handle, filesize ($this->fileCache));
						fclose ($this->fileCache);
						
						if($content==$this->compile($this->content)){
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							include($this->fileCache);
						}
						else{
							$this->contentCompiled=$this->compile($this->content);
							$this->saveCache($this->contentCompiled);
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							include($this->fileCache);
						}
					}
					else{
						$this->contentCompiled=$this->compile($this->content);
						$this->saveCache($this->contentCompiled);
						
						foreach ($this->vars as $cle => $valeur){
							${$cle} = $valeur;
						}
						include($this->fileCache);
					}
				}
				else{
					$this->contentCompiled=$this->compile($this->content);
					$this->saveCache($this->contentCompiled);
					
					foreach ($this->vars as $cle => $valeur){
						${$cle} = $valeur;
					}
					include($this->fileCache);
				}
			}
			elseif($this->show==false){
				if(is_file($this->fileCache) && $this->timeCache>0){
					$this->timeFile=filemtime($this->fileCache);
					if(($this->timeFile+$this->timeCache)>time()){
						$handle = fopen($this->fileCache, 'rb');
						$content = fread($handle, filesize ($this->fileCache));
						fclose ($this->fileCache);
						
						if($content==$this->compile($this->content)){
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							
							ob_start ();
								include($this->fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
						else{
							$this->contentCompiled=$this->compile($this->content);
							$this->saveCache($this->contentCompiled);
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							
							ob_start ();
								include($this->fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
					}
					else{
						$this->contentCompiled=$this->compile($this->content);
						$this->saveCache($this->contentCompiled);
						
						foreach ($this->vars as $cle => $valeur){
							${$cle} = $valeur;
						}
						
						ob_start ();
							include($this->fileCache);
						$out = ob_get_contents();
						ob_get_clean();
						return $out;
					}
				}
				else{
					$this->contentCompiled=$this->compile($this->content);
					$this->saveCache($this->contentCompiled);
					
					foreach ($this->vars as $cle => $valeur){
						${$cle} = $valeur;
					}
					
					ob_start ();
						include($this->fileCache);
					$out = ob_get_contents();
					ob_get_clean();
					return($out);
				}
			}
		}
		
		public function setShow($show){
			$this->show = $show;
		}
	}
	
	class templateGcParser{
		/// référence vers l'objet de la classe gagatemplate
		protected $templateGC;
		protected $lang;
		protected $langInstance;
		protected $contenu;
		
		/// les balises à parser
		protected $bal= array(
			'vars'           => array('{', '}', '{$', '}', '{{', '}}', 'variable', '{{gravatar:', '}}'),  // vars
			'include'        => array('include', 'file', 'cache'),                   // include
			'cond'           => array('if', 'elseif', 'else', 'cond'),               // condition
			'foreach'        => array('foreach', 'var', 'as', 'foreachelse'),        // boucle tableau
			'function'       => array('function', 'name'),                           // fonction
			'com'            => array('/#', '#/'),                                   // commentaire
			'switch'         => array('switch', 'case', 'cond', 'default'),          // switch
			'while'          => array('while', 'var'),                               // while
			'for'            => array('for', 'var', 'boucle'),                       // for
			'spaghettis'     => array('continue', 'break', 'goto', 'from', 'to'),    // spaghettis
			'lang'           => array('_(', ')_'));                                  // langue

		protected $error;
		
		public  function __construct(templateGC &$tplGC, $lang = 'fr'){
			$this->templateGC=$tplGC;
			$this->lang=$lang;
			$this->createLangInstance();
		}
		
		private function createLangInstance(){
			$this->langInstance = new lang($this->lang);
		}
		
		private function useLang($sentence){
			return $this->langInstance->loadSentence($sentence);
		}
		
		public function parse($c){
			$this->contenu=$c;
			$this->parseInclude();
			$this->parseGravatar();
			$this->parseForeach();
			$this->parseWhile();
			$this->parseFor();
			$this->parsevarsExist();
			$this->parsevars();
			$this->parseVarAdd();
			$this->parseCond();
			$this->parseSwitch();
			$this->parseCom();
			$this->parseFunc();
			$this->parseSpaghettis();
			$this->parseLang();
			return $this->contenu;
		}
		
		private function parsevars(){
			foreach ($this->templateGC->vars as $cle => $valeur){
				$variable = '$'.$cle.'='.$valeur.';';
				$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).''.$cle.''.preg_quote($this->bal['vars'][1]).'`', '<?php echo ($'.$cle.'); ?>', $this->contenu);
			}
			$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).'([A-Za-z0-9._-]+)'.preg_quote($this->bal['vars'][1]).'`', '<?php echo $$1; ?>', $this->contenu);
		}
		
		private function parsevarsExist(){
			$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][2]).'([A-Za-z0-9._-]+)'.preg_quote($this->bal['vars'][3]).'`', '<?php echo $1; ?>', $this->contenu);
		}
		
		private function parseInclude(){
			$this->contenu = preg_replace_callback(
				'`<'.preg_quote($this->bal['include'][0]).' '.preg_quote($this->bal['include'][1]).'="(.+)" />`isU', 
				array('templateGcParser','parseIncludeCallback'), $this->contenu);	
				
			$this->contenu=$this->parseIncludeChaine($this->contenu);
		}
		
		private function parseIncludeChaine($chaine){
			$chaine = preg_replace_callback(
				'`<'.preg_quote($this->bal['include'][0]).' '.preg_quote($this->bal['include'][1]).'="(.+)" />`isU', 
				array('templateGcParser','parseIncludeCallback'), $chaine);	
				
				return $chaine;
		}
		
		private function parseIncludeCallback($m){
			foreach($m as $m){
				//if($this->templateGC->file!=$m){
					$m = TEMPLATE_PATH.$m.TEMPLATE_EXT;
					if(file_exists($m) or is_readable($m)){
						$handle = fopen($m, 'rb');
						$content = fread($handle, filesize ($m));
						fclose ($m);
					}
				//}
			}
			return $content;
		}
		
		private function parseCond(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['cond'][0]).' '.preg_quote($this->bal['cond'][3]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['cond'][0]).'>`sU',
					'`<'.preg_quote($this->bal['cond'][1]).' '.preg_quote($this->bal['cond'][3]).'="(.+)"\s?/?>`sU',
					'`<'.preg_quote($this->bal['cond'][2]).'\s?/?>`sU',
				),array(
					'<?php if(\1) { ?>',
					'<?php } ?>',
					'<?php }elseif(\1){ ?>',
					'<?php }else{ ?>'
				),
				$this->contenu);
		}
		
		private function parseSwitch(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['switch'][0]).' '.preg_quote($this->bal['switch'][2]).'="(.+)">`sU',
					'`<'.preg_quote($this->bal['switch'][1]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['switch'][1]).'>`sU',
					'`</'.preg_quote($this->bal['switch'][0]).'>`sU',
					'`<'.preg_quote($this->bal['switch'][3]).'>`sU',
					'`</'.preg_quote($this->bal['switch'][3]).'>`sU',
				),array(
					'<?php switch(\1) { ',
					'case "\1" : ?>',
					'<?php break;',
					'} ?>',
					'default : ?>',
					'<?php break;'					
				),
				$this->contenu);
		}
		
		private function parseCom(){
			$this->contenu = preg_replace('`'.preg_quote($this->bal['com'][0]).'.+'.preg_quote($this->bal['com'][1]).'`isU', null, $this->contenu);
		}
		
		private function parseFunc(){
			$this->contenu = preg_replace_callback('`<'.preg_quote($this->bal['function'][0]).' '.preg_quote($this->bal['function'][1]).'="(\w+)"\s?(.*)?/?>`isU', array('templateGcParser', 'parseFuncCallback'), $this->contenu);
		}

		private static function parseFuncCallback($m){
			$args = '';
			if($nb = preg_match_all('`(string|int|var)="(.+)"`U', $m[2], $arr)){
				for($i=0; $i<$nb; $i++){
					$args .= $arr[1][$i] == 'string' ? '"'.$arr[2][$i].'", ' : $arr[2][$i].', ';
				}
			}
			$args = substr($args, 0, strlen($args)-2);
			return '<?php echo ('.$m[1].'('.$args.')); ?>';
		}
		
		private function parseForeach(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['foreach'][0]).' '.preg_quote($this->bal['foreach'][1]).'="(.+)" '.preg_quote($this->bal['foreach'][2]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['foreach'][0]).'>`sU',
					'`<'.preg_quote($this->bal['foreach'][3]).' />`sU'
				),array(
					'<?php if(!empty($1)) { foreach(\1 as \2) { ?>',
					'<?php } ?>',
					'<?php }} else { ?>'
				),
			$this->contenu);
		}
		
		private function parseWhile(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['while'][0]).' '.preg_quote($this->bal['while'][1]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['while'][0]).'>`sU'
				),array(
					'<?php while(\1) { ?>',
					'<?php } ?>'
				),
			$this->contenu);
		}
		
		private function parseFor(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['for'][0]).' '.preg_quote($this->bal['for'][1]).'="(.+)" '.preg_quote($this->bal['for'][2]).'="(.+)-(.+)-(.+)">`sU',
					'`</'.preg_quote($this->bal['for'][0]).'>`sU'
				),array(
					'<?php for(\1=\2;\1<=\3;\1=\1+\4) { ?>',
					'<?php } ?>'
				),
			$this->contenu);
		}
		
		private function parseVarAdd(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['vars'][6]).' (.+) />`sU'
				),array(
					'<?php $$1; ?>'
				),
			$this->contenu);
		}
		
		private function parseSpaghettis(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['spaghettis'][0]).' />`sU',
					'`<'.preg_quote($this->bal['spaghettis'][1]).' />`sU',
					'`<'.preg_quote($this->bal['spaghettis'][2]).' '.preg_quote($this->bal['spaghettis'][3]).'="(.+)" />`sU',
					'`<'.preg_quote($this->bal['spaghettis'][2]).' '.preg_quote($this->bal['spaghettis'][4]).'="(.+)" />`sU'
				),array(
					'<?php continue; ?>',
					'<?php break; ?>',
					'<?php goto $1; ?>',
					'<?php $1: ?>'
				),
			$this->contenu);
		}
		
		private function parseLang(){
			$this->contenu = preg_replace_callback('`'.preg_quote($this->bal['lang'][0]).'(.+)'.preg_quote($this->bal['lang'][1]).'`sU', array('templateGcParser', 'parseLangCallBack'), $this->contenu);
		}
		
		private function parseLangCallBack($m){
			foreach($m as $m){
			}
			return '<?php echo "'.$this->useLang(''.$m.'').'"; ?>';
		}
		
		private function parseGravatar(){
			$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][7]).'(.+):(.+)'.preg_quote($this->bal['vars'][8]).'`sU',
			'<?php echo \'<img src="http://gravatar.com/avatar/\'.md5("$1").\'?s=$2&default=http://\'.$_SERVER[\'HTTP_HOST\'].\'/\'.FOLDER.\'/asset/image/empty_avatar.png" alt="avatar" />\'; ?>',
			$this->contenu);
		}
	}
?>