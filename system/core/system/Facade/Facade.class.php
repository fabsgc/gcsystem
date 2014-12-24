<?php
	/*\
	 | ------------------------------------------------------
	 | @file : facades.class.php
	 | @author : fab@c++
	 | @description : loader system to load core and helper classes
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Facade;

	use system\General\error;
	use system\Exception\Exception;

	class Facade{
		use error;

		/** 
		 * list of the class alias and the real class behind
		 * @var array
		*/

		private static $_alias = array(
			'sql'            =>                   '\system\Sql\Sql',
			'orm'            =>                   '\system\Orm\Orm',
			'spam'           =>             '\system\Security\Spam',
			'lang'           =>                 '\system\Lang\Lang',
			'cron'           =>                 '\system\Cron\Cron',
			'cache'          =>               '\system\Cache\Cache',
			'define'         =>             '\system\Define\Define',
			'helper'          =>      '\system\Facade\FacadeHelper',
			'entity'         =>       '\system\Facade\FacadeEntity',
			'library'        =>           '\system\Library\Library',
			'firewall'       =>         '\system\Security\Firewall',
			'template'       =>         '\system\Template\Template',
			'terminal'       =>         '\system\Terminal\Terminal',
			'assetManager'   => '\system\AssetManager\AssetManager',
			'entityMultiple' =>     '\system\Entity\EntityMultiple',
			'templateParser' =>    '\system\Template\templateParser'
		);

		/**
		 * load a system or helper class. This static method use ReflectionClass to instantiate the class with alias $name
		 * @access public
		 * @param $name string : class alias name
		 * @param $params array : list of parameters
		 * @param $stack array : execution pile
		 * @throws Exception if the method is unrecognized
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

				throw new Exception('undefined method "'.$name.'" in "'.$file.'" line '.$line);
			}
		}
	}