<?php
	/**
	 * @file : paginationGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la pagination de façon automatique
	 * @version : 2.0 bêta
	*/
	
	class paginationGc{
		use errorGc, langInstance                  ;                         //trait
		
		protected $_byPage                = 2      ;
		protected $_entry                          ;
		protected $_buttonFl              = true   ;
		protected $_buttonNp              = true   ;
		protected $_url                            ;
		protected $_pageActuel            = 0      ;
		protected $_nbrPage               = 0      ;
		
		protected $_paginationFirstBefore = true   ;
		protected $_paginationLastAfter   = true   ;
		protected $_paginationTotalPage   = true   ;
		
		protected $_paginationList        = array();
		protected $_paginationFirst       = array();
		protected $_paginationLast        = array();
		protected $_paginationBefore      = array();
		protected $_paginationAfter       = array();
		protected $_paginationCut         = false  ;
		
		protected $_data                  = array();
	
		/**
		 * Crée l'instance de la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function __construct($donnees = array(), $lang=""){
			foreach($donnees as $cle => $val){
				switch($cle){
					case 'buttonFl':
						$this->_buttonFl = $val;
					break;
					
					case 'buttonNp':
						$this->_buttonNp = $val;
					break;
					
					case 'url':
						$this->_url = $val;
					break;
					
					case 'bypage':
						$this->_byPage = intval($val);
					break; 
					
					case 'entry':
						if(is_array($val)){
							$this->_entry = count($val);
						}
						else{
							$this->_entry = intval($val);
						}
					break;
					
					case 'pageActuel':
						$this->_pageActuel = intval($val);
					break;
					
					case 'totalPage':
						$this->_paginationTotalPage = $val;
					break;
					
					case 'cut':
						$this->_paginationCut = intval($val);
					break;
				}
			}
			
			$this->_setData();
			
			if($lang==""){ $this->_lang=$this->getLangClient(); } else { $this->_lang=$lang; }
			$this->_createLangInstance();
		}
		
		protected function _createLangInstance(){
			$this->_langInstance = new langGc($this->_lang);
		}
		
		public function useLang($sentence){
			return $this->_langInstance->loadSentence($sentence);
		}
		
		protected function _setData(){
			if($this->_pageActuel == 0 || $this->_pageActuel == ""){
				$linkDisabled = false;
			}
			else{
				$linkDisabled = true;
			}
			
			
			if($this->_pageActuel == "" || $this->_pageActuel < 1){
				$this->_pageActuel = 1;
			}
			
			$this->_nbrPage = ceil($this->_entry / $this->_byPage);
			
			if($this->_pageActuel > $this->_nbrPage){
				$this->_pageActuel = $this->_nbrPage;
			}
			if($this->_pageActuel == 1){
				$this->_paginationFirstBefore = false;
			}
			if($this->_pageActuel == $this->_nbrPage){
				$this->_paginationLastAfter = false;
			}
			
			if($this->_paginationCut == false){
				for($i = 1; $i<=$this->_nbrPage; $i++){
					if($i == $this->_pageActuel && $linkDisabled != false){
						$this->_paginationList[$i] = false;
					}
					else{
						$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
					}
				}
			}
			else{
				if($this->_paginationCut > (($this->_nbrPage/2)-2)){
					$this->_paginationCut = intval(($this->_nbrPage/2));
				}
				if($this->_paginationCut < 1){
					$this->_paginationCut = 1;
				}
				
				if(($this->_pageActuel - $this->_paginationCut) > 0 && ($this->_pageActuel + $this->_paginationCut) < $this->_nbrPage){
					for($i = $this->_pageActuel - $this->_paginationCut; $i<=$this->_pageActuel + $this->_paginationCut; $i++){
						if($i == $this->_pageActuel && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				elseif(($this->_pageActuel - $this->_paginationCut) > 0 && ($this->_pageActuel + $this->_paginationCut) >= $this->_nbrPage){
					for($i = $this->_pageActuel - $this->_paginationCut; $i<=$this->_nbrPage; $i++){
						if($i == $this->_pageActuel && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				elseif(($this->_pageActuel - $this->_paginationCut) <= 0 && ($this->_pageActuel + $this->_paginationCut) < $this->_nbrPage){
					for($i = 1; $i<=$this->_pageActuel + $this->_paginationCut; $i++){
						if($i == $this->_pageActuel && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				else{
					for($i = 1; $i<=$this->_nbrPage; $i++){
						if($i == $this->_pageActuel && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
			}
		}
		
		public function show(){
			$rand = rand(0,2);
			$tpl = new templateGc('GCsystem\GCpagination', 'pagination_'.$rand, 0,'en');
			
			$tpl->assign(array(
				'paginationFirstLast'    => $this->_buttonFl,
				'paginationBeforeAfter'  => $this->_buttonNp,
				'pageActuel'             => $this->_pageActuel,
				'paginationFirstBefore'  => $this->_paginationFirstBefore,
				'paginationLastAfter'    => $this->_paginationLastAfter,
				'urlfirst'               => preg_replace('#\{page\}#isU', 1, $this->_url),
				'urllast'                => preg_replace('#\{page\}#isU', $this->_nbrPage, $this->_url),
				'urlbefore'              => preg_replace('#\{page\}#isU', $this->_pageActuel-1, $this->_url),
				'urlafter'               => preg_replace('#\{page\}#isU', $this->_pageActuel+1, $this->_url),
				'pagination'             => $this->_paginationList,
				'totalpage'              => $this->_paginationTotalPage,
				'nbrpage'                => $this->_nbrPage
			));
				
			$tpl->show();
			
			return $this->_pageActuel;
		}
		
		public function getData($data){
			$this->_data = array();
			for($i = ((($this->_byPage * $this->_pageActuel) - $this->_byPage)); $i<= (($this->_byPage * $this->_pageActuel)-1);$i++){
				if(isset($data[$i])){
					array_push($this->_data, $data[$i]);
				}
			}
			
			return $this->_data;
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