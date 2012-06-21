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
		
		public function query($nom, $query){
			$this->query[''.$nom.''] = $query;
		}

		public function  fetch($nom, $fetch = self::PARAM_FETCH){
			$query = $this->bdd->prepare(''.$this->query[''.$nom.''].'');
			
			foreach($this->var as $cle => $val){
				if(preg_match('#'.$cle.'#', $this->query[''.$nom.''])){
					if(is_array($val)){
						$query->bindValue($cle,$val[0],$val[1]);
					}
					else{
						switch(gettype($val)){
							case 'boolean' :
								$query->bindValue(":$cle",$val,self::PARAM_BOOL);
							break;
							
							case 'integer' :
								$query->bindValue(":$cle",$val,self::PARAM_INT);
							break;
							
							case 'double' :
								$query->bindValue(":$cle",$val,self::PARAM_STR);
							break;
							
							case 'string' :
								$query->bindValue(":$cle",$val,self::PARAM_STR);
							break;
							
							case 'NULL' :
								$query->bindValue(":$cle",$val,self::PARAM_NULL);
							break;
							
							default :
								$this->addError('type non géré');
							break;
						}
					}
				}
				
			}
			
			$query->execute();
			
			switch($fetch){
				case self::PARAM_FETCH : return $data = $query->fetchAll(); break;
				case self::PARAM_FETCHCOLUMN : return $data = $query->fetchColumn(); break;
				default : $this->addError('cette constante n\'existe pas'); break;
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