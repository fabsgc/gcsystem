<?php
	/*\
	 | ------------------------------------------------------
	 | @file : sqlGc.class.php
	 | @author : fab@c++
	 | @description : class facilitant la gestion des requêtes SQL
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class sqlGc{
		protected $_var            = array();       //liste des variables
		protected $_query          = array();       //liste des requêtes
		protected $_bdd            = array();       //connexion sql
		protected $_error          = array();       //erreur
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
		
		public  function __construct($bdd){
			$this->_bdd = $bdd;
		}
		
		public function getVar(){
			return print_r($this->_var);
		}
		
		public function getQuery(){
			return $this->_requete;
		}
		
		public function query($nom, $query, $time=0){
			$this->_query[''.$nom.''] = $query;
			$this->time[''.$nom.''] = $time;
		}
		
		public function setVar($var = array()){
			foreach($var as $cle => $valeur){
				$this->_var[$cle] = $valeur;
			}
		}
		
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
		
		public function showError(){
			foreach($this->_error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function _addError($error){
			array_push($this->_error, $error);
		}
	}
?>