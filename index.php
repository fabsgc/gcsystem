<?php
session_start();

/*\
 | ------------------------------------------------------
 | @file : index.php
 | @author : fab@c++
 | @description : Controlleur central de l'application
 | @version : 2.0 bêta
 | ------------------------------------------------------
\*/

require_once('web.config.php');
require_once(CLASS_AUTOLOAD);

/* ---------- creation de la page -------------- */
$GLOBALS['appDevGc'] = new appDevGc();
$GLOBALS['rubrique'] = new Gcsystem(); //constructeur

/* ---------- gestion des erreurs (log) ----------- */
$c = new TestErrorHandling(); 

/* ---------- connexion SQL ----------------- */
if(CONNECTBDD == true) {$GLOBALS['base']=$GLOBALS['rubrique']->connectDatabase($db); }

/* ---------- démarrage de l'application ----------------- */
$GLOBALS['rubrique']->init();

/* --------------- fonctions générique --------- */
require_once(FUNCTION_GENERIQUE);

/* ------ articulation du site web -------- */

/* ------ appelez ici vos classes personnelles -------- */
/* ------ appelez ici vos classes personnelles -------- */

if(MAINTENANCE==false){
	$GLOBALS['rubrique']->route();
}
elseif(MAINTENANCE==true){
	$GLOBALS['rubrique']->setMaintenance();
}
if(ENVIRONMENT == 'development') $GLOBALS['appDevGc']->show();