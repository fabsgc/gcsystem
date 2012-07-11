<?php
	/**
	 * @file : modoGc.class.php
	 * @author : fab@c++
	 * @description : class gérant le filtrage du contenu du site
	 * @version : 2.0 bêta
	*/
	
	class modoGc{
		use errorGc;                            //trait
		
		protected $_contenu                       ; //contenu à filtrer
		protected $_maxWord                  = 10  ; //contenu à filtrer
		protected $_insulte 				 = array('Agnaflai', 'Amagan', 'Anani Sikerim', 'Anayin Ami', 'Anus De Poulpe', 'Arschloch', 'Artaïl', 'Aspirateur à Bites', 'Aspirateur à Muscadet',
													 'Asshole', 'Ateye', 'Balafamouk', 'Baptou', 'Balai De Chiottes', 'Bassine A Foutre', 'Bite Molle', 'Bit molle',
													 'Bite de moll', 'Bit moll', 'Bleubite', 'Bordel', 'Bordel à Cul', 'Bordel de merde', 'Bordel de con', 'Bolosse', 'Bouche à Pipe',
													 'Bouffon', 'Bougre De Con', 'Bougre De Conne', 'Boursemolle', 'Boursouflure', 'Bouseux', 'Boz', 'Branleur', 'Butor', 'Cabron', 'Caja De Miera',
													 'Chancreux', 'Chien D\'infidèle', 'Chien Galeux', 'Chieur', 'Chiant', 'Clawi', 'Con', 'Conard', 'Connard', 'Connasse', 'Conne', 'Cono', 'Couille De Loup',
													 'Couille De Moineau', 'Couille De Tétard', 'Couille Molle', 'Couillon', 'Crevard', 'Crevure', 'Crétin', 'Cul De Babouin', 'Cul Terreux',
													 'Degueulasse', 'Ducon', 'Dégénéré Chromozomique', 'Embrayage', 'Emmerdeur', 'Encule Ta Mère', 'Enculeur De Mouches', 'enculé', 'Enfant De Tainpu',
													 'Face de bite', 'Face de caca', 'Face De Cul', 'Face De Pet', 'Face De Rat', 'Fils De Pute', 'Fouille Merde', 'Grognasse', 'Gros Con', 'Hijo De Puta', 'Lopette', 'Manche à Couille',
													 'Mange Merde', 'Merde', 'Mist', 'Moudlabite', 'Nike ta mère', 'Pauvre Con', 'Pendejo', 'Perra', 'Petite Merde',
													 'Ptit con', 'Petit con', 'Playboy De Superette', 'Pouffiasse', 'Putain',
													 'Pute', 'Put', 'Pute Au Rabais', 'Pétasse', 'Quéquette', 'Raclure De Bidet', 'Raclure De Chiotte', 'Sac à Merde', 'Safali', 'Salaud', 'Sale Pute', 'Sal pute', 'Sal put', 'Sale put', 'Sale con', 'Sal con', 'Sale connard', 'Sal connard', 'Sal conard', 'Sale connard', 'Sale conard', 'Saligaud',
													 'Salopard', 'Salope', 'Sous Merde', 'Spermatozoide Avarié', 'Suce Bites', 'Trou De Balle', 'Trou Du Cul', 'Trou du kul', 'Trou du qu', 'Trou du ku', 'Trou de bite', 'Tête De Bite', 'Va Te Faire', 'Va te faire niker', 'Vieux Con');
													//avec la participation de t1307
		protected $_parseInsulte             = array();
		protected $_i                        = array();
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @param string $contenu : contenu à modérer
		 * @param string $maxword : nombre maximum de mot toléré, une valeur de 0 entraîne un nombre illimité d'insultes toléré
		 * @since 2.0
		*/
		
		public  function __construct($contenu, $maxword=0){
			$this->_contenu = strval($contenu);
			$this->_maxWord = intval($maxword);
		}
		
		/**
		 * Fonction de parsage du message. Retourne soit true soit un array contenant la liste des insultes
		 * @access	public
		 * @return	array ou boolean
		 * @since 2.0
		*/
		
		public function parse(){
			$this->_parseInsulte = array();
			foreach($this->_insulte as $insulte){
				if(preg_match('`'.preg_quote($insulte).' `i', $this->_contenu)){
					array_push($this->_parseInsulte, $insulte);
				}
			}
			
			if(count($this->_parseInsulte) != 0){
				return $this->_parseInsulte;
			}
			else{
				return true;
			}
		}
		
		/**
		 * Fonction de censure du message, renvoie le texte censure ou non
		 * @access	public
		 * @return	string
		 * @since 2.0
		*/
		
		public function censure(){
			$this->_i            = 0;
			$this->_parseInsulte = array();
			foreach($this->_insulte as $insulte){
				if(preg_match('`'.preg_quote($insulte).' `i', $this->_contenu)){
					$this->_i++;
				}
			}
			
			if($this->_i++ > $this->_maxWord){
				$content = $this->_contenu;
				foreach($this->_insulte as $insulte){
					$content = preg_replace('`'.preg_quote($insulte).'`i', '***censuré***', $content);
				}
				return $content;
			}
			else{
				return $this->_contenu;
			}
		}
		
		/**
		 * Récupération du contenu du message
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getContenu(){
			return $this->_contenu;
		}
		
		/**
		 * Récupération du nombre de mot vulgaire maximum
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getMaxWord(){
			return $this->_maxWord;
		}
		
		/**
		 * Récupération du tableau contenant les insultes détectées
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getInsulte(){
			return $this->_parseInsulte;
		}
		
		/**
		 * Récupération du tableau contenant les insultes détectées en format html
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getInsulteHtml(){
			foreach($this->_parseInsulte as $valeur){
				$val .=$valeur.'<br />';
			}
			return $val;
		}
		
		/**
		 * Configuration du contenu à modérer
		 * @access	public
		 * @return	void
		 * @param string $contenu : contenu à modérer
		 * @since 2.0
		*/
		
		public function setContenu($contenu){
			$this->_contenu = strval($contenu);
			$this->_i            = 0      ;
			$this->_parseInsulte = array();
		}
		
		/**
		 * Configuration du nombre de mot vulgaire toléré, une valeur de 0 entraîne un nombre illimité d'insultes toléré
		 * @access	public
		 * @return	void
		 * @param string $max : nombre d'insultes toléré
		 * @since 2.0
		*/
		
		public function setMaxWord($max){
			$this->_maxWord = intval($maxu);
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