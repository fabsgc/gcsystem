<?php
	/**
	 * @file : feedGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les flux rss
	 * @version : 2.0 bêta
	*/
	
	class feedGc{
		use errorGc,domGc                              ;
		
		protected $_rssFile                        = ""     ;
		protected $_rssFileContent                 = ""     ;
		protected $_rssRead                        = array();
		protected $_rssArray                       = array();
		
		protected $_cache                                   ;
		protected $_time                           = array();
		
		protected $_genRss                         = array();
		protected $_genRssI                        = 0      ;
		
		const NOFILE   = 'Aucun fichier rss n\'a été difini';
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public  function __construct(){
		}
		
		public function newRss($rss, $cache = 0){
			$this->_time[$rss] = $cache;
			if($rss!=""){
				if(empty($this->_genRss[$rss])){
					$this->_genRss[$rss] = array();
					$this->_genRss[$rss]['header'] = array();
					$this->_genRss[$rss]['items'] = array();
					return true;
				}
				else{
					$this->_addError('Ce flux rss existe déjà');
					return false;
				}
			}
			else{
				$this->_addError('Des erreurs sont présentes dans les paramètres de la fonction');
				return false;
			}
		}
		
		public function addHeader($rss, $markup = array()){
			if($rss!="" && is_array($markup) && isset($this->_genRss[$rss])){
				foreach($markup as $cle1 => $value1){
					switch($cle1){
						case 'title':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'link':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'description':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'language':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'copyright':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'managingEditor':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'webMaster':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'pubDate':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'lastBuildDate':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'category' :
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'generator':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'docs':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'cloud':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'ttl':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'image':
							if(is_array($value1)){
								foreach($value1 as $cle2 => $value2){
									switch($cle2){
										case 'title' :
											$this->_genRss[$rss]['header'][$cle1][$cle2] = $value2;
										break;
										
										case 'url' :
											$this->_genRss[$rss]['header'][$cle1][$cle2] = $value2;
										break;
										
										case 'link':
											$this->_genRss[$rss]['header'][$cle1][$cle2] = $value2;
										break;
										
										default:
										break;
									}
								}
							}
						break;
						
						case 'rating':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'textInput':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'skipHours':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						case 'skipDays':
							$this->_genRss[$rss]['header'][$cle1] = $value1;
						break;
						
						default:
						break;
					}
					$this->_genRssI++;
				}
			}
			else{
				$this->_addError('Des erreurs sont présentes dans les paramètres de la fonction');
				return false;
			}
		}
		
		public function addItem($rss, $markup = array()){
			$isarray = array();
			if($rss!="" && is_array($markup) && isset($this->_genRss[$rss])){
				foreach($markup as $value1){
					if(!is_array($value1)){
						array_push($isarray, false);
					}
					else{
						array_push($isarray, true);
					}
					foreach($value1 as $cle2 => $value2){
						switch($cle2){
							case 'title' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'link' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'description' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'author' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'category' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'comments' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'enclosure' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'guid' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'pubDate' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							case 'source' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle2] = $value2;
							break;
							
							default:
							break;
						}
					}
					$this->_genRssI++;
				}
				
				if(in_array(false, $isarray) && !in_array(true, $isarray)){
					foreach($markup as $cle1 => $value1){
						switch($cle1){
							case 'title' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'link' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'description' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'author' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'category' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'comments' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'enclosure' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'guid' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'pubDate' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							case 'source' : 
								$this->_genRss[$rss]['items']['item_'.$this->_genRssI][$cle1] = $value1;
							break;
							
							default:
							break;
						}
					}
				}
			}
			else{
				$this->_addError('Des erreurs sont présentes dans les paramètres de la fonction');
				return false;
			}
		}
		
		public function showRss($rss){
			if($rss!="" && isset($this->_genRss[$rss])){
				$this->_cache = new cacheGc($rss.'.feedGc', "", $this->_time[$rss]);
			
				if($this->_cache->isDie()){
					$this->_domXml = new DomDocument('1.0', CHARSET);
					
					$this->_nodeXml = $this->_domXml->createElement("rss");
					$this->_nodeXml->setAttribute("version", "2.0"); 
					$this->_nodeXml->setAttribute("encoding", CHARSET); 
					$this->_nodeXml = $this->_domXml->appendChild($this->_nodeXml); 
					
					$this->_channelXml = $this->_domXml->createElement("channel");
					$this->_channelXml = $this->_nodeXml->appendChild($this->_channelXml);
					
					foreach($this->_genRss[$rss]['header'] as $cle => $val){
						$this->_markupXml = $this->_domXml->createElement($cle);
						$this->_markupXml = $this->_channelXml->appendChild($this->_markupXml);
						
						if(!is_array($val)){
							$this->_textXml = $this->_domXml->createTextNode($val);
							$this->_textXml = $this->_markupXml->appendChild($this->_textXml);
						}
						elseif(is_array($val)){
							foreach($this->_genRss[$rss]['header'][$cle] as $cle2 => $val2){
								$this->_markup2Xml = $this->_domXml->createElement($cle2);
								$this->_markup2Xml = $this->_markupXml->appendChild($this->_markup2Xml);
								
								$this->_text2Xml = $this->_domXml->createTextNode($val2);
								$this->_text2Xml = $this->_markup2Xml->appendChild($this->_text2Xml);
							}
						}
					}
					
					foreach($this->_genRss[$rss]['items'] as $cle => $val){
						$this->_itemXml = $this->_domXml->createElement("item");
						$this->_itemXml = $this->_channelXml->appendChild($this->_itemXml);
						
						foreach($val as $cle2 => $val2){
							$this->_markup2Xml = $this->_domXml->createElement($cle2);//On crée un élément description
							$this->_markup2Xml = $this->_itemXml->appendChild($this->_markup2Xml);//On ajoute cet élément au channel
								
							$this->_text2Xml = $this->_domXml->createTextNode($val2); //On crée un texte
							$this->_text2Xml = $this->_markup2Xml->appendChild($this->_text2Xml); //On insère ce texte dans le noeud description
						}
					}
					
					$this->_cache->setVal($this->_domXml->saveXML());
					$this->_cache->setCache();
					return $this->_cache->getCache();
				}
				else{
					return $this->_cache->getCache();
				}
			}
			else{
				$this->_addError('Ce flux rss n\'existe pas');
				return false;
			}
		}
		
		public function getGenRss(){
			return $this->_genRss;
		}
		
		public function addRss($nom, $rss){
			if($nom!="" && $rss!=""){
				if(empty($this->_rssArray[$nom])){
					if(preg_match('#http:#isU', $rss) || is_file($rss)){
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $rss);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
						curl_setopt($ch, CURLOPT_TIMEOUT, 2);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
						$this->_rssFileContent = curl_exec($ch);
						$this->_rssFile = $rss;
						curl_close($ch);

						$this->_domXml = new DomDocument('1.0', CHARSET);
						
						if($this->_domXml->loadXML($this->_rssFileContent)){
							$this->_addError('fichier ouvert : '.$this->_rssFile);
							$this->_nodeXml = $this->_domXml->getElementsByTagName('rss')->item(0)->getElementsByTagName('channel')->item(0);

							$this->_setRssHeader($nom, $this->_nodeXml);
							$this->_setRssItem($nom, $this->_nodeXml);
							$this->_rssRead[$nom] = true;
							return true;
						}
						else{
							$this->_addError('Le fichier '.$this->_rssFile.' n\'a pas pu être ouvert');
							$his->_rssRead[$nom] = false;
							return false;
						}
					}
					else{
						$his->_rssRead[$nom] = false;
						return false;
					}
				}
				else{
					$this->_addError('Ce flux rss existe déjà');
					return false;
				}
			}
			else{
				$this->_addError('Des erreurs sont présentes dans les paramètres de la fonction');
				return false;
			}
		}
		
		public function _setRssHeader($nom, $rss){
			$this->_rssArray[$nom]['header']['title'] = $rss->getElementsByTagName('title')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['link'] = $rss->getElementsByTagName('link')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['description'] = $rss->getElementsByTagName('description')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['language'] = $rss->getElementsByTagName('language')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['copyright'] = $rss->getElementsByTagName('copyright')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['managingEditor'] = $rss->getElementsByTagName('managingEditor')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['webMaster'] = $rss->getElementsByTagName('webMaster')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['pubDate'] = $rss->getElementsByTagName('pubDate')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['lastBuildDate'] = $rss->getElementsByTagName('lastBuildDate')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['category'] = $rss->getElementsByTagName('category')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['generator'] = $rss->getElementsByTagName('generator')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['docs'] = $rss->getElementsByTagName('docs')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['ttl'] = $rss->getElementsByTagName('ttl')->item(0)->nodeValue;
			
			$this->_rssArray[$nom]['header']['image']['title'] = $this->_markupeXml = $rss->getElementsByTagName('image')->item(0)->getElementsByTagName('title')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['image']['url'] = $this->_markupeXml = $rss->getElementsByTagName('image')->item(0)->getElementsByTagName('url')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['image']['link'] = $this->_markupeXml = $rss->getElementsByTagName('image')->item(0)->getElementsByTagName('link')->item(0)->nodeValue;			
			
			$this->_rssArray[$nom]['header']['rating'] = $this->_nodeXml->getElementsByTagName('rating')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['textInput'] = $this->_nodeXml->getElementsByTagName('textInput')->item(0)->nodeValue;
			$this->_rssArray[$nom]['header']['skipDays'] = $this->_nodeXml->getElementsByTagName('skipDays')->item(0)->nodeValue;
		}
			
		public function _setRssItem($nom, $rss){
			foreach($rss->getElementsByTagName('item') as $cle => $val){
				$this->_rssArray[$nom]['items']['item_'.$cle]['title'] = $val->getElementsByTagName('title')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['link'] = $val->getElementsByTagName('link')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['description'] = $val->getElementsByTagName('description')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['author'] = $val->getElementsByTagName('author')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['category'] = $val->getElementsByTagName('category')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['comments'] = $val->getElementsByTagName('comments')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['enclosure'] = $val->getElementsByTagName('enclosure')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['guid'] = $val->getElementsByTagName('guid')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['pubDate'] = $val->getElementsByTagName('pubDate')->item(0)->nodeValue;
				$this->_rssArray[$nom]['items']['item_'.$cle]['source'] = $val->getElementsByTagName('source')->item(0)->nodeValue;
			}
		}
		
		public function getRssTitle($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['title'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getRssLink($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['link'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssDescription($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['description'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssLanguage($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['language'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCopyright($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['copyright'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssManagingEditor($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['managingEditor'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssWebMaster($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['webMaster'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssPubDate($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['pubDate'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssLastBuildDate($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['lastBuildDate'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCategory($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['category'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssGenerator($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['generator'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssDocs($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['docs'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCloud($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['cloud'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssTtl($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['ttl'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssImage($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['image'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssRating($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['rating'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssTextInput($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['textInput'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssSkipHours($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['skipHours'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssSkipDays($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					return $this->_rssArray[$nom]['header']['skipDays'];
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getItemTitle($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['title']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemLink($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['link']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemDescription($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['description']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemAuthor($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['author']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemCategory($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['category']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemComments($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['comments']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemEnclosure($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['enclosure']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemGuid($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['guid']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemPubdate($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['pubDate']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemSource($nom){
			$items = array();
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				if(isset($this->_rssArray[$nom])){
					foreach($this->_rssArray[$nom]['items'] as $cle => $val){
						array_push($items, $val['source']);
					}
					return $items;
				}
				else{
					$this->_addError('Ce flux rss n\'existe pas');
					return false;
				}
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getRss($nom){
			if(isset($this->_rssRead[$nom]) && $this->_rssRead[$nom] == true){
				return $this->_rssArray[$nom];
			}
			else{
				return false;
			}
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __destruct(){
		
		}
	}