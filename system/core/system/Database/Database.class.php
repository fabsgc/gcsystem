<?php
	/*\
	 | ------------------------------------------------------
	 | @file : Database.class.php
	 | @author : fab@c++
	 | @description : enable database connection
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Database;

	use system\Pdo\Pdo;
	use system\General\error;
	use system\Exception\MissingDatabaseException;

	class Database{
		use error;

		/**
		 * create the database connection
		 * @access public
		 * @param $db array
		 * @throws \system\Exception\MissingDatabaseException
		 * @return mixed
		 * @since 3.0
		 * @package system\Database
		*/

		public static function connect($db){
			if(DATABASE == true){
				switch ($db['driver']){
					case 'pdo' :
						$options = [
							Pdo::ATTR_STATEMENT_CLASS => array('\system\Pdo\PdoStatement', array())
						];

						switch ($db['type']){
							case 'mysql':
								try{
									$sql = new Pdo('mysql:host='.$db['hostname'].';dbname='.$db['database'], $db['username'], $db['password'], $options);
								}
								catch (\PDOException $e){
									throw new MissingDatabaseException($e->getMessage().' / '.$e->getCode());
								}
							break;

							default :
								throw new MissingDatabaseException("Can't connect to SQL Database because the driver is not supported");
							break;
						}
					break;

					default :
						throw new MissingDatabaseException("Can't connect to SQL Database because the API is unrecognized");
					break;
				}

				return $sql;
			}
			else{
				return null;
			}
		}
	}