<?php
	/**
	 * @file : sqlGc.class.php
	 * @author : fab@c++
	 * @description : class facilitant la gestion des requêtes SQL
	 * @version : 2.0 bêta
	*/
	
	class sqlGc{
		use errorGc;                                    //trait
		
		protected $_var            = array();       //liste des variables
		protected $_query          = array();       //liste des requêtes
		protected $_bdd            = array();       //connexion sql
		protected $_cache                   ;       //référence vers un objet de type cache
		protected $_requete                 ;       //contient la requête sql
		protected $_data                    ;       //contient les données
		
		const PARAM_INT                 = 1;       //les paramètres des variables, en relation avec PDO::PARAM_
		const PARAM_BOOL                = 5;
		const PARAM_NULL                = 0;
		const PARAM_STR                 = 2;
		const PARAM_FETCH               = 0;
		const PARAM_FETCHCOLUMN         = 1;
		const PARAM_FETCHINSERT         = 2;
		const PARAM_FETCHUPDATE         = 3;
		
		/**
		 * Cr&eacute;e l'instance de la classe
		 * @access	public
		 * @return	void
		 * @param PDO : référence vers un objet de type PDO
		 * @since 2.0
		*/
		
		public  function __construct($bdd){
			$this->_bdd = $bdd;
		}
		
		/**
		 * Récupération sous la forme d'un array des variables transmises à la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getVar(){
			return print_r($this->_var);
		}
		
		/**
		 * modification du DAO
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function setBdd($bdd){
			$this->_bdd = $bdd;
		}
		
		/**
		 * Récupération sous la forme d'un array des requêtes sql transmises à la classe
		 * @access	public
		 * @return	void
		 * @since 2.0
		*/
		
		public function getQuery(){
			return $this->_requete;
		}
		
		/**
		 * Ajout d'une nouvelle requête SQL
		 * @access	public
		 * @return	void
		 * @param string $nom : le nom de la requête. Si vous donnez un nom existant déjà dans l'instance, l'ancienne requête sera écrasée
		 * @param string $query : Votre requête SQL avec la syntaxe de PDO (requête préparée)
		 * @param string $time : Le temps de mise en cache de la requêt
		 * @since 2.0
		*/
		
		public function query($nom, $query, $time=0){
			$this->_query[''.$nom.''] = $query;
			$this->time[''.$nom.''] = $time;
		}
		
		/**
		 * Configuration des variables à transmettre à la classe
		 * @access	public
		 * @return	void
		 * @param array $var : tableau contenant la liste des variables qui seront utilisées dans les requêtes<br />
		   premire syntaxe ex : array('id' => array(31, sqlGc::PARAM_INT), 'pass' => array("fuck", sqlGc::PARAM_STR))<br />
		   deuxième syntaxe ex : array('id' => 31, 'pass' => "fuck") si le type de la variable n\'est pas défini grâce aux constantes de la classe, le type sera définie directement pas la classe
		 * @since 2.0
		*/
		
		public function setVar($var = array()){
			foreach($var as $cle => $valeur){
				$this->_var[$cle] = $valeur;
			}
		}
		
		/**
		 * Exécution de la requête. Cette méthode retourne l'objet PDO juste après son exécution. (execute())
		 * @access	public
		 * @return	PDO
		 * @param string $nom : nom de la requête à exécuter
		 * @since 2.0
		*/
		
		public function execute($nom){
			$this->_requete = $this->_bdd->prepare(''.$this->_query[''.$nom.''].'');
			
			foreach($this->_var as $cle => $val){
				if(preg_match('#'.$cle.'#', $this->_query[''.$nom.''])){
					if(is_array($val)){
						$this->_requete->bindValue($cle,$val[0],$val[1]);
					}
					else{
						switch(gettype($val)){
							case 'boolean' :
								$this->_requete->bindValue(":$cle",$val,self::PARAM_BOOL);
							break;
							
							case 'integer' :
								$this->_requete->bindValue(":$cle",$val,self::PARAM_INT);
							break;
							
							case 'double' :
								$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
							break;
							
							case 'string' :
								$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
							break;
							
							case 'NULL' :
								$this->_requete->bindValue(":$cle",$val,self::PARAM_NULL);
							break;
							
							default :
								$this->_addError('type non géré');
							break;
						}
					}
				}
			}
			$this->_requete->execute();
			return $this->_requete;
		}
		
		/**
		 * Fetch de la requête. Cette méthode retourne plusieurs valeurs en fonctions des paramètres
		 * @access	public
		 * @return	array ou boolean
		 * @param string $nom : nom de la requête à fetcher
		 * @param string $fetch : type de fetch à réaliser. Il en existe 3 :<br />
		   sqlGc::PARAM_FETCH         : correspondant au fetch de PDO. Prévu pour une requête de type SELECT<br />
		   sqlGc::PARAM_FETCHCOLUMN   : correspondant au fetchcolumn de PDO. Prévu pour une requête de type SELECT COUNT<br />
		   sqlGc::PARAM_FETCHINSERT   : Prévu pour une requête de type INSERT<br />
		   sqlGc::PARAM_FETCHUPDATE   : Prévu pour une requête de type UPDATE<br />
		   valeur par défaut : sqlGc::PARAM_FETCH
		 * @since 2.0
		*/

		public function  fetch($nom, $fetch = self::PARAM_FETCH){
			$this->_cache = new cacheGc($nom.'.sql', "", $this->time[''.$nom.'']);
			
			if($this->_cache->isDie() || $fetch == self::PARAM_FETCHINSERT){
				$this->_requete = $this->_bdd->prepare(''.$this->_query[''.$nom.''].'');
				$GLOBALS['appDevGc']->addSql(''.$this->_query[''.$nom.''].'');
				
				foreach($this->_var as $cle => $val){
					if(preg_match('#'.$cle.'#', $this->_query[''.$nom.''])){
						if(is_array($val)){
							$this->_requete->bindValue($cle,$val[0],$val[1]);
							$GLOBALS['appDevGc']->addSql('_'.$cle.' : '.$val[0]);
						}
						else{
							switch(gettype($val)){
								case 'boolean' :
									$this->_requete->bindValue(":$cle",$val,self::PARAM_BOOL);
									$GLOBALS['appDevGc']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'integer' :
									$this->_requete->bindValue(":$cle",$val,self::PARAM_INT);
									$GLOBALS['appDevGc']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'double' :
									$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
									$GLOBALS['appDevGc']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'string' :
									$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
									$GLOBALS['appDevGc']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'NULL' :
									$this->_requete->bindValue(":$cle",$val,self::PARAM_NULL);
									$GLOBALS['appDevGc']->addSql($cle.' : '.$val);
								break;
								
								default :
									$this->_addError('type non géré');
								break;
							}
						}
					}
				}
				
				$GLOBALS['appDevGc']->addSql('####################################');
				$this->_requete->execute();
				
				switch($fetch){
					case self::PARAM_FETCH : $this->_data = $this->_requete->fetchAll(); break;
					case self::PARAM_FETCHCOLUMN : $this->_data = $this->_requete->fetchColumn(); break;
					case self::PARAM_FETCHINSERT : $this->_data = true; break;
					case self::PARAM_FETCHUPDATE : $this->_data = true; break;
					default : $this->_addError('cette constante n\'existe pas'); $this->_data=""; break;
				}
				
				switch($fetch){
					case self::PARAM_FETCH :
						case self::PARAM_FETCHCOLUMN : 
							$this->_cache->setVal($this->_data);
							$this->_cache->setCache($this->_data);
							return $this->_cache->getCache(); break;
					case self::PARAM_FETCHINSERT : return true; break;
					case self::PARAM_FETCHUPDATE : return true; break;
					default : $this->_addError('cette constante n\'existe pas'); $this->_data=""; break;
				}
			}
			else{
				return $this->_cache->getCache();
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