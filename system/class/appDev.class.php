<?php
	/*\
	 | ------------------------------------------------------
	 | @file : appDev.class.php
	 | @author : fab@c++
	 | @description : class à utiliser lors du développement de l'application
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class appDev{
		
		public  function __construct(){
			$tpl = new templateGC('GCsystemDev', 'GCsystemDev', 0);
			
			$tpl->assign(array(
				'text'=>"interface de développement en cours de création",
				'IMG_PATH'=>IMG_PATH
			));
				
			$tpl->show();
		}
		
		public  function __desctuct(){
		
		}
	}
?>