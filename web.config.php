<?php
/*\

 | ------------------------------------------------------

 | @file : web.config.php
 | @author : fab@c++
 | @description : Configuration générale de l'application web et des connexions SQL
 | @version : 1.0 Bêta
 
 | ------------------------------------------------------
 
\*/

//asset
define('ASSET_PATH', 'asset/');

//app
define('APP_PATH', 'app/');

//system
define('SYSTEM_PATH', 'system/');

//chemin d'accès fichiers css
define('CSS_PATH', ASSET_PATH.'css/');

//chemin d'accès fichiers javascript
define('JS_PATH', ASSET_PATH.'js/');

//chemin d'accès fichiers javascript
define('IMG_PATH', ASSET_PATH.'image/');

//chemin d'accès fichiers d'upload
define('UPLOAD_PATH', ASSET_PATH.'upload/');

//chemin d'accès fichiers de log
define('LOG_PATH', APP_PATH.'log/');

//chemin d'accès fichiers de log
define('CACHE_PATH', APP_PATH.'cache/');

//chemin d'accès fichiers divers
define('FILE_PATH', ASSET_PATH.'file/');

//chemin d'accès rubriques (controleur)
define('RUBRIQUE_PATH', APP_PATH.'rubrique/');

//chemin d'accès des includes (vue+modele)
define('INCLUDE_PATH', APP_PATH.'include/');

//chemin d'accès des includes (vue+modele)
define('SQL_PATH', APP_PATH.'sql/');

//chemin d'accès des formulaires
define('FORMS_PATH', APP_PATH.'forms/');

//chemin d'accès des templates
define('TEMPLATE_PATH', APP_PATH.'template/');

//chemin d'accès fichiers class
define('CLASS_PATH', SYSTEM_PATH.'class/');

//chemin d'accès librairies
define('LIB_PATH', SYSTEM_PATH.'lib/');

//chemin d'accès fichiers de langues
define('LANG_PATH', SYSTEM_PATH.'lang/');

//extension fichiers de langues
define('LANG_EXT', '.xml');

// Définit l'extension des fichiers
define('FILES_EXT', '.html');

//fonction generique
define('FUNCTION_GENERIQUE', INCLUDE_PATH.'function.php');

//class mere gerant l'application
define('CLASS_GENERAL_INTERFACE', CLASS_PATH.'general.class.php');

//class mere gerant l'application
define('CLASS_RUBRIQUE', CLASS_PATH.'rubrique.class.php');

//class gerant les log
define('CLASS_LOG', CLASS_PATH.'log.class.php');

//class gerant les log
define('CLASS_CACHE', CLASS_PATH.'cache.class.php');

//class gerant les captchas
define('CLASS_CAPTCHA', CLASS_PATH.'captcha.class.php');

//class gerant des exceptions
define('CLASS_EXCEPTION', CLASS_PATH.'exceptionGc.class.php');

//class gerant les templates
define('CLASS_TEMPLATE', CLASS_PATH.'templateGc.class.php');

//class formsGC
define('CLASS_FORMSGC', LIB_PATH.'FormsGC/formsGC.php');

//class lang
define('CLASS_LANG', CLASS_PATH.'lang.class.php');

//class file
define('CLASS_FILE', CLASS_PATH.'file.class.php');

//class dir
define('CLASS_DIR', CLASS_PATH.'dir.class.php');

//class picture
define('CLASS_PICTURE', CLASS_PATH.'picture.class.php');

//class sql
define('CLASS_SQL', CLASS_PATH.'sqlGc.class.php');

//class appDev
define('CLASS_APPDEV', CLASS_PATH.'appDev.class.php');

//class zip
define('CLASS_ZIP', CLASS_PATH.'zip.class.php');

//class mail
define('CLASS_MAIL', CLASS_PATH.'mail.class.php');

//extension des fichiers de fonctions
define('FUNCTION_EXT', '.function');

//extension des fichiers de fonctions
define('SQL_EXT', '.sql');

//extension des fichiers de fonctions
define('FORMS_EXT', '.forms');

//extension des fichiers de template
define('TEMPLATE_EXT', '.tpl');

//erreur script rubrique not found
define('RUBRIQUE_NOT_FOUND', 'Une erreur relative au script s\'est produite.');

//erreur variabels manquantes
define('RUBRIQUE_MISSING_PARAMETERS', 'Il manque des paramètre pour répondre à votre demande.');

//erreur variabels manquantes
define('ACTION_NOT_FOUND', 'La rubrique n\'existe pas.');

//charset
define('CHARSET', 'iso-8859-15');

//favicon
define('FAVICON_PATH', 'no');

//erreur acces interdit
define('RUBRIQUE_FORBIDDEN', 'Vous n\'ête pas autorisé(e) à accéder à cette page.');

//dossier où est placé le framework à partir de la racine du répertoire
define('FOLDER', 'GCsystem');

/** Definit l'environnement dans lequel est effectué l'application :
* development : erreurs affichées
* production : erreurs non affichées **/
define('ENVIRONMENT', 'development');

/* --------------parametres de connexion a la base de donnees------------------*/

$db['bdd']['hostname'] = "localhost";
$db['bdd']['username'] = "root";
$db['bdd']['password'] = "";
$db['bdd']['database'] = "8_legeekcafe";
$db['bdd']['extension'] = "pdo";

/* -------------- CONSTANTE RELATIVE AU SITE ----------------- */

//base du site (utile pour eviter les repetition et faciliter  les changements de bdd
define('BDD', '8_legeekcafe');