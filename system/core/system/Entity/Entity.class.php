<?php
	/*\
 	 | ------------------------------------------------------
	 | @file : Entity.class.php
	 | @author : fab@c++
	 | @description : entity, OO table representation
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/

	namespace system\Entity;

	use system\General\error;
	use system\General\langs;
	use system\General\facades;
	use system\Sql\Sql;

	class Entity {
		use error, facades, langs;

		protected $_table        =      '';
		protected $_columns      = array();
		protected $_bdd                   ;
		protected $_primary      =      '';
		protected $_uniq         =      0 ;

		const PARAM_INT                = 1;
		const PARAM_BOOL               = 5;
		const PARAM_NULL               = 0;
		const PARAM_STR                = 2;

		/**
		 * Constructor
		 * @access public
		 * @param &$profiler \system\Profiler\Profiler
		 * @param &$config \system\Config\Config
		 * @param &$request \system\Request\Request
		 * @param &$response \system\Response\Response
		 * @param $lang string
		 * @param $bdd \system\Pdo\Pdo
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __construct(&$profiler, &$config, &$request, &$response, $lang, $bdd) {
			$this->profiler = $profiler;
			$this->config   =   $config;
			$this->request  =  $request;
			$this->response = $response;
			$this->lang     =     $lang;

			$this->setBdd($bdd);
			$this->setTableDefinition();
			$this->_uniq = rand(0,100000);
		}

		/**
		 * Creation of the table
		 * @access public
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function setTableDefinition(){

		}

		/**
		 * configure the databse
		 * @access public
		 * @param $bdd pdo
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function setBdd($bdd) {
			$this->_bdd = $bdd;
		}

		/**
		 * permit to set a column
		 * @access public
		 * @param string $key
		 * @param array $value
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __set($key, $value) {
			if(array_key_exists($key, $this->_columns)){
				$this->_columns[''.$key.'']->setValue($value);
			}
		}

		/**
		 * permit to set a column
		 * @access public
		 * @param $key string
		 * @param $value array
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function set($key, $value) {
			if(array_key_exists($key, $this->_columns)){
				$this->_columns[''.$key.'']->setValue($value);
			}
		}

		/**
		 * add a column
		 * @access public
		 * @param $name string
		 * @param $options array
		 * @return void
		 * @since 3.0
		 * @package system\Entity
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
		 * @access public
		 * @param $name string
		 * @return void
		 * @since 3.0
		 * @package system\Entity
		*/

		public function setTable($name) {
			$this->_table = $name;
		}

		/**
		 * return a column
		 * @access public
		 * @param $key string
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __get($key) {
			if(array_key_exists($key, $this->_columns)){
				return $this->_columns[''.$key.'']->getValue();
			}
		}

		/**
		 * return a column
		 * @access public
		 * @param $key string
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function get($key) {
			if(array_key_exists($key, $this->_columns)){
				return $this->_columns[''.$key.'']->getValue();
			}
		}

		/**
		 * return the column name
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getTable() {
			return $this->_table;
		}

		/**
		 * return all the columns
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getColumns() {
			return $this->_columns;
		}

		/**
		 * return the the primary key name
		 * @access public
		 * @return string
		 * @since 3.0
		 * @package system\Entity
		*/

		public function getPrimary() {
			return $this->_primary;
		}

		/**
		 * insert a new line in the database
		 * @access public
		 * @return boolean
		 * @since 3.0
		 * @package system\Entity
		*/

		public function insert() {
			$sql = $this->sql($this->_bdd);
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
			$sql->fetch('insert_'.$this->_table.'_'.$this->_uniq, Sql::PARAM_FETCHINSERT);

			if($keyAutoIncrement != ''){
				$this->_columns[''.$keyAutoIncrement.'']->setValue($this->_bdd->lastInsertId());
			}

			return true;
		}

		/**
		 * update a line
		 * @access public
		 * @param $where string
		 * @param $var array
		 * @return boolean
		 * @since 3.0
		 * @package system\Entity
		*/

		public function update($where = '', $var = array()) {
			$sql = $this->sql($this->_bdd);
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
			$sql->fetch('update_'.$this->_table.'_'.$this->_uniq, Sql::PARAM_FETCHUPDATE);

			return true;
		}

		/**
		 * delete a line
		 * @access public
		 * @param $where string
		 * @param $var array
		 * @return boolean
		 * @since 3.0
		 * @package system\Entity
		*/

		public function delete($where = '', $var = array()) {
			$sql = $this->sql($this->_bdd);

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
			$sql->fetch('delete_'.$this->_table.'_'.$this->_uniq, Sql::PARAM_FETCHDELETE);

			return true;
		}

		/**
		 * insert a new line if the primary key is emport or update in the other case
		 * @access public
		 * @return boolean
		 * @since 3.0
		 * @package system\Entity
		*/

		public function flush(){
			if($this->_columns[''.$this->_primary.''] == NULL){
				$this->insert();
			}
			else{
				$val_arg = func_get_args();
				$this->update($val_arg[0], $val_arg[1]);
			}
		}

		/**
		 * Destructor
		 * @access public
		 * @since 3.0
		 * @package system\Entity
		*/

		public function __destruct(){
		}
	}