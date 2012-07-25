<?php
	/**
	 * @file : feedGc.class.php
	 * @author : fab@c++
	 * @description : class gérant les flux rss
	 * @version : 2.0 bêta
	*/
	
	class feedGc{
		use errorGc;                            //trait
		
		protected $_rssFile                        = "";
		protected $_rssFileContent                 = "";
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
		
		public function getRssTitle(){
		}
		
		public function getRssLink(){
		}

		public function getRssDescription(){
		}

		public function getRssLanguage(){
		}

		public function getRssCopyright(){
		}

		public function getRssManagingEditor(){
		}

		public function getRssWebMaster(){
		}

		public function getRssPubDate(){
		}

		public function getRssLastBuildDate(){
		}

		public function getRssCategory(){
		}

		public function getRssGenerator(){
		}

		public function getRssDocs(){
		}

		public function getRssCloud(){
		}

		public function getRssTtl(){
		}

		public function getRssImage(){
		}

		public function getRssRating(){
		}

		public function getRssTextInput(){
		}

		public function getRssSkipHours(){
		}

		public function getRssSkipDays(){
		}
		
		public function getItemTitle(){
		}

		public function getItemLink(){
		}

		public function getItemDescription(){
		}

		public function getItemAuthor(){
		}

		public function getItemCategory(){
		}

		public function getItemComments(){
		}

		public function getItemEnclosure(){
		}

		public function getItemGuid(){
		}

		public function getItemPubdate(){
		}

		public function getItemSource(){
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
				return true;
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
		
		public  function __desctuct(){
		
		}
	}
?>