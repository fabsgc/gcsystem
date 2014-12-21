<?php
	/*\
 	 | ------------------------------------------------------
	 | @file : entity.class.php
	 | @author : fab@c++
	 | @description : ORM du GCsystem
	 | @version : 2.4 bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class entity {
			use error;

			protected $_table        =      '';
			protected $_columns      = array();
			protected $_bdd                   ;
			protected $_primary      =      '';
			protected $_uniq         =      0;

			const PARAM_INT                 = 1;
			const PARAM_BOOL                = 5;
			const PARAM_NULL                = 0;
			const PARAM_STR                 = 2;

			/**
			 * Constructeur de la classe
			 * @param $bdd pdo
			 * @access	public
			 * @since 2.4
			 */

			public function __construct($bdd) {
				$this->setBdd($bdd);
				$this->setTableDefinition();
				$this->_uniq = rand(0,100000);
			}

			/**
			 * Création de la table
			 * @param : PDO $bdd
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */
			public function setTableDefinition(){

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
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param string $key
			 * @param array $value
			 * @return	void
			 * @since 2.4
			 */
			public function __set($key, $value) {
				if(array_key_exists($key, $this->_columns)){
					$this->_columns[''.$key.'']->setValue($value);
				}
			}

			/**
			 * permet de modifier un des champs de la table
			 * @access	public
			 * @param string $key
			 * @param array $value
			 * @return	void
			 * @since 2.4
			 */
			public function set($key, $value) {
				if(array_key_exists($key, $this->_columns)){
					$this->_columns[''.$key.'']->setValue($value);
				}
			}

			/**
			 * ajoute une colonne à la table
			 * @param string $name
			 * @param array $options
			 * @access	public
			 * @return	void
			 * @since 2.4
			 */

			public function addColumn($name, $options = array()) {
				$this->_columns[''.$name.''] = new entityColumn();
				$this->_columns[''.$name.'']->setName($name);

				foreach($options as $key => $value){
					switch($key){
						case 'autoincrement':
							$this->_columns[''.$name.'']->setOptions(array($key => $value));
						break;

						case 'primary':
							$this->_columns[''.$name.'']->setOptions(array($key => $value));
							$this->_primary = $name;
						break;

						case 'type':
							switch($value){
								case self::PARAM_BOOL:
									$this->_columns[''.$name.'']->setOptions(array($key => $value));
								break;

								case self::PARAM_INT:
									$this->_columns[''.$name.'']->setOptions(array($key => $value));
								break;

								case self::PARAM_STR:
									$this->_columns[''.$name.'']->setOptions(array($key => $value));
								break;

								case self::PARAM_NULL:
									$this->_columns[''.$name.'']->setOptions(array($key => $value));
								break;

								default:
									$this->_columns[''.$name.'']->setOptions(array($key => self::PARAM_INT));
								break;
							}
						break;
					}
				}
			}

			/**
			 * modifie le nom de la table
			 * @param : string $name
			 * @access	public
			 * @return	void
			 * @since 2.4
			**/

			public function setTable($name) {
				$this->_table = $name;
			}

			/**
			 * permet de retourner un des champs de la table
			 * @access	public
			 * @param $key string
			 * @return	string
			 * @since 2.4
			 */
			public function __get($key) {
				if(array_key_exists($key, $this->_columns)){
					return $this->_columns[''.$key.'']->getValue();
				}
			}

			/**
			 * permet de retourner un des champs de la table
			 * @access	public
			 * @param $key string
			 * @return	string
			 * @since 2.4
			 */
			public function get($key) {
				if(array_key_exists($key, $this->_columns)){
					return $this->_columns[''.$key.'']->getValue();
				}
			}

			/**
			 * retourne le nom de la table
			 * @access	public
			 * @return	string
			 * @since 2.4
			 **/

			public function getTable() {
				return $this->_table;
			}

			/**
			 * retourne toutes les colonnes
			 * @access	public
			 * @return	string
			 * @since 2.4
			 **/

			public function getColumns() {
				return $this->_columns;
			}

			/**
			 * retourne le nom de la clef primaire
			 * @access	public
			 * @return	string
			 * @since 2.4
			 **/

			public function getPrimary() {
				return $this->_primary;
			}

			/**
			 * insert une nouvelle ligne dans la base de données
			 * @access	public
			 * @return	boolean
			 * @since 2.4
			 **/

			public function insert() {
				$sql = new sql($this->_bdd);
				$fields = '';
				$values = '';
				$keyAutoIncrement = '';

				foreach($this->_columns as $key => $value){
					if($value->getOptions()['autoincrement'] == false){
						$fields .= $key.', ';
						$values .= ':'.$key.'_var, ';
						if(array_key_exists('type', $value->getOptions())){
							$sql->setVar(array($key.'_var' => array($value->getValue(), $value->getOptions()['type'])));
						}
						else{
							$sql->setVar(array($key.'_var' => $value->getValue()));
						}
					}
					else{
						$keyAutoIncrement = $key;
					}
				}

				$fields = preg_replace('#, $#isU', '', $fields);
				$values = preg_replace('#, $#isU', '', $values);
				$query = 'INSERT INTO '.$this->_table.'('.$fields.') VALUES('.$values.')';
				
				$sql->query('insert_'.$this->_table.'_'.$this->_uniq, $query);
				$sql->fetch('insert_'.$this->_table.'_'.$this->_uniq, sql::PARAM_FETCHINSERT);

				if($keyAutoIncrement != ''){
					$this->_columns[''.$keyAutoIncrement.'']->setValue($this->_bdd->lastInsertId());
				}

				return true;
			}

			/**
			 * insert une nouvelle ligne dans la base de données
			 * @access	public
			 * @param $where string
			 * @param $var array
			 * @return	boolean
			 * @since 2.4
			 **/

			public function update($where = '', $var = array()) {
				$sql = new sql($this->_bdd);
				$query = '';

				if($where == '' && $this->_primary != ''){
					$where = $this->_columns[''.$this->_primary.'']->getName().' = :primary_var';
					$sql->setVar(array('primary_var' => $this->_columns[''.$this->_primary.'']->getValue()));
				}

				foreach($this->_columns as $key => $value){
					if($value->getOptions()['autoincrement'] == false){
						$query .= $key.' = :'.$key.'_var, ';

						if(array_key_exists('type', $value->getOptions())){
							$sql->setVar(array($key.'_var' => array($value->getValue(), $value->getOptions()['type'])));
						}
						else{
							$sql->setVar(array($key.'_var' => $value->getValue()));
						}
					}
				}

				foreach($var as $key => $value){
					$sql->setVar(array($key => $value));
				}

				$query = preg_replace('#, $#isU', '', $query);
				$query = 'UPDATE '.$this->_table.' SET '.$query.' WHERE '.$where;

				$sql->query('update_'.$this->_table.'_'.$this->_uniq, $query);
				$sql->fetch('update_'.$this->_table.'_'.$this->_uniq, sql::PARAM_FETCHUPDATE);

				return true;
			}

			/**
			 * supprime une ligne de la base de données
			 * @access	public
			 * @param $where string
			 * @param $var array
			 * @return	boolean
			 * @since 2.4
			 **/

			public function delete($where = '', $var = array()) {
				$sql = new sql($this->_bdd);

				echo $this->_primary;

				if($where == '' && $this->_primary != ''){
					$where = $this->_columns[''.$this->_primary.'']->getName().' = :primary_var';
					$sql->setVar(array('primary_var' => $this->_columns[''.$this->_primary.'']->getValue()));
				}

				foreach($var as $key => $value){
					$sql->setVar(array($key => $value));
				}

				$query = 'DELETE FROM '.$this->_table.' WHERE '.$where;

				$sql->query('delete_'.$this->_table.'_'.$this->_uniq, $query);
				$sql->fetch('delete_'.$this->_table.'_'.$this->_uniq, sql::PARAM_FETCHDELETE);

				return true;
			}

			/**
			 * insert une nouvelle ligne si la clé primaire est vide, sinon, met à jour
			 * @access	public
			 * @return	boolean
			 * @since 2.4
			 **/

			public function flush(){
				if($this->_primary != '' && $this->_columns[''.$this->_primary.'']->getValue() != ''){
					$val_arg = func_get_args();
					$this->update($val_arg[0], $val_arg[1]);
				}
				else{
					$this->insert();
				}
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