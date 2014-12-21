<?php
	/*\
	 | ------------------------------------------------------
	 | @file : sql.class.php
	 | @author : fab@c++
	 | @description : class facilitant la gestion des requêtes SQL
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class sql{
			use error, general;
			
			protected $_var            = array();       //liste des variables
			protected $_query          = array();       //liste des requêtes
			protected $_bdd            = array();       //connexion sql
			protected $_time           = array();       //temps de mise en cache
			protected $_cache                   ;       //référence vers un objet de type cache
			protected $_requete                 ;       //contient la requête sql
			protected $_data                    ;       //contient les données
			protected $_nameQuery      =      '';
			
			const PARAM_INT                 = 1;       //les paramètres des variables, en relation avec PDO::PARAM_
			const PARAM_BOOL                = 5;
			const PARAM_NULL                = 0;
			const PARAM_STR                 = 2;
			const PARAM_FETCH               = 0;
			const PARAM_FETCHCOLUMN         = 1;
			const PARAM_FETCHINSERT         = 2;
			const PARAM_FETCHUPDATE         = 3;
			const PARAM_NORETURN            = 4;
			const PARAM_FETCHDELETE         = 5;
			
			/**
			 * Crée l'instance de la classe
			 * @access	public
			 * @param $bdd pdo : référence vers un objet de type PDO
			 * @since 2.0
			*/
			
			public  function __construct($bdd){
				$this->_bdd = $bdd;

				$stack = debug_backtrace(0);
				$trace = $this->getStackTraceToString($stack);

				$this->_nameQuery = 'sql__'.$_GET['controller'].'_'.$_GET['action'].'__'.$trace.'__';
			}

			private function getStackTraceToString($stack){
				$max = 0;
				$trace = '';

				for($i = 1; $i < count($stack) && $max < 3; $i++){
					if(preg_match('#('.preg_quote('system\orm').')#isU', $stack[$i]['file'])){ //ORM
						$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'_'.$stack[$i-1]['line'].'__';
					}
					else{ //SQL direct
						$max++;
						if($max < 3)
							$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'_'.$stack[$i-1]['line'].'__';
						else
							$trace .= str_replace('\\', '-', $stack[$i]['class']).'_'.$stack[$i]['function'].'__';
					}
				}

				return $trace;
			}
			
			/**
			 * Récupération sous la forme d'un array des variables transmises à la classe
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getVar(){
				return print_r($this->_var);
			}
			
			/**
			 * modification du DAO
			 * @access	public
			 * @param $bdd pdo
			 * @return	void
			 * @since 2.0
			*/
			
			public function setBdd($bdd){
				$this->_bdd = $bdd;
			}
			
			/**
			 * Récupération sous la forme d'un array des requêtes sql transmises à la classe
			 * @access	public
			 * @return	string
			 * @since 2.0
			*/
			
			public function getQuery(){
				return $this->_requete;
			}
			
			/**
			 * Ajout d'une nouvelle requête SQL
			 * @access	public
			 * @return	void
			 * @param $nom string  : le nom de la requête. Si vous donnez un nom existant déjà dans l'instance, l'ancienne requête sera écrasée
			 * @param $query string : Votre requête SQL avec la syntaxe de PDO (requête préparée)
			 * @param $time int : Le temps de mise en cache de la requête
			 * @since 2.0
			*/
			
			public function query($nom, $query, $time=0){
				$this->_query[''.$nom.''] = $query;
				$this->_time[''.$nom.''] = $time;
			}
			
			/**
			 * Configuration des variables à transmettre à la classe
			 * @access	public
			 * @return	void
			 * @param $var : tableau contenant la liste des variables qui seront utilisées dans les requêtes<br />
			 *  premire syntaxe ex : array('id' => array(31, sql::PARAM_INT), 'pass' => array("fuck", sql::PARAM_STR))<br />
			 *  deuxième syntaxe ex : array('id' => 31, 'pass' => "fuck") si le type de la variable n'est pas défini grâce aux constantes de la classe, le type sera définie directement pas la classe
			 *  si jamais vous n'avez qu'une seule variable à passer, vous pouvez aussi le faire sans l'englober dans un array
			 * @since 2.0
			*/
			
			public function setVar($var){
				if(is_array($var)){
					foreach($var as $cle => $valeur){
						$this->_var[$cle] = $valeur;
					}
				}
				else if(func_num_args() == 2){
					$args = func_get_args();

					$this->_var[$args[0]] = $args[1];
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
				try{
					$this->_requete = $this->_bdd->prepare(''.$this->_query[''.$nom.''].'');
					
					foreach($this->_var as $cle => $val){
						if(preg_match('`:'.$cle.'[\s|,|\)|\(%]`', $this->_query[''.$nom.''].' ')){
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
										$this->_addError('type non géré', __FILE__, __LINE__, ERROR);
									break;
								}
							}
						}
					}

					$this->_requete->execute();
					
					return $this->_requete;
				}
				catch (exception $e) {
					$this->_addError($nom.' : '.$e->getMessage(), __FILE__, __LINE__, FATAL);
					return false;
				}
			}
			
			/**
			 * Fetch de la requête. Cette méthode retourne plusieurs valeurs en fonctions des paramètres
			 * @access	public
			 * @return	array
			 * @param $nom string : nom de la requête à fetcher
			 * @param $fetch int : type de fetch à réaliser. Il en existe 3 :
			 *  sql::PARAM_FETCH         : correspondant au fetch de PDO. Prévu pour une requête de type SELECT
			 *  sql::PARAM_FETCHCOLUMN   : correspondant au fetchcolumn de PDO. Prévu pour une requête de type SELECT COUNT
			 *  sql::PARAM_FETCHINSERT   : Prévu pour une requête de type INSERT
			 *  sql::PARAM_FETCHUPDATE   : Prévu pour une requête de type UPDATE
			 *  sql::PARAM_FETCHDELETE   : Prévu pour une requête de type DELETE
			 *  valeur par défaut : sql::PARAM_FETCH
			 * @since 2.0
			*/

			public function fetch($nom, $fetch = self::PARAM_FETCH){
				$GLOBALS['appDev']->setTimeExecUser('sql fetch '.$this->_nameQuery.$nom);

				if($this->_time[''.$nom.''] > 0){
					$this->_cache = new cache($this->_nameQuery.$nom.'.sql', "", $this->_time[''.$nom.'']);
				}

				if(
					(isset($this->_cache) && $this->_cache->isDie() && $this->_time[''.$nom.''] > 0) ||
					$this->_time[''.$nom.''] == 0 || $fetch == self::PARAM_FETCHINSERT ||
					$fetch == self::PARAM_FETCHUPDATE || $fetch == self::PARAM_FETCHDELETE
				){
					try {
						$this->_requete = $this->_bdd->prepare(''.$this->_query[''.$nom.''].'');
						$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, 'query', ''.$this->_query[''.$nom.''].'');
						$this->setErrorLog(LOG_SQL, '['.$_GET['controller'].']['.$_GET['action'].']['.$nom."] \n".$this->_query[''.$nom.''].'');
						
						foreach($this->_var as $cle => $val){
							if(preg_match('`:'.$cle.'[\s|,|\)|\(%]`', $this->_query[''.$nom.''].' ')){
								if(is_array($val)){
									$this->_requete->bindValue($cle,$val[0],$val[1]);
									$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle.' : '.$val[0]);
									$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val[0]);
								}
								else{
									switch(gettype($val)){
										case 'boolean' :
											$this->_requete->bindValue(":$cle",$val,self::PARAM_BOOL);
											$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle, $val);
											$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val);
										break;
										
										case 'integer' :
											$this->_requete->bindValue(":$cle",$val,self::PARAM_INT);
											$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle, $val);
											$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val);
										break;
										
										case 'double' :
											$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
											$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle, $val);
											$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val);
										break;
										
										case 'string' :
											$this->_requete->bindValue(":$cle",$val,self::PARAM_STR);
											$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle, $val);
											$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val);
										break;
										
										case 'NULL' :
											$this->_requete->bindValue(":$cle",$val,self::PARAM_NULL);
											$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, $cle, $val);
											$this->setErrorLog(LOG_SQL, ':'.$cle.' : '.$val);
										break;
										
										default :
											$this->setErrorLog(LOG_SQL, 'sql, variable '.$cle.', type non géré');
										break;
									}
								}
							}
						}

						$this->_requete->execute();
						
						switch($fetch){
							case self::PARAM_FETCH : $this->_data = $this->_requete->fetchAll(); break;
							case self::PARAM_FETCHCOLUMN : $this->_data = $this->_requete->fetchColumn(); break;
							case self::PARAM_FETCHINSERT : $this->_data = true; break;
							case self::PARAM_FETCHUPDATE : $this->_data = true; break;
							case self::PARAM_FETCHDELETE : $this->_data = true; break;
							case self::PARAM_NORETURN : $this->_data = true; break;
							default : $this->setErrorLog(LOG_SQL, 'la constante d\'exécution '.$fetch.' n\'existe pas'); break;
						}

						$GLOBALS['appDev']-> setTimeExecUser('sql fetch '.$this->_nameQuery.$nom);
						$GLOBALS['appDev']->addSql($this->_nameQuery.$nom, 'query-executed', ''.$this->_requete->_debugQuery().'');
						$this->setErrorLog(LOG_SQL, $this->_requete->_debugQuery());
						$this->setErrorLog(LOG_SQL, "########################################################################\n\n");

						switch($fetch){
							case self::PARAM_FETCH :
								if(isset($this->_cache)){
									$this->_cache->setVal($this->_data);
									$this->_cache->setCache();
								}

								return $this->_data; break;
							break;
							case self::PARAM_FETCHCOLUMN :
								if(isset($this->_cache)){
									$this->_cache->setVal($this->_data);
									$this->_cache->setCache();
								}

								return $this->_data; 
							break;
							case self::PARAM_FETCHINSERT : return true; break;
							case self::PARAM_FETCHUPDATE : return true; break;
							case self::PARAM_FETCHDELETE : return true; break;
							case self::PARAM_NORETURN : return true; break;
							default : $this->setErrorLog(LOG_SQL, 'la constante d\'exécution '.$fetch.' n\'existe pas'); break;
						}
					} 
					catch (exception $e) {
						$this->_addError($nom.' '.$e->getMessage(), __FILE__, __LINE__, FATAL);
						return false;
					}
				}
				else{
					$GLOBALS['appDev']-> setTimeExecUser('sql fetch '.$this->_nameQuery.$nom);

					if(isset($this->_cache)){
						return $this->_cache->getCache();
					}
					else{
						return false;
					}
				}
			}

			/**
			 * retourne les données sous forme d'entités
			 * @access	public
			 * @param $entity string
			 * @return	array
			 * @since 2.0
			 */
			public function data($entity = ''){
				$entities = array();

				foreach($this->_data as $value){
					if($entity != ''){
						$entityName = '\entity\\'.$entity;
						$entityObject = new $entityName($this->_bdd);

						foreach($value as $key => $value2){
							$entityObject->$key = $value2;
						}
					}
					else{
						$entityObject = new entityMultiple($value);
					}

					array_push($entities, $entityObject);
				}

				return $entities;
			}

			/**
			 * retourne les données sous forme d'entités
			 * @access	public
			 * @param $entity string
			 * @return	array
			 * @since 2.0
			 */
			public function toArray($entity = ''){
				return $this->_cache->getCache();
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
	}