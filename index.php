<?php
session_start();

/*\
 | ------------------------------------------------------
 | @file : index.php
 | @author : fab@c++
 | @description : Controlleur central de l'application
 | @version : 2.2 bêta
 | ------------------------------------------------------
\*/

require_once('web.config.php');
require_once(CLASS_AUTOLOAD);

$GLOBALS['appDev'] = new system\appDev();
$GLOBALS['controller'] = new system\engine(DEFAULTLANG);

$GLOBALS['controller']->init();

if(MAINTENANCE == false){ 
	$GLOBALS['controller']->route(); $GLOBALS['controller']->run(); 
}
elseif(MAINTENANCE == true){ 
	$GLOBALS['controller']->setMaintenance(); 
}

if(ENVIRONMENT == 'development' &&  ((DEVTOOL == true && $GLOBALS['appDev']->getShow() == true) || (DEVTOOL ==  false && $GLOBALS['appDev']->getShow() == true))){
	$GLOBALS['appDev']->show();
}