<?php
	/*\
	 | ------------------------------------------------------
	 | @file : autoload.php
	 | @author : fab@c++
	 | @description : Inclusion automatique de tous les fichiers nécessaires au système
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/

	namespace GCsystem{
		require_once(CLASS_GENERAL);
		require_once(CLASS_LOG);
		require_once(CLASS_EXCEPTION);

		class autoloader{
			public static function load($class){
				$class = preg_replace('#'.preg_quote('\\').'#isU', '/', $class);

				if(file_exists(CLASS_PATH.$class.'.class.php')){
					include_once(CLASS_PATH.$class.'.class.php');
				}

				if(file_exists(RESOURCE_PATH.$class.EVENT_EXT.'.php')){
					include_once(RESOURCE_PATH.$class.EVENT_EXT.'.php');
				}

				if(file_exists(APP_PATH.$class.ENTITY_EXT.'.php')){
					include_once(APP_PATH.$class.ENTITY_EXT.'.php');
				}
			}
		}

		spl_autoload_register(__NAMESPACE__ . "\\autoloader::load");
	}