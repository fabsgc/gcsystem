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
		protected $var            = array();       //liste des variables
		protected $query          = array();       //liste des requêtes
		protected $bdd            = array();       //connexion sql
		protected $error          = array();       //erreur
		protected $cache                   ;       //référence vers un objet de type cache
		protected $time                    ;       //durée de mise en cache
		const PARAM_INT           = 1;             //les paramètres des variables, en relation avec PDO::PARAM_
		const PARAM_BOOL          = 5;
		const PARAM_NULL          = 0;
		const PARAM_STR           = 2;
		const PARAM_FETCH         = 0;
		const PARAM_FETCHCOLUMN   = 1;
		
		public  function __construct($bdd){
			$this->bdd = $bdd;
		}
		
		public function setVar($var){
			foreach($var as $cle => $valeur){
				$this->var[$cle] = $valeur;
			}
		}
		
		public function query($nom, $query, $time=0){
			$this->query[''.$nom.''] = $query;
			$this->time[''.$nom.''] = $time;
		}

		public function  fetch($nom, $fetch = self::PARAM_FETCH){
			$this->cache = new cache($nom.'.sql', "", $this->time[''.$nom.'']);
			
			if($this->cache->isDie()){
				$query = $this->bdd->prepare(''.$this->query[''.$nom.''].'');
				$GLOBALS['appdev']->addSql(''.$this->query[''.$nom.''].'');
				
				foreach($this->var as $cle => $val){
					if(preg_match('#'.$cle.'#', $this->query[''.$nom.''])){
						if(is_array($val)){
							$query->bindValue($cle,$val[0],$val[1]);
							$GLOBALS['appdev']->addSql('_'.$cle.' : '.$val[0]);
						}
						else{
							switch(gettype($val)){
								case 'boolean' :
									$query->bindValue(":$cle",$val,self::PARAM_BOOL);
									$GLOBALS['appdev']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'integer' :
									$query->bindValue(":$cle",$val,self::PARAM_INT);
									$GLOBALS['appdev']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'double' :
									$query->bindValue(":$cle",$val,self::PARAM_STR);
									$GLOBALS['appdev']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'string' :
									$query->bindValue(":$cle",$val,self::PARAM_STR);
									$GLOBALS['appdev']->addSql('_'.$cle.' : '.$val);
								break;
								
								case 'NULL' :
									$query->bindValue(":$cle",$val,self::PARAM_NULL);
									$GLOBALS['appdev']->addSql($cle.' : '.$val);
								break;
								
								default :
									$this->addError('type non géré');
								break;
							}
						}
					}
					
				}
				$GLOBALS['appdev']->addSql('####################################');
				$query->execute();
				
				switch($fetch){
					case self::PARAM_FETCH : $data = $query->fetchAll(); break;
					case self::PARAM_FETCHCOLUMN : $data = $query->fetchColumn(); break;
					default : $this->addError('cette constante n\'existe pas'); $data=""; break;
				}
				
				$this->cache->setVal($data);
				$this->cache->setCache($data);
				return $this->cache->getCache();
			}
			else{
				return $this->cache->getCache();
			}
		}
		
		private function showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		private function addError($error){
			array_push($this->error, $error);
		}
	}
?>