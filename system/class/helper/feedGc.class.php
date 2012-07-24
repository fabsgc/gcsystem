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
		
		protected function _setRss($rss){
			if(is_file($rss)){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $rss);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$this->_rssFileContent =  = curl_exec($ch);
				curl_close($ch);
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