<?php
	/*\
	 | ------------------------------------------------------
	 | @file : orm.class.php
	 | @author : fab@c++
	 | @description : ORM du GCsystem. il permet de :
	 | 	faire des requêtes simples (find***() count***())
	 | 	faire des requêtes complexes via la class sql ou une chaîne de caractère + variables
	 |  	récupérer les données sous forme d'entités ou d'array brut
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class orm {
			use error;

			protected $_bdd         = NULL   ;
			protected $_data        = array();
			protected $_entities    = array();
			protected $_entity      =      '';
			protected $_multiple    =   false;
			protected $_numberQuery =       0;
			protected $_nameQuery   =      '';
			protected $_count       =       0;

			const PARAM_FETCH       =       0;
			const PARAM_COUNT       =       1;

			/**
			 * configure la base de données
			 * @access	public
			 * @param $bdd pdo
			 * @param $entity string
			 * @since 2.4
			 */
			public function __construct(pdo $bdd, $entity = '') {
				$this->setBdd($bdd);
				$this->setEntity($entity);
				$this->_entity = $entity;
				$this->_numberQuery = 0;

				$bt = debug_backtrace();
				$caller = array_shift($bt);

				$this->_nameQuery = '';
			}

			/**
			 * configure la base de données
			 * @access	public
			 * @param $bdd pdo
			 * @return	void
			 * @since 2.4
			 */
			public function setBdd($bdd) {
				$this->_bdd = $bdd;
			}

			/**
			 * configure l'entité à retourner
			 * @access	public
			 * @param $entity string
			 * @return	void
			 * @since 2.4
			 */
			public function setEntity($entity){
				if($entity == ''){
					$this->_multiple = true;
				}
				else if(!file_exists(ENTITY_PATH.$entity.ENTITY_EXT.'.php')){
					$this->_multiple = true;
					$this->_addError('l\'entité '.$entity.' n\'existe pas', __FILE__, __LINE__, ERROR);
				}
				else{
					$this->_multiple = false;
				}
			}

			/**
			 * clause WHERE + variable
			 * @access	public
			 * @param $where string
			 * @param $vars array
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findWhereVar($where, $vars = array(), $fetchType = self::PARAM_FETCH){
				$sql = new sql($this->_bdd);

				if($where != ''){
					foreach($vars as $key => $value){
						$sql->setVar(array($key => $value));
					}

					switch($fetchType){
						case self::PARAM_COUNT :
							$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT COUNT(*) FROM '.$this->_entity.' WHERE '.$where);
							$this->_count = $sql->fetch('orm_query_'.$this->_getNameQuery(), $fetchType);
						break;

						case self::PARAM_FETCH :
							$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT * FROM '.$this->_entity.' WHERE '.$where);
							$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), $fetchType);
						break;
					}
				}
				else{
					$this->find($fetchType);
				}
			}

			/**
			 * clause requête + variable
			 * @access	public
			 * @param $query string
			 * @param $vars array
			 * @param $fetchType int
			 * @return	$this
			 * @since 2.4
			 */
			public function findQueryVar($query, $vars = array(), $fetchType = self::PARAM_FETCH){
				$sql = new sql($this->_bdd);
				$sql->query('orm_query_'.$this->_getNameQuery(), $query);

				foreach($vars as $key => $value){
					$sql->setVar(array($key => $value));
				}

				switch($fetchType){
					case self::PARAM_COUNT :
						$this->_count = count($sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH));
					break;

					case self::PARAM_FETCH :
						$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * clause sql + nom
			 * @access	public
			 * @param $sql object
			 * @param $name string
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findSql($sql, $name, $fetchType = self::PARAM_FETCH){
				switch($fetchType){
					case self::PARAM_COUNT :
						$this->_count = count($sql->fetch($name, sql::PARAM_FETCH));
					break;

					case self::PARAM_FETCH :
						$this->_data = $sql->fetch($name, sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * clause pdo
			 * @access	public
			 * @param $pdo object
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findPdo($pdo, $fetchType = self::PARAM_FETCH){
				$sql = new sql($this->_bdd);
				$sql->query('orm_query_'.$this->_getNameQuery(), $pdo->getQuery());

				foreach($pdo->getBindValue() as $key => $value){
					$sql->setVar(array($key => $value));
				}

				switch($fetchType){
					case self::PARAM_COUNT :
						$this->_count = count($sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH));
					break;

					case self::PARAM_FETCH :
						$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * clause sql + nom. gère :
			 * 		ORDER BY, LIMIT, GROUP BY, DISTINCT
			 * 		WHERE : >, <, <=, <=, !=, =,
			 * @access	public
			 * @param $options array
			 * @param $vars array
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findOptions($options = array(), $vars = array(), $fetchType = self::PARAM_FETCH){
				$sql = new sql($this->_bdd);
				$varUniq = uniqid();

				$where    = '';
				$orderBy  = '';
				$limit    = '';
				$groupBy  = '';
				$distinct = '';
				$query    = '';

				foreach($options as $key => $value){
					switch($key){
						case '>' :
							$where .= ' '.$value[0].' > :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case '<' :
							$where .= ' '.$value[0].' < :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case '<=' :
							$where .= ' '.$value[0].' <= :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case '>=' :
							$where .= ' '.$value[0].' >= :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case '!=' :
							$where .= ' '.$value[0].' != :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case '=' :
							$where .= ' '.$value[0].' = :'.$value[0].'_'.$varUniq.' AND ';
							$sql->setVar($value[0].'_'.$varUniq, $value[1]);
						break;

						case 'ORDER BY':
							$orderBy .= ' ORDER BY '.$value[0].' '.$value[1].' ';
						break;

						case 'LIMIT':
							$limit .= ' LIMIT :'.$value[0].'_1_'.$varUniq.', :'.$value[1].'_2_'.$varUniq.' ';
							$sql->setVar($value[0].'_1_'.$varUniq, $value[0]);
							$sql->setVar($value[1].'_2_'.$varUniq, $value[1]);
						break;

						case 'GROUP BY':
							$groupBy .= ' GROUP BY '.$value;
						break;

						case 'DISTINCT':
							$distinct .= 'DISTINCT '.$value;
						break;
					}
				}

				switch($fetchType){
					case self::PARAM_COUNT :
						$query .= 'SELECT COUNT(*) FROM '.$this->_entity;
					break;

					case self::PARAM_FETCH :
						if($distinct != ''){
							$query .= 'SELECT '.$distinct.' FROM '.$this->_entity;
						}
						else{
							$query .= 'SELECT * FROM '.$this->_entity;
						}
					break;
				}

				$where = preg_replace('#AND $#isu', '', $where);

				if($where != ''){
					$query .= ' WHERE'.$where;
				}

				$query .= $orderBy;
				$query .= $groupBy;
				$query .= $limit;

				foreach($vars as $key => $value){
					$sql->setVar(array('key' => $key));
				}

				$sql->query('orm_query_'.$this->_getNameQuery(), $query);

				switch($fetchType){
					case self::PARAM_COUNT :
						$this->_count = count($sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH));
					break;

					case self::PARAM_FETCH :
						$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * clause sql + nom
			 * @access	public
			 * @param $key string int
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findPrimary($key, $fetchType = self::PARAM_FETCH){
				$entityName = '\entity\\'.$this->_entity;
				$entity = new $entityName($this->_bdd);

				$sql = new sql($this->_bdd);
				$sql->setVar(array('key' => $key));

				switch($fetchType){
					case self::PARAM_COUNT :
						$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT COUNT(*) FROM '.$this->_entity.' WHERE '.$entity->getPrimary().' = :key');
						$this->_count = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCHCOLUMN);
					break;

					case self::PARAM_FETCH :
						$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT * FROM '.$this->_entity.' WHERE '.$entity->getPrimary().' = :key');
						$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * clause requête simple
			 * @access	public
			 * @param $fetchType int
			 * @return	void
			 * @since 2.4
			 */
			public function findQuery($fetchType = self::PARAM_FETCH){
				$sql = new sql($this->_bdd);

				switch($fetchType){
					case self::PARAM_COUNT :
						$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT COUNT(*) FROM '.$this->_entity);
						$this->_count = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCHCOLUMN);
					break;

					case self::PARAM_FETCH :
						$sql->query('orm_query_'.$this->_getNameQuery(), 'SELECT * FROM '.$this->_entity);
						$this->_data = $sql->fetch('orm_query_'.$this->_getNameQuery(), sql::PARAM_FETCH);
					break;
				}
			}

			/**
			 * retourne des données
			 * @access	public
			 * @return	$this
			 * @since 2.4
			 */
			public function find(){
				$this->_numberQuery++;
				$num_arg = func_num_args();
				$val_arg = func_get_args();
				$this->_data = array();

				if($num_arg == 2 and is_string($val_arg[0]) && is_array($val_arg[1])){
					if($this->_multiple == false){
						$this->findWhereVar($val_arg[0], $val_arg[1], self::PARAM_FETCH);
					}
					else{
						$this->findQueryVar($val_arg[0], $val_arg[1], self::PARAM_FETCH);
					}
				}
				else if($num_arg == 2 && is_object($val_arg[0]) && is_string($val_arg[1])){
					$this->findSql((object)$val_arg[0], $val_arg[1], self::PARAM_FETCH);
				}
				else if($num_arg == 2 && is_array($val_arg[0]) && is_array($val_arg[1])){
					if($this->_multiple == false){
						$this->findOptions($val_arg[0], $val_arg[1], self::PARAM_FETCH);
					}
					else{
						$this->_addError('La requête est sur plusieurs entités, les paramètres de find() sont incorrectes.', __FILE__, __LINE__, ERROR);
					}
				}
				else if($num_arg == 1 && is_object($val_arg[0])){
					$this->findPdo((object)$val_arg[0], self::PARAM_FETCH);
				}
				else if($num_arg == 1 && (!is_string($val_arg[0]) || !is_object($val_arg[0]))){
					if($this->_multiple == false){
						$this->findPrimary($val_arg[0], self::PARAM_FETCH);
					}
					else{
						$this->find($val_arg[0], array(), self::PARAM_FETCH);
					}
				}
				else if($num_arg == 0){
					if($this->_multiple == false){
						$this->findQuery(self::PARAM_FETCH);
					}
					else{
						$this->_addError('La requête est sur plusieurs entités, les paramètres de find() sont incorrectes. 0/1 donné', __FILE__, __LINE__, ERROR);
					}
				}

				return $this;
			}

			/**
			 * retourne le nombre de données
			 * @access	public
			 * @return	int
			 * @since 2.4
			 */
			public function count(){
				$this->_numberQuery++;
				$num_arg = func_num_args();
				$val_arg = func_get_args();
				$this->_count = 0;

				if($num_arg == 2 and is_string($val_arg[0]) && is_array($val_arg[1])){
					if($this->_multiple == false){
						$this->findWhereVar($val_arg[0], $val_arg[1], self::PARAM_COUNT);
					}
					else{
						$this->findQueryVar($val_arg[0], $val_arg[1], self::PARAM_COUNT);
					}
				}
				else if($num_arg == 2 && is_object($val_arg[0]) && is_string($val_arg[1])){
					$this->findSql((object)$val_arg[0], $val_arg[1], self::PARAM_COUNT);
				}
				else if($num_arg == 2 && is_array($val_arg[0]) && is_array($val_arg[1])){
					if($this->_multiple == false){
						$this->findOptions($val_arg[0], $val_arg[1], self::PARAM_COUNT);
					}
					else{
						$this->_addError('La requête est sur plusieurs entités, les paramètres de find() sont incorrectes.', __FILE__, __LINE__, ERROR);
					}
				}
				else if($num_arg == 1 && is_object($val_arg[0])){
					$this->findPdo((object)$val_arg[0], self::PARAM_COUNT);
				}
				else if($num_arg == 1 && (!is_string($val_arg[0]) || !is_object($val_arg[0]))){
					if($this->_multiple == false){
						$this->findPrimary($val_arg[0], self::PARAM_COUNT);
					}
					else{
						$this->find($val_arg[0], array(), self::PARAM_COUNT);
					}
				}
				else if($num_arg == 0){
					if($this->_multiple == false){
						$this->findQuery(self::PARAM_COUNT);
					}
					else{
						$this->_addError('La requête est sur plusieurs entités, les paramètres de find() sont incorrectes. 0/1 donné', __FILE__, __LINE__, ERROR);
					}
				}

				return $this->_count;
			}

			/**
			 * retourne les données récupérées par la dernière requête sous forme d'entités
			 * @access	public
			 * @return	array
			 * @since 2.4
			 */
			public function data(){
				$this->_entities = array();

				foreach($this->_data as $value){
					if($this->_multiple == false){
						$entityName = '\entity\\'.$this->_entity;
						$entity = new $entityName($this->_bdd);

						foreach($value as $key => $value2){
							$entity->$key = $value2;
						}
					}
					else{
						$entity = new entityMultiple($value);
					}

					array_push($this->_entities, $entity);
				}

				return $this->_entities;
			}

			/**
			 * retourne les données récupérées par la dernière requête sous forme d'un array associatif
			 * @access	public
			 * @return	array
			 * @since 2.4
			 */
			public function toArray(){
				return $this->_data;
			}

			/**
			 * retourne le nom de la requête en cours
			 * @access	public
			 * @return	string
			 * @since 2.4
			 */
			protected  function _getNameQuery(){
				return $this->_nameQuery.$this->_numberQuery;
			}

			/**
			 * Destructeur
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function __destruct(){
			}
		}
	}