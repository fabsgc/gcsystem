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
			protected $_debugBindValues = array();

			protected function __construct(){
			}

			public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR)
			{
				$this->_debugBindValues[$parameter] = $value;
				parent::bindValue($parameter, $value, $data_type);
			}

			public function getQuery(){
				return $this->queryString;
			}

			public function getBindValue(){
				return $this->_debugBindValues;
			}

			public function _debugQuery($replaced = true){
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