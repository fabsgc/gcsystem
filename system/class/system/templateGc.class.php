<?php
	/**
	 * @file : templateGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le moteur de template
	 * @version : 2.0 bêta
	*/
	
	class templateGc{
		use errorGc, langInstance;                                    //trait
		
		protected $_file               = ""         ;    //chemin vers le .tpl
		protected $_fileCache          = ""         ;    //chemin vers le .compil.tpl
		protected $_nom                = ""         ;    //nom du fichier compilé à créer
		protected $_content            = ""         ;    //contenu du fichier de template
		protected $_contentCompiled    = ""         ;    //contenu du fichier de template
		public $vars                   = array()    ;    //ensemble des variables
		protected $_refParser		   = null       ;    //contient une réfénrece vers l'instance du parser
		protected $_variable		   = ""         ;    //contient des variables
		protected $_timeCache		   = 0          ;    //contient le temps de mise en cache
		protected $_timeFile		   = 0          ;    //contient la date de dernière modif du template
		protected $_show		       = true       ;
		
		public  function __construct($file="", $nom="", $timecache=0, $lang=""){
			$this->_file=TEMPLATE_PATH.$file.TEMPLATE_EXT;
			if(file_exists($this->_file) or is_readable($this->_file)){
				$handle = fopen($this->_file, 'rb');
				$this->_content = fread($handle, filesize ($this->_file));
				fclose ($handle);
				
				$this->_nom=$nom;
				$this->_timeCache=$timecache;
				$this->_fileCache=CACHE_PATH.'template_'.$this->_nom.'.tpl.compil.php';
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->setParser();
			} 
			else{
				array_push($error, 'le fichier de template spécifié n\'a pas été trouvé.');
				$this->_addError("le fichier ne peut pas être lu");
				$this->_nom=$nom;
				$this->_timeCache=$timecache;
				$this->_fileCache=CACHE_PATH.'template_'.$this->_nom.'.tpl.compil.php';
				if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
				$this->setParser();
			}
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		protected function setParser(){
			$this->_refParser = new templateGcParser($this, $this->_lang);
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
			return $this->_variable.$this->_refParser->parse($contenu);
		} 
		
		public function saveCache($contenu){
			$file = fopen($this->_fileCache, 'w+');
			fputs($file, ($contenu));
			fclose($file);
		}
		
		public function show(){
			if($this->_show==true){
				$GLOBALS['appDevGc']->addTemplate($this->_file);
				if(is_file($this->_fileCache) && $this->_timeCache>0){
					$this->_timeFile=filemtime($this->_fileCache);
					if(($this->_timeFile+$this->_timeCache)>time()){
						$handle = fopen($this->_fileCache, 'rb');
						$content = fread($handle, filesize ($this->_fileCache));
						fclose ($this->_fileCache);
						
						if($content==$this->compile($this->_content)){
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							include($this->_fileCache);
						}
						else{
							$this->_contentCompiled=$this->compile($this->_content);
							$this->saveCache($this->_contentCompiled);
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							include($this->_fileCache);
						}
					}
					else{
						$this->_contentCompiled=$this->compile($this->_content);
						$this->saveCache($this->_contentCompiled);
						
						foreach ($this->vars as $cle => $valeur){
							${$cle} = $valeur;
						}
						include($this->_fileCache);
					}
				}
				else{
					$this->_contentCompiled=$this->compile($this->_content);
					$this->saveCache($this->_contentCompiled);
					
					foreach ($this->vars as $cle => $valeur){
						${$cle} = $valeur;
					}
					include($this->_fileCache);
				}
			}
			elseif($this->_show==false){
				$GLOBALS['appDevGc']->addTemplate($this->_file);
				if(is_file($this->_fileCache) && $this->_timeCache>0){
					$this->_timeFile=filemtime($this->_fileCache);
					if(($this->_timeFile+$this->_timeCache)>time()){
						$handle = fopen($this->_fileCache, 'rb');
						$content = fread($handle, filesize ($this->_fileCache));
						fclose ($this->_fileCache);
						
						if($content==$this->compile($this->_content)){
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							
							ob_start ();
								include($this->_fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
						else{
							$this->_contentCompiled=$this->compile($this->_content);
							$this->saveCache($this->_contentCompiled);
							foreach ($this->vars as $cle => $valeur){
								${$cle} = $valeur;
							}
							
							ob_start ();
								include($this->_fileCache);
							$out = ob_get_contents();
							ob_get_clean();
							return $out;
						}
					}
					else{
						$this->_contentCompiled=$this->compile($this->_content);
						$this->saveCache($this->_contentCompiled);
						
						foreach ($this->vars as $cle => $valeur){
							${$cle} = $valeur;
						}
						
						ob_start ();
							include($this->_fileCache);
						$out = ob_get_contents();
						ob_get_clean();
						return $out;
					}
				}
				else{
					$this->_contentCompiled=$this->compile($this->_content);
					$this->saveCache($this->_contentCompiled);
					
					foreach ($this->vars as $cle => $valeur){
						${$cle} = $valeur;
					}
					
					ob_start ();
						include($this->_fileCache);
					$out = ob_get_contents();
					ob_get_clean();
					return($out);
				}
			}
		}
		
		public function setShow($show){
			$this->_show = $show;
		}
	}
	
	class templateGcParser{
		use errorGc, langInstance, urlRegex;                                              //trait
		
		protected $templateGC     ;
		protected $contenu        ;
		
		
		/// les balises à parser
		protected $bal= array(
			'vars'           => array('{', '}', '{$', '}', '{{', '}}', 'variable', '{{gravatar:', '}}', '{{url:', '}}'),  // vars
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
			$this->_lang=$lang;
			$this->_createLangInstance();
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		public function parse($c){
			$this->contenu=$c;
			$this->parseInclude();
			$this->parseGravatar();
			$this->parseUrlRegex();
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
		
		protected function parsevars(){
			foreach ($this->templateGC->vars as $cle => $valeur){
				$variable = '$'.$cle.'='.$valeur.';';
				$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).''.$cle.''.preg_quote($this->bal['vars'][1]).'`', '<?php echo ($'.$cle.'); ?>', $this->contenu);
			}
			$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][0]).'([\[\]A-Za-z0-9._-]+)'.preg_quote($this->bal['vars'][1]).'`', '<?php echo ($$1); ?>', $this->contenu);
		}
		
		protected function parsevarsExist(){
			$this->contenu = preg_replace('`'.preg_quote($this->bal['vars'][2]).'([\[\]A-Za-z0-9\$\'._-]+)'.preg_quote($this->bal['vars'][3]).'`', '<?php echo ($1); ?>', $this->contenu);
		}
		
		protected function parseInclude(){
			$this->contenu = preg_replace_callback(
				'`<'.preg_quote($this->bal['include'][0]).' '.preg_quote($this->bal['include'][1]).'="(.+)" />`isU', 
				array('templateGcParser','parseIncludeCallback'), $this->contenu);	
				
			$this->contenu=$this->parseIncludeChaine($this->contenu);
		}
		
		protected function parseIncludeChaine($chaine){
			$chaine = preg_replace_callback(
				'`<'.preg_quote($this->bal['include'][0]).' '.preg_quote($this->bal['include'][1]).'="(.+)" />`isU', 
				array('templateGcParser','parseIncludeCallback'), $chaine);	
				
				return $chaine;
		}
		
		protected function parseIncludeCallback($m){
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
		
		protected function parseCond(){
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
		
		protected function parseSwitch(){
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
		
		protected function parseCom(){
			$this->contenu = preg_replace('`'.preg_quote($this->bal['com'][0]).'.+'.preg_quote($this->bal['com'][1]).'`isU', null, $this->contenu);
		}
		
		protected function parseFunc(){
			$this->contenu = preg_replace_callback('`<'.preg_quote($this->bal['function'][0]).' '.preg_quote($this->bal['function'][1]).'="(\w+)"\s?(.*)?/?>`isU', array('templateGcParser', 'parseFuncCallback'), $this->contenu);
		}

		protected static function parseFuncCallback($m){
			$args = '';
			if($nb = preg_match_all('`(string|int|var)="(.+)"`U', $m[2], $arr)){
				for($i=0; $i<$nb; $i++){
					$args .= $arr[1][$i] == 'string' ? '"'.$arr[2][$i].'", ' : $arr[2][$i].', ';
				}
			}
			$args = substr($args, 0, strlen($args)-2);
			return '<?php echo ('.$m[1].'('.$args.')); ?>';
		}
		
		protected function parseForeach(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['foreach'][0]).' '.preg_quote($this->bal['foreach'][1]).'="(.+)" '.preg_quote($this->bal['foreach'][2]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['foreach'][0]).'>`sU',
					'`<'.preg_quote($this->bal['foreach'][3]).' />`sU'
				),array(
					'<?php if(!empty($1)) { foreach(\1 as \2) { ?>',
					'<?php }} ?>',
					'<?php }} else { ?>'
				),
			$this->contenu);
		}
		
		protected function parseWhile(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['while'][0]).' '.preg_quote($this->bal['while'][1]).'="(.+)">`sU',
					'`</'.preg_quote($this->bal['while'][0]).'>`sU'
				),array(
					'<?php while(\1) { ?>',
					'<?php } ?>'
				),
			$this->contenu);
		}
		
		protected function parseFor(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['for'][0]).' '.preg_quote($this->bal['for'][1]).'="(.+)" '.preg_quote($this->bal['for'][2]).'="(.+)-(.+)-(.+)">`sU',
					'`</'.preg_quote($this->bal['for'][0]).'>`sU'
				),array(
					'<?php for(\1=\2;\1<=\3;\1=\1+\4) { ?>',
					'<?php } ?>'
				),
			$this->contenu);
		}
		
		protected function parseVarAdd(){
			$this->contenu = preg_replace(array(
					'`<'.preg_quote($this->bal['vars'][6]).' (.+) />`sU'
				),array(
					'<?php $$1; ?>'
				),
			$this->contenu);
		}
		
		protected function parseSpaghettis(){
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
		
		protected function parseLang(){
			$this->contenu = preg_replace_callback('`'.preg_quote($this->bal['lang'][0]).'(.+)'.preg_quote($this->bal['lang'][1]).'`sU', array('templateGcParser', 'parseLangCallBack'), $this->contenu);
		}
		
		protected function parseLangCallBack($m){
			foreach($m as $m){
			}
			return '<?php echo "'.$this->useLang(''.$m.'').'"; ?>';
		}
		
		protected function parseGravatar(){
			$this->contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][7]).'(.+):(.+)'.preg_quote($this->bal['vars'][8]).'`sU',
			array('templateGcParser', 'parseGravatarCallback'), $this->contenu);
		}
		
		protected function parseGravatarCallback($m){			
			if(preg_match('#\$#', $m[1])){
				foreach ($this->templateGC->vars as $cle => $val){
					if(substr($m[1], 1, strlen($m[1])) == $cle){
						$m[1] = preg_replace('`'.$cle.'`', $val, $m[1]);
						$m[1] =  substr($m[1], 1, strlen($m[1]));
					}
				}
			}
			if(preg_match('#\$#', $m[2])){
				foreach ($this->templateGC->vars as $cle => $val){
					if(substr($m[2], 1, strlen($m[2])) == $cle){
						$m[2] = preg_replace('`'.$cle.'`', $val, $m[2]);
						$m[2] =  substr($var, 1, strlen($m[2]));
					}
				}
			}
			
			return '<?php echo \'<img src="http://gravatar.com/avatar/\'.md5("'.$m[1].'").\'?s='.$m[2].'&default=http://\'.$_SERVER[\'HTTP_HOST\'].\'/\'.FOLDER.\'/asset/image/GCsystem/empty_avatar.png" alt="avatar" />\'; ?>';
		}
		
		protected function parseUrlRegex(){
			$this->contenu = preg_replace_callback('`'.preg_quote($this->bal['vars'][9]).'(.+):(.+)'.preg_quote($this->bal['vars'][10]).'`sU',
			array('templateGcParser', 'parseUrlRegexCallback'), $this->contenu);
		}
		
		protected function parseUrlRegexCallback($m){
			$vars = explode(',', $m[2]);
			$valeur = array();
			foreach($vars as $var){
				if(preg_match('#\$#', $var)){
					foreach ($this->templateGC->vars as $cle => $val){
						if(substr($var, 1, strlen($var)) == $cle){
							$var = preg_replace('`'.$cle.'`', $val, $var);
							array_push($valeur, substr($var, 1, strlen($var)));
						}
					}
				}
				else{
					array_push($valeur, $var);
				}
			}
			return $this->getUrl($m[1], $valeur);
		}
	}
?>