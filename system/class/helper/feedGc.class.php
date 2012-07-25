<?php
	/**
	 * @file : feedGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les flux rss
	 * @version : 2.0 bêta
	*/
	
	class feedGc{
		use errorGc;                            //trait
		
		protected $_rssFile                        = ""     ;
		protected $_rssFileContent                 = ""     ;
		protected $_rssRead                        = false  ;
		
		protected $_rssTitle                       = array();
		protected $_rssLink                        = array();
		protected $_rssDescription                 = array();
		protected $_rssLanguage                    = array();
		protected $_rssCopyright                   = array();
		protected $_rssManagingEditor              = array();
		protected $_rssWebMaster                   = array();
		protected $_rssPubDate                     = array();
		protected $_rssLastBuildDate               = array();
		protected $_rssCategory                    = array();
		protected $_rssGenerator                   = array();
		protected $_rssDocs                        = array();
		protected $_rssCloud                       = array();
		protected $_rssTtl                         = array();
		protected $_rssImage                       = array();
		protected $_rssRating                      = array();
		protected $_rssTextInput                   = array();
		protected $_rssSkipHours                   = array();
		protected $_rssSkipDays                    = array();
		
		protected $_itemTitle                      = array();
		protected $_itemLink                       = array();
		protected $_itemDescription                = array();
		protected $_itemAuthor                     = array();
		protected $_itemCategory                   = array();
		protected $_itemComments                   = array();
		protected $_itemEnclosure                  = array();
		protected $_itemGuid                       = array();
		protected $_itemPubdate                    = array();
		protected $_itemSource                     = array();
		
		protected $_domXml                                  ;
		protected $_nodeXml                                 ;
		protected $_markupXml                               ;
		
		protected $_genRss                         = array() ;
		protected $_genRssOutput                   = ''      ;
		protected $_genRssI                        = 0       ;
		
		const NOFILE   = 'Aucun fichier rss n\'a été difini';
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public  function __construct($rss = ""){
			if($rss!=""){
				if($this->_setRss($rss) == true){
					$rssRead = true;
				}
			}
		}
		
		public function newRss($rss){
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
			$this->_genRssI = 0;
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
			}
			else{
				$this->_addError('Ce flux rss n\'existe pas');
				return false;
			}
		}
		
		public function getGenRss(){
			return $this->_genRss;
		}
		
		public function getRssTitle(){
			if($this->_isExist == true){
				if($this->_rssDescription != "")
					return $this->_rssTitle;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getRssLink(){
			if($this->_isExist == true){
				if($this->_rssDescription != "")
					return $this->_rssLink;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssDescription(){
			if($this->_isExist == true){
				if($this->_rssDescription != "")
					return $this->_rssDescription;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssLanguage(){
			if($this->_isExist == true){
				if($this->_rssLanguage != "")
					return $this->_rssLanguage;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCopyright(){
			if($this->_isExist == true){
				if($this->_rssCopyright != "")
					return $this->_rssCopyright;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssManagingEditor(){
			if($this->_isExist == true){
				if($this->_rssManagingEditor != "")
					return $this->_rssManagingEditor;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssWebMaster(){
			if($this->_isExist == true){
				if($this->_rssWebMaster != "")
					return $this->_rssWebMaster;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssPubDate(){
			if($this->_isExist == true){
				if($this->_rssPubDate != "")
					return $this->_rssPubDate;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssLastBuildDate(){
			if($this->_isExist == true){
				if($this->_rssLastBuildDate != "")
					return $this->_rssLastBuildDate;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCategory(){
			if($this->_isExist == true){
				if($this->_rssCategory != "")
					return $this->_rssCategory;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssGenerator(){
			if($this->_isExist == true){
				if($this->_rssGenerator != "")
					return $this->_rssGenerator;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssDocs(){
			if($this->_isExist == true){
				if($this->_rssDocs != "")
					return $this->_rssDocs;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssCloud(){
			if($this->_isExist == true){
				if($this->_rssCloud != "")
					return $this->_rssCloud;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssTtl(){
			if($this->_isExist == true){
				if($this->_rssTtl != "")
					return $this->_rssTtl;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssImage(){
			if($this->_isExist == true){
				if($this->_rssImage != "")
					return $this->_rssImage;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssRating(){
			if($this->_isExist == true){
				if($this->_rssRating != "")
					return $this->_rssRating;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssTextInput(){
			if($this->_isExist == true){
				if($this->_rssTextInput != "")
					return $this->_rssTextInput;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssSkipHours(){
			if($this->_isExist == true){
				if($this->_rssSkipHours != "")
					return $this->_rssSkipHours;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getRssSkipDays(){
			if($this->_isExist == true){
				if($this->_rssSkipDays != "")
					return $this->_rssSkipDays;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getItemTitle(){
			if($this->_isExist == true){
				if($this->_itemTitle != "")
					return $this->_itemTitle;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemLink(){
			if($this->_isExist == true){
				if($this->_itemLink != "")
					return $this->_itemLink;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemDescription(){
			if($this->_isExist == true){
				if($this->_itemDescription != "")
					return $this->_itemDescription;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemAuthor(){
			if($this->_isExist == true){
				if($this->_itemAuthor != "")
					return $this->_itemAuthor;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemCategory(){
			if($this->_isExist == true){
				if($this->_itemCategory != "")
					return $this->_itemCategory;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemComments(){
			if($this->_isExist == true){
				if($this->_itemComments != "")
					return $this->_itemComments;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemEnclosure(){
			if($this->_isExist == true){
				if($this->_itemEnclosure != "")
					return $this->_itemClosure;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemGuid(){
			if($this->_isExist == true){
				if($this->_itemGuid != "")
					return $this->_itemGuid;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemPubdate(){
			if($this->_isExist == true){
				if($this->_itemPubdate != "")
					return $this->_itemPubdate;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}

		public function getItemSource(){
			if($this->_isExist == true){
				if($this->_itemSource != "")
					return $this->_itemSource;
				else
					return false;
			}
			else{
				$this->_addError(self::NOFILE);
				return false;
			}
		}
		
		public function getRss(){
		}
		
		protected function _setRss($rss){
			if(is_file($rss)){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $rss);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$this->_rssFileContent = curl_exec($ch);
				curl_close($ch);
				$this->_rssFile = $rss;
				
				$this->_setRssTitle($this->_rssFileContent);
				$this->_setRssLink($this->_rssFileContent);
				$this->_setRssDescription($this->_rssFileContent);
				$this->_setRssLanguage($this->_rssFileContent);
				$this->_setRssCopyright($this->_rssFileContent);
				$this->_setRssManagingEditor($this->_rssFileContent);
				$this->_setRssWebMaster($this->_rssFileContent);
				$this->_setRssPubDate($this->_rssFileContent);
				$this->_setRssLastBuildDate($this->_rssFileContent);
				$this->_setRssCategory($this->_rssFileContent);
				$this->_setRssGenerator($this->_rssFileContent);
				$this->_setRssDocs($this->_rssFileContent);
				$this->_setRssCloud($this->_rssFileContent);
				$this->_setRssTtl($this->_rssFileContent);
				$this->_setRssImage($this->_rssFileContent);
				$this->_setRssRating($this->_rssFileContent);
				$this->_setRssTextInput($this->_rssFileContent);
				$this->_setRssSkipHours($this->_rssFileContent);
				$this->_setRssSkipDays($this->_rssFileContent);
				$this->_setItemTitle($this->_rssFileContent);
				$this->_setItemLink($this->_rssFileContent);
				$this->_setItemDescription($this->_rssFileContent);
				$this->_setItemAuthor($this->_rssFileContent);
				$this->_setItemCategory($this->_rssFileContent);
				$this->_setItemComments($this->_rssFileContent);
				$this->_setItemEnclosure($this->_rssFileContent);
				$this->_setItemGuid($this->_rssFileContent);
				$this->_setItemPubdate($this->_rssFileContent);
				$this->_setItemSource($this->_rssFileContent);
				$this->_setRss($this->_rssFileContent);
				
				return true;
			}
			else{
				return false;
			}
		}
		
		public function _setRssTitle($rss){
		}

		public function _setRssLink($rss){
		}

		public function _setRssDescription($rss){
		}

		public function _setRssLanguage($rss){
		}

		public function _setRssCopyright($rss){
		}

		public function _setRssManagingEditor($rss){
		}

		public function _setRssWebMaster($rss){
		}

		public function _setRssPubDate($rss){
		}

		public function _setRssLastBuildDate($rss){
		}

		public function _setRssCategory($rss){
		}

		public function _setRssGenerator($rss){
		}

		public function _setRssDocs($rss){
		}

		public function _setRssCloud($rss){
		}

		public function _setRssTtl($rss){
		}

		public function _setRssImage($rss){
		}

		public function _setRssRating($rss){
		}

		public function _setRssTextInput($rss){
		}

		public function _setRssSkipHours($rss){
		}

		public function _setRssSkipDays($rss){
		}

		public function _setItemTitle($rss){
		}

		public function _setItemLink($rss){
		}

		public function _setItemDescription($rss){
		}

		public function _setItemAuthor($rss){
		}

		public function _setItemCategory($rss){
		}

		public function _setItemComments($rss){
		}

		public function _setItemEnclosure($rss){
		}

		public function _setItemGuid($rss){
		}

		public function _setItemPubdate($rss){
		}

		public function _setItemSource($rss){
		}
		
		/**
		 * Desctructeur
		 * @access	public
		 * @return	boolean
		 * @since 2.0
		*/
		
		public  function __desctuct(){
		
		}
	}
?>