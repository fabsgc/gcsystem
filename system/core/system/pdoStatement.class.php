<?php
	/*\
	 | ------------------------------------------------------
	 | @file : pdoStatement.class.php
	 | @author : fab@c++
	 | @description : surchage de pdoStatement rajoutant quelques fonctions
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace system{
		class pdoStatement extends \PDOStatement{

			/** 
			 * list of vars for each query
 			 * @var array
 			*/

			protected $_debugBindValues = array();

			/**
			 * constructor
			 * @access public
			 * @since 3.0
			*/

			protected function __construct(){
			}

			/**
			 * override binvalue to keep in memory the vars
			 * @access public
			 * @param $parameter string
			 * @param $value string
			 * @param $data_type int
			 * @since 3.0
			*/

			public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR){
				$this->_debugBindValues[$parameter] = $value;
				parent::bindValue($parameter, $value, $data_type);
			}

			/**
			 * return the query string
			 * @access public
			 * @return string
			 * @since 3.0
			*/

			public function getQuery(){
				return $this->queryString;
			}

			/**
			 * return vars
			 * @access public
			 * @return array
			 * @since 3.0
			*/

			public function getBindValue(){
				return $this->_debugBindValues;
			}

			/**
			 * return query with vars or not
			 * @access public
			 * @param $replaced boolean
			 * @return string
			 * @since 3.0
			*/

			public function debugQuery($replaced = true){
				$q = $this->queryString;

				if (!$replaced) {
					return $q;
				}
				else{
					if(count($this->_debugBindValues) > 0){
						return preg_replace_callback('/:([0-9a-z_]+)/i', array($this, '_debugReplaceBindValue'), $q);
					}
					else{
						return $q;
					}
				}
			}

			/**
			 * replace vars in the query
			 * @access protected
			 * @param $m array
			 * @return string
			 * @since 3.0
			*/

			protected function _debugReplaceBindValue($m){
				$v = $this->_debugBindValues[':'.$m[1]];

				switch(gettype($v)){
					case 'boolean' :
						return $v;
					break;

					case 'integer' :
						return $v;
					break;

					case 'double' :
						return $v;
					break;

					case 'string' :
						return "'".addslashes($v)."'";
					break;

					case 'NULL' :
						return 'NULL';
					break;

					default :
						return 'NULL';
					break;
				}
			}
		}
	}