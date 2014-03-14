<?php
session_start();

/*\
 | ------------------------------------------------------
 | @file : index.php
 | @author : fab@c++
 | @description : Controlleur central de l'application
 | @version : 2.1 bêta
 | ------------------------------------------------------
\*/

require_once('web.config.php');
require_once(CLASS_AUTOLOAD);

/* ---------- creation de la page -------------- */

$GLOBALS['appDevGc'] = new appDevGc();
$GLOBALS['controller'] = new Gcsystem();

$GLOBALS['controller']->init();

/* ------ articulation du site web-------- */

if(MAINTENANCE==false){ 
	$GLOBALS['controller']->route(); $GLOBALS['controller']->run(); 
}
elseif(MAINTENANCE==true){ 
	$GLOBALS['controller']->setMaintenance(); 
}

if(ENVIRONMENT == 'development' &&  ((DEVTOOL == true && $GLOBALS['appDevGc']->getShow() == true) || (DEVTOOL ==  false && $GLOBALS['appDevGc']->getShow() == true))){
	$GLOBALS['appDevGc']->show();
}