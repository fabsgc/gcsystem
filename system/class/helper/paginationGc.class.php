<?php
	/**
	 * @file : paginationGc.class.php
	 * @author : fab@c++
	 * @description : class gérant la pagination de façon automatique
	 * @version : 2.2 bêta
	*/
	
	class paginationGc{
		use errorGc, langInstance                  ;//trait
		
		protected $_byPage                = 2      ;
		protected $_entry                          ;
		protected $_buttonFl              = true   ;
		protected $_buttonBa              = true   ;
		protected $_url                            ;
		protected $_actualPage            = 0      ;
		protected $_nbrPage               = 0      ;
		
		protected $_paginationFirstBefore = true   ;
		protected $_paginationLastAfter   = true   ;
		protected $_paginationTotalPage   = true   ;
		
		protected $_paginationList        = array();
		protected $_paginationFirst       = array();
		protected $_paginationLast        = array();
		protected $_paginationBefore      = array();
		protected $_paginationAfter       = array();
		protected $_paginationCut         = false  ; //permet de spécifier cb de liens max à afficher à gauche et à droite du lien actuel
		
		protected $_data                  = array();
	
		/**
		 * Crée l'instance de la classe
		 * @access	public
		 * @return	void
		 * @param $data
		 *		buttonFl   : bouton premier dernier (true/false)
		 *		buttonBa   : bouton précédent suivant (true/false)
		 *		url        : url de la page à utiliser. Les endroits comprenant le numéro de la page devront être remplacés par "{page}"
		 *		bypage     : nombre d'entités par page
		 *		entry      : array() ou nombre indiquant le nombre d'entrées
		 *		actualPage : page actuelle
		 *		totalPage  : affichage du nombre total de pages (true/false)
		 *		cut        : combien de liens faut-il afficher avant et après le lien actuel
		 * @since 2.0
		*/
		public function __construct($data = array(), $lang=""){
			foreach($data as $cle => $val){
				switch($cle){
					case 'buttonFl':
						$this->_buttonFl = $val;
					break;
					
					case 'buttonBa':
						$this->_buttonBa = $val;
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
					
					case 'actualPage':
						$this->_actualPage = intval($val);
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
		
		public function useLang($sentence, $var = array(), $template = langGc::USE_NOT_TPL){
			return $this->_langInstance->loadSentence($sentence, $var, $template);
		}
		
		protected function _setData(){
			if(($this->_actualPage == 0 || $this->_actualPage == "")){
				$linkDisabled = false;
			}
			else{
				$linkDisabled = true;
			}
			
			if($this->_actualPage == "" || $this->_actualPage < 1){
				$this->_actualPage = 1;
			}
			
			$this->_nbrPage = ceil($this->_entry / $this->_byPage);

			if($this->_nbrPage == 0){
				$this->_nbrPage = 1;
			}
			
			if($this->_actualPage > $this->_nbrPage){
				$this->_actualPage = $this->_nbrPage;
			}
			if($this->_actualPage == 1){
				$this->_paginationFirstBefore = false;
			}
			if($this->_actualPage == $this->_nbrPage){
				$this->_paginationLastAfter = false;
			}
			
			if($this->_paginationCut == false || $this->_paginationCut > $this->_nbrPage){ //inutile de couper le nombre de lien si y a pas assez de page
				for($i = 1; $i<=$this->_nbrPage; $i++){
					if($i == $this->_actualPage && $linkDisabled != false){
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
				
				if(($this->_actualPage - $this->_paginationCut) > 0 && ($this->_actualPage + $this->_paginationCut) < $this->_nbrPage){
					for($i = $this->_actualPage - $this->_paginationCut; $i<=$this->_actualPage + $this->_paginationCut; $i++){
						if($i == $this->_actualPage && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				elseif(($this->_actualPage - $this->_paginationCut) > 0 && ($this->_actualPage + $this->_paginationCut) >= $this->_nbrPage){
					for($i = $this->_actualPage - $this->_paginationCut; $i<=$this->_nbrPage; $i++){
						if($i == $this->_actualPage && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				elseif(($this->_actualPage - $this->_paginationCut) <= 0 && ($this->_actualPage + $this->_paginationCut) < $this->_nbrPage){
					for($i = 1; $i<=$this->_actualPage + $this->_paginationCut; $i++){
						if($i == $this->_actualPage && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
				else{
					for($i = 1; $i<=$this->_nbrPage; $i++){
						if($i == $this->_actualPage && $linkDisabled != false){
							$this->_paginationList[$i] = false;
						}
						else{
							$this->_paginationList[$i] = preg_replace('#\{page\}#isU', $i, $this->_url);
						}
					}
				}
			}
		}
		
		/**
		 * affiche la pagination
		 * @access	public
		 * @return	string
		 * @param $template : chemin vers le tpl qui affichera la pagination
		 * @since 2.0
		*/
		public function show($template = ''){
			$rand = rand(0,2);

			if($template == ''){
				$tpl = new templateGc('GCsystem/GCpagination', 'pagination_'.$rand, 0, $this->_lang);
			}
			else{
				$tpl = new templateGc($template, 'pagination_'.$rand, 0, $this->_lang);
			}
			
			$tpl->assign(array(
				'paginationFirstLast'    => $this->_buttonFl,
				'paginationBeforeAfter'  => $this->_buttonBa,
				'actualPage'             => $this->_actualPage,
				'paginationFirstBefore'  => $this->_paginationFirstBefore,
				'paginationLastAfter'    => $this->_paginationLastAfter,
				'urlfirst'               => preg_replace('#\{page\}#isU', 1, $this->_url),
				'urllast'                => preg_replace('#\{page\}#isU', $this->_nbrPage, $this->_url),
				'urlbefore'              => preg_replace('#\{page\}#isU', $this->_actualPage-1, $this->_url),
				'urlafter'               => preg_replace('#\{page\}#isU', $this->_actualPage+1, $this->_url),
				'pagination'             => $this->_paginationList,
				'totalpage'              => $this->_paginationTotalPage,
				'nbrpage'                => $this->_nbrPage
			));
			
			$tpl->setShow(false);	
			return $tpl->show();
		}

		/**
		 * retourne la page actuelle
		 * @access	public
		 * @return	int
		 * @since 2.0
		*/
		public function getActualPage(){
			return $this->_actualPage;
		}

		/**
		 * retourne le nombre de pages
		 * @access	public
		 * @return	int
		 * @since 2.0
		*/
		public function getNbrPage(){
			if($this->_nbrPage != 0)
				return $this->_nbrPage;
			else
				return 1;
		}
		
		/**
		 * à partir de la totalité des données, retourne celles qu'il faut afficher
		 * @access	public
		 * @return	array
		 * @param $data : array() totalité des données
		 * @since 2.0
		*/
		public function getData($data){
			$this->_data = array();
			for($i = ((($this->_byPage * $this->_actualPage) - $this->_byPage)); $i<= (($this->_byPage * $this->_actualPage)-1);$i++){
				if(isset($data[$i])){
					array_push($this->_data, $data[$i]);
				}
			}
			
			return $this->_data;
		}

		/**
		 * pour la syntaxe LIMIT, retourne le premier entier, le deuxième étant le nombre de pages
		 * @access	public
		 * @return	int
		 * @since 2.0
		*/
		public function getDataFirstCase(){
			return $this->_byPage * $this->_actualPage - $this->_byPage;
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