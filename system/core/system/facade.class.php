<?php
	/*\
	 | ------------------------------------------------------
	 | @file : facade.class.php
	 | @author : fab@c++
	 | @description : loader system to load core and helper classes
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system{
		class facade{
			use error;

			/** 
			 * list of the class alias and the real class behind
 			 * @var array
 			*/

			private static $_alias = array(
				'sql'            =>            '\system\sql',
				'orm'            =>            '\system\orm',
				'spam'           =>           '\system\spam',
				'lang'           =>           '\system\lang',
				'cron'           =>           '\system\cron',
				'cache'          =>          '\system\cache',
				'define'         =>         '\system\define',
				'helper'          =>  '\system\helperFacade',
				'entity'         =>   '\system\entityFacade',
				'library'        =>        '\system\library',
				'firewall'       =>       '\system\firewall',
				'template'       =>       '\system\template',
				'terminal'       =>       '\system\terminal',
				'assetManager'   =>   '\system\assetManager',
				'entityMultiple' => '\system\entityMultiple',
				'templateParser' => '\system\templateParser'
			);

			/**
			 * load a system or helper class. This static method use ReflectionClass to instantiate the class with alias $name
			 * @access public
			 * @param $name string : class alias name
			 * @param $params array : list of parameters
			 * @param $stack array : execution pile
			 * @throws exception if the method is unrecognized
			 * @return mixed
			*/

			public static function load($name, $params, $stack){
				if(array_key_exists($name, self::$_alias)){
					$reflect  = new \ReflectionClass(self::$_alias[$name]);
					return $reflect->newInstanceArgs($params);
				}
				else{
					$file = '';
					$line = '';

					foreach ($stack as $value) {
						if($value['function'] == $name){
							$file = $value['file'];
							$line = $value['line'];
							break;
						}
					}

					throw new exception('undefined method "'.$name.'" in "'.$file.'" line '.$line, 1);
				}
			}
		}
	}