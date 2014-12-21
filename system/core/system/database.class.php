<?php
	/*\
	 | ------------------------------------------------------
	 | @file : database.class.php
	 | @author : fab@c++
	 | @description : enable database connection
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class database{
			use error;
			
			/**
			 * create the database connection
			 * @access public
			 * @return mixed
			 * @since 3.0
 			 * @package system
			*/
			
			public static function connect($db){
				if(DATABASE == true){
					switch ($db['driver']){
						case 'pdo' :
							$options = array(
								pdo::ATTR_STATEMENT_CLASS => array('\system\pdoStatement', array()),
							);

							switch ($db['type']){
								case 'mysql':
									try{
										$sql = new pdo('mysql:host='.$db['hostname'].';dbname='.$db['database'], $db['username'], $db['password'], $options);
									}
									catch (exception $e){
										$this->addError("Can't connect to MySQL Database", __FILE__, __LINE__, ERROR_ERROR);
										return null;
									}
								break;

								default :
									$this->addError("Can't connect to SQL Database because the driver isn't supported", __FILE__, __LINE__, ERROR_ERROR);
									return null;
								break;
							}
						break;

						default :
							$this->addError("Can't connect to SQL Database because the API is unknow", __FILE__, __LINE__, ERROR_ERROR);
							return null;
						break;
					}

					return $sql;
				}
				else{
					return null;
				}
			}
		}
	}