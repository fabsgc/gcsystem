<?php
	/**
	 * @file : bbcodeGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le parsage des messages et l'affichage d'un éditeur plus ou moins avancé
	 * @version : 2.0 bêta
	*/

	class bbcodeGc{
		use errorGc, langInstance;                                              //trait

		protected $_contenu                                     ; //contenu à parser
		const TAGSTART                    = '['                 ; //constante contenant les tag pour la syntaxe
		const TAGSTART2                   = '[/'                ;
		const TAGEND                      = ']'                 ;
		const PARSETAGSTART               = '<'                 ;
		const PARSETAGSTART2              = '</'                ;
		const PARSETAGEND                 = '>'                 ;
		const PARSETAGENDAUTO             = '/>'                ;
		const BBCODE                      = 'bbcode'            ; //répertoire contenant les images

		protected $_id                                          ; //id uniq pour la création d'un formulaire de bbcode
		protected $_name                                        ; //name pour la création d'un formulaire de bbcode
		protected $_preview                  =true              ; //la prévisualisation est activée
		protected $_previewInstantanee       =true              ; //la prévisualisation instantanée est activée

		protected $_bbCodeWidth              ='600px'            ; //largeur de l'éditeur
		protected $_bbCodeHeight             ='250px'            ; //largeur de la zone de texte de l'éditeur
		protected $_bbCodeBgColor            ='#9d9d9d'          ; //couleur de fond de l'éditeur
		protected $_bbCodeButton             ='blue'             ; //couleur de la barre d'option et du bouton prévisualiser.
															      //valeur : button blue red green pinkish maroonish golden brownish 
															      //grayish skinish yellowish goldenish pink violet orange seagreen personalize
		protected $_bbCodeButtonColor        = array()           ; //dans le cas de couleurs personnalisées

		protected $_bbCode = array (
			'abbr'   => array ('abbr title=&quot;(.*)&quot;', 'abbr', 'abbr title="$1"', 'abbr', '$2'),
			'quote'  => array ('quote title=&quot;(.*)&quot;', 'quote', 'blockquote title="$1"', 'blockquote', '$2'),
			'sup'    => array ('sup', 'sup', 'sup', 'sup', '$1'),
			'sub'    => array ('sub', 'sub', 'sub', 'sub', '$1'),
			'a'      => array ('a', 'a', 'a href="$1"', 'a', '$1'),
			'a2'     => array ('a url=&quot;(.*)&quot;', 'a', 'a href="$1"', 'a', '$2'),
			'h1'     => array ('h1', 'h1', 'h1', 'h1', '$1'),
			'h2'     => array ('h2', 'h2', 'h2', 'h2', '$1'),
			'h3'     => array ('h3', 'h3', 'h3', 'h3', '$1'),
			'h4'     => array ('h4', 'h4', 'h4', 'h4', '$1'),
			'h5'     => array ('h5', 'h5', 'h5', 'h5', '$1'),
			'h6'     => array ('h6', 'h6', 'h6', 'h6', '$1'),
			'em'     => array ('em', 'em', 'em', 'em', '$1'),
			'img'    => array ('img', 'img', 'img src="$1"', '', ''),
			'audio'  => array ('audio', 'audio', 'audio src="$1" controls="controls"', 'audio', '$1'),
			'ins'    => array ('ins', 'ins', 'ins', 'ins', '$1'),
			'del'    => array ('del', 'del', 'del', 'del', '$1'),
			'dfn'    => array ('dfn', 'dfn', 'dfn', 'dfn', '$1'),
			'strong' => array ('strong', 'strong', 'strong', 'strong', '$1'),
			'pre'    => array ('pre', 'pre', 'pre', 'pre', '$1'),
			'align'  => array ('align val=&quot;(.*)&quot;', 'align', 'span style="display: inline-block; text-align: $1;"', 'span', '$2'),
			'float'  => array ('float val=&quot;(.*)&quot;', 'float', 'span style="float: $1;"', 'span', '$2'),
			'email'  => array ('email', 'email', 'a href="mailto:$1"', 'a', '$1'),
			'color'  => array ('color val=&quot;(.*)&quot;', 'color', 'span style="color: $1;"', 'span', '$1'),
			'taille' => array ('size val=&quot;(.*)&quot;', 'size', 'span style="font-size: $1em;"', 'span', '$1'),
			'police' => array ('font val=&quot;(.*)&quot;', 'font', 'span style="font-family: $1;"', 'span', '$1'),
		);

		protected $_bbCodeSmiley = array (
			':)'       => array('smile.png', ':)'),
			':D'       => array('heureux.png', ':D'), 
			':p'       => array('langue.png', ':p'),
			':rire:'   => array('rire.png', ':rire:'),
			':euh:'    => array('unsure.png', ':euh' ),
			':('       => array('triste.png', ':('),
			':o'       => array('huh.png', ':o'),
			':colere:' => array('mechant.png', ':colere'),
			'^^'       => array('hihi.png', '^^'),
			':-&deg;'  => array('siffle.png', ':-°'),
			':think:'  => array('think.png', ':think:'),
			':pleure:'     => array('pleure.png', ':pleure:')
		);

		protected $_bbCodeJs = array (
			'abbr'   => array ('abbr title="(.*)"', 'abbr', 'abbr title="$1"', 'abbr', '$2'),
			'quote'  => array ('quote title="(.*)"', 'quote', 'blockquote title="$1"', 'blockquote', '$2'),
			'sup'    => array ('sup', 'sup', 'sup', 'sup', '$1'),
			'sub'    => array ('sub', 'sub', 'sub', 'sub', '$1'),
			'a'      => array ('a', 'a', 'a href="$1"', 'a', '$1'),
			'a2'     => array ('a url="(.*)"', 'a', 'a href="$1"', 'a', '$2'),
			'h1'     => array ('h1', 'h1', 'h1', 'h1', '$1'),
			'h2'     => array ('h2', 'h2', 'h2', 'h2', '$1'),
			'h3'     => array ('h3', 'h3', 'h3', 'h3', '$1'),
			'h4'     => array ('h4', 'h4', 'h4', 'h4', '$1'),
			'h5'     => array ('h5', 'h5', 'h5', 'h5', '$1'),
			'h6'     => array ('h6', 'h6', 'h6', 'h6', '$1'),
			'h6'     => array ('em', 'em', 'em', 'em', '$1'),
			'img'    => array ('img', 'img', 'img src="$1"', '', ''),
			'audio'  => array ('audio', 'audio', 'audio src="$1" controls="controls"', 'audio', '$1'),
			'ins'    => array ('ins', 'ins', 'ins', 'ins', '$1'),
			'del'    => array ('del', 'del', 'del', 'del', '$1'),
			'dfn'    => array ('dfn', 'dfn', 'dfn', 'dfn', '$1'),
			'strong' => array ('strong', 'strong', 'strong', 'strong', '$1'),
			'pre'    => array ('pre', 'pre', 'pre', 'pre', '$1'),
			'align'  => array ('align val="(.*)"', 'align', 'span style="display: inline-block; text-align: $1;"', 'span', '$2'),
			'float'  => array ('float val="(.*)"', 'float', 'span style="float: $1;"', 'span', '$2'),
			'email'  => array ('email', 'email', 'a href="mailto:$1"', 'a', '$1'),
			'color'  => array ('color val="(.*)"', 'color', 'span style="color: $1;"', 'span', '$2'),
			'taille' => array ('size val="(.*)"', 'size', 'span style="font-size: $1em;"', 'span', '$2'),
			'police' => array ('font val="(.*)"', 'font', 'span style="font-family: $1;"', 'span', '$2'),
		);

		protected $_bbCodeS = array ('ul', 'li', 'ol', 'table', 'tr', 'td', 'th');

		protected $_bbCodeSJs = array (
			'ul'    => array('ul'),
			'li'    => array('li'), 
			'ol'    => array('ol'), 
			'table' => array('table'), 
			'tr'    => array('tr'), 
			'td'    => array('td'), 
			'th'    =>  array('th')
		);

		protected $_bbCodeEditor = array (
			'strong' => array ('strong', 'strong', 'gras.png'),
			'em'     => array ('em', 'em', 'italique.png'),
			'ins'    => array ('ins', 'ins', 'souligne.png'),
			'del'    => array ('del', 'del', 'barre.png'),
			'h3'     => array ('h3', 'h3', 'titre1.png'),
			'h4'     => array ('h4', 'h4', 'titre2.png'),
			'a2'     => array ('a url=&quot;&quot;', 'a', 'lien.png'),
			'img'    => array ('img', 'img', 'image.png'),
			'liste'  => array ('ul][li', 'li][/ul', 'liste.png'),
			'tableau'=> array ('table][tr][td', 'td][/tr][/table', 'tab.png'),
			'quote'  => array ('quote title=&quot;&quot;', 'quote', 'citation.png'),
			'email'  => array ('email', 'email', 'email.png'),
			'sup'    => array ('sup', 'sup', 'sup.png'),
			'sub'    => array ('sub', 'sub', 'sub.png'),
			'align'  => array ('align val=&quot;&quot;', 'align', 'align.png'),
			'float'  => array ('float val=&quot;&quot;', 'float', 'float.png'),
			'color'  => array ('color val=&quot;&quot;', 'color', 'color.png'),
			'taille' => array ('size val=&quot;&quot;', 'size', 'sizeup.png'),
			'police' => array ('font val=&quot;&quot;', 'font', 'style.png'),
			'audio'  => array ('audio', 'audio', 'son.png'),
			'video'  => array ('video', 'video', 'video.png'),
			'code'   => array ('code type=&quot;&quot;', 'code', 'code.png'),
		);

		public  function __construct($lang=""){
			require_once(GESHI);

			$this->_langInstance;
			$this->_createLangInstance();
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
		}

		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}

		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}

		public function parse($contenu){
			//securite
			$this->_contenu = preg_replace(
				'`'.preg_quote(self::TAGSTART).'img'.preg_quote(self::TAGEND).'javascript:(.*)'.preg_quote(self::TAGSTART2).'img'.preg_quote(self::TAGEND).'`isU', 
				'script supprimé', 
				$this->_contenu
			);
			$this->_contenu = preg_replace(
				'`'.preg_quote(self::TAGSTART).'a'.preg_quote(self::TAGEND).'javascript:(.*)'.preg_quote(self::TAGSTART2).'a'.preg_quote(self::TAGEND).'`isU', 
				'script supprimé', 
				$this->_contenu
			);
			$this->_contenu = preg_replace(
				'`'.preg_quote(self::TAGSTART).'a url="javascript:(.*)"'.preg_quote(self::TAGEND).'(.*)'.preg_quote(self::TAGSTART2).'a'.preg_quote(self::TAGEND).'`isU', 
				'script supprimé', 
				$this->_contenu
			);

			$this->_contenu = htmlentities($contenu);

			foreach($this->_bbcode as $cle => $valeur){
				if($valeur[3]!=""){
					$this->_contenu = preg_replace(
						'`'.preg_quote(self::TAGSTART).$valeur[0].preg_quote(self::TAGEND).'(.*)'.preg_quote(self::TAGSTART2).$valeur[1].preg_quote(self::TAGEND).'`isU', 
							self::PARSETAGSTART.$valeur[2].self::PARSETAGEND.$valeur[4].self::PARSETAGSTART2.$valeur[3].self::PARSETAGEND.' ', 
							$this->_contenu
					);
				}
				else{ //cas des balises auto fermantes
					$this->_contenu = preg_replace(
						'`'.preg_quote(self::TAGSTART).$valeur[0].preg_quote(self::TAGEND).'(.*)'.preg_quote(self::TAGSTART2).$valeur[1].preg_quote(self::TAGEND).'`isU', 
							self::PARSETAGSTART.$valeur[2].self::PARSETAGENDAUTO.' ', 
							$this->_contenu
					);
				}
			}

			foreach($this->_bbcodeS as $valeur){
					$this->_contenu = preg_replace(
						'`'.preg_quote(self::TAGSTART).$valeur.preg_quote(self::TAGEND).'`isU', 
							self::PARSETAGSTART.$valeur.self::PARSETAGEND, 
							$this->_contenu
					);

					$this->_contenu = preg_replace(
						'`'.preg_quote(self::TAGSTART2).$valeur.preg_quote(self::TAGEND).'`isU', 
							self::PARSETAGSTART2.$valeur.self::PARSETAGEND, 
							$this->_contenu
					);

			}

			foreach($this->_bbcodeSmiley as $cle => $valeur){
				$this->_contenu = preg_replace(
					'`'.preg_quote($cle).'`isU', 
						self::PARSETAGSTART.'img src="'.IMG_PATH.'bbcode/'.$valeur[0].'" alt="'.$valeur[1].'" '.self::PARSETAGENDAUTO.' ', 
						$this->_contenu
				);
			}

			//balise non html
			$this->_contenu = preg_replace_callback(
				'`'.preg_quote(self::TAGSTART).'video'.preg_quote(self::TAGEND).'(.*)'.preg_quote(self::TAGSTART2).'video'.preg_quote(self::TAGEND).'`isU', 
				array('bbcodeGc', '_video'), $this->_contenu
			);
			$this->_contenu = preg_replace_callback(
				'`'.preg_quote(self::TAGSTART).'code type=&quot;(.*)&quot;'.preg_quote(self::TAGEND).'(.*)'.preg_quote(self::TAGSTART2).'code'.preg_quote(self::TAGEND).'`isU', 
				array('bbcodeGc', '_highlight'), $this->_contenu
			);
			$this->_contenu =  preg_replace_callback(
				'`\s((?:https?|ftp)://\S+?)(?=[]\).,;:!?]?(?:\s|\Z)|\Z)`isU', 
				array('bbcodeGc', '_link'), $this->_contenu);

			$this->_contenu = nl2br($this->_contenu);
			$this->_contenu = preg_replace('`><br />`isU', '>', $this->_contenu);
			return $this->_contenu;
		}

		public function setContenu($contenu){
			$this->_contenu = $this->_contenu;
		}

		public function setLang($Lang){
			$this->_lang=$Lang;
			$this->_langInstance->setLang($this->_lang);
		} 

		public function setPreview($preview){
			$this->_preview=$preview;
		} 

		public function setInstantane($preview){
			$this->_previewInstantanee=$preview;
		} 

		protected function _highlight($contenu){
			$contenu[1] = html_entity_decode($contenu[1]);
			$contenu[2] = html_entity_decode($contenu[2]);

			$code = new GeSHi($contenu[2], $contenu[1]);
			$code->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
			$code->set_line_style('background: #fefefe;', 'background: #f0f0f0;'); 
			$code->enable_keyword_links(false);
			$parse = $code->parse_code();
			return self::PARSETAGSTART.'code'.self::PARSETAGEND.
				self::PARSETAGSTART.'div class="code_nom"'.self::PARSETAGEND.$contenu[1].self::PARSETAGSTART2.'div'.self::PARSETAGEND.
				self::PARSETAGSTART.'div class="code_code"'.self::PARSETAGEND.$parse.self::PARSETAGSTART2.'div'.self::PARSETAGEND.
				self::PARSETAGSTART2.'code'.self::PARSETAGEND;
		}

		protected function _link($contenu){
			if(strlen($contenu[1])>65){
				return self::PARSETAGSTART.'a href="http://'.substr($contenu[1], 6,strlen($contenu[1])).'"'.self::PARSETAGEND.substr($contenu[1], 0,65).'...'.self::PARSETAGSTART2.'a'.self::PARSETAGEND; 
			}
			else{
				return self::PARSETAGSTART.'a href="http://'.substr($contenu[1], 6,strlen($contenu[1])).'"'.self::PARSETAGEND.$contenu[1].self::PARSETAGSTART2.'a'.self::PARSETAGEND;
			}
		}

		protected function _video($contenu){
			$contenu[1] = html_entity_decode($contenu[1]);
			$contenu[1] =  preg_replace('#&feature=related#isU', '', $contenu[1]);	

			if(preg_match('#youtube#isU',$contenu[1])){
				if(preg_match('#www#isU',$contenu[1])){	
					if(!preg_match('#gl=#isU',$contenu[1])){
						$resultat =  preg_replace('#https?://www.youtube.com/watch\?v=(.+)#isU', '$1', $contenu[1]);
					}
					else{
						$resultat =  preg_replace('#https?://www.youtube.com/watch\?gl=(.+)&v=(.+)#isU', '$2', $contenu[1]);
					}	
				}
				else{
					if(!preg_match('#gl=#isU',$contenu[1])){
						$resultat =  preg_replace('#https?://youtube.com/watch\?v=(.+)#isU', '$1', $contenu[1]);
					}
					else{
						$resultat =  preg_replace('#https?://youtube.com/watch\?gl=(.+)v=(.+)#isU', '$2', $contenu[1]);
					}
				}
				return '<object width="500" height="375" type="application/x-shockwave-flash" data="http://www.youtube.com/v/'.$resultat.'">
							<param name="movie" value="http://www.youtube.com/v/'.$resultat.'"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent">
						</object>';
			}
			elseif(preg_match('#dailymotion#isU',$contenu[1])){
				if(preg_match('#www#isU',$contenu[1])){
					$resultat =  preg_replace('#https?://www.dailymotion.com/video/(.+)#isU', 'http://www.dailymotion.com/swf/video/$1', $contenu[1]);
				}
				else{
					$resultat =  preg_replace('#https?://dailymotion.com/video/(.+)#isU', 'http://www.dailymotion.com/swf/video/$1', $contenu[1]);
				}
				return '<object width="500" height="375" type="application/x-shockwave-flash" data="'.$resultat.'"><param name="movie" value="'.$resultat.'">
							<param name="allowFullScreen" value="true"><param name="wmode" value="transparent">
						</object>';
			}
			elseif(preg_match('#vimeo#isU',$contenu[1])){
				if(preg_match('#www#isU',$contenu[1])){
					$resultat =  preg_replace('#https?://www.vimeo.com/(.+)#', 'http://www.vimeo.com/moogaloop.swf?clip_id=$1&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1', $contenu[1]);
				}
				else{
					$resultat =  preg_replace('#https?://vimeo.com/(.+)#', 'http://www.vimeo.com/moogaloop.swf?clip_id=$1&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1', $contenu[1]);
				}
				return '<object width="500" height="375" type="application/x-shockwave-flash" data="'.$resultat.'">
							<param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" />
							<param name="movie" value="'.$resultat.'" /><param name="wmode" value="transparent" />
						</object>';
			}
			else{
				return '<video controls="" width="500" src="'.$contenu[1].'" controls="controls"></video>';
			}
		}

		public function editor($contenu, $option = array()){
			$this->_id = 'id_'.uniqid();
			$this->_name = 'name_'.uniqid();

			foreach($option as $cle=>$info){
				switch($cle){
					case 'width':
						$this->_bbCodeWidth = $info;
					break;

					case 'height':
						$this->_bbCodeHeight = $info;
					break;

					case 'bgcolor':
						$this->_bbCodeBgColor = $info;
					break;

					case 'id':
						$this->_id = $info;
					break;

					case 'name':
						$this->_name = $info;
					break;

					case 'theme':
						$this->_bbCodeButton = $info;
					break;

					case 'preview':
						$this->_preview = $info;
					break;

					case 'instantane':
						$this->_previewInstantanee = $info;
					break;

					case 'color' :
						$this->_bbCodeButtonColor = $info;
					break;
				}
			}

			$tpl = new templateGC(GCSYSTEM_PATH.'GCbbcodeEditor', 'GCbbcodeEditor', 0, $this->_lang);
			$tpl->assign(array(
				'message' => $contenu,
				'preview' => $this->_preview,
				'instantane' => $this->_previewInstantanee,
				'id' => $this->_id,
				'name' => $this->_name,
				'width' => $this->_bbCodeWidth,
				'Twidth' => $this->_bbCodeWidth,
				'height' => $this->_bbCodeHeight,
				'bgcolor' => $this->_bbCodeBgColor,
				'theme' => $this->_bbCodeButton,
				'smiley' => $this->_bbCodeSmiley,
				'bbcode' => $this->_bbCodeJs,
				'imgpath' => IMG_PATH.GCSYSTEM_PATH,
				'bbCodeS' => $this->_bbCodeSJs,
				'bbCodeEditor' => $this->_bbCodeEditor,
				'color' => $this->_bbCodeButtonColor
			));
			$tpl->show();
			
			print_r($this->_bbcodeButtonColor);
		}

		public  function __desctuct(){

		}
	}