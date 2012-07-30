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
define('LOG_PATH', SYSTEM_PATH.'log/');

//chemin d'accèfichiers de log
define('CACHE_PATH', APP_PATH.'cache/');

//chemin d'accès fichiers divers
define('FILE_PATH', ASSET_PATH.'file/');

//chemin d'accès rubriques (controleur)
define('RUBRIQUE_PATH', APP_PATH.'rubrique/');

//chemin d'accès rubriques (controleur)
define('RUBRIQUE_EXT', '.class');

//chemin d'accès aux fichiers de config du projet
define('APP_CONFIG_PATH', APP_PATH.'config/');

//chemin d'accès des templates
define('TEMPLATE_PATH', APP_PATH.'template/');

//chemin d'accès fichiers class
define('CLASS_PATH', SYSTEM_PATH.'class/');

//chemin d'accès librairies
define('LIB_PATH', SYSTEM_PATH.'lib/');

//chemin d'accès fichiers de langues
define('LANG_PATH', SYSTEM_PATH.'lang/');

//chemin d'accès fichiers Error http
define('ERRORDUOCUMENT_PATH', 'ErrorDocument/');

//chemin d'accès fichiers GCsysem
define('GCSYSTEM_PATH', 'GCsystem/');

//extension fichiers de langues
define('LANG_EXT', '.xml');

//extension fichiers routes
define('ROUTE_EXT', '.xml');

//extension fichiers de config de l'pplication
define('CONFIG_EXT', '.xml');

// Définit l'extension des fichiers
define('FILES_EXT', '.html');

//fonction generique
define('FUNCTION_GENERIQUE', RUBRIQUE_PATH.'function.php');

//chemin class systeme
define('CLASS_SYSTEM_PATH', 'system/');

//chemin class helper
define('CLASS_HELPER_PATH', 'helper/');

//class mere gerant l'application
define('CLASS_GENERAL_INTERFACE', CLASS_PATH.CLASS_SYSTEM_PATH.'generalGc.class.php');

//class mere gerant l'application
define('CLASS_RUBRIQUE', CLASS_PATH.CLASS_SYSTEM_PATH.'Gcsystem.class.php');

//class application abstraite. gestion d'une page
define('CLASS_APPLICATION', CLASS_PATH.CLASS_SYSTEM_PATH.'applicationGc.class.php');

//class gerant les log
define('CLASS_LOG', CLASS_PATH.CLASS_SYSTEM_PATH.'logGc.class.php');

//class gerant les log
define('CLASS_CACHE', CLASS_PATH.CLASS_SYSTEM_PATH.'cacheGc.class.php');

//class gerant les captchas
define('CLASS_CAPTCHA', CLASS_PATH.CLASS_HELPER_PATH.'captchaGc.class.php');

//class gerant des exceptions
define('CLASS_EXCEPTION', CLASS_PATH.CLASS_SYSTEM_PATH.'exceptionGc.class.php');

//class gerant les templates
define('CLASS_TEMPLATE', CLASS_PATH.CLASS_SYSTEM_PATH.'templateGc.class.php');

//class formsGC
define('CLASS_FORMSGC', LIB_PATH.'FormsGC/formsGC.php');

//class lang
define('CLASS_LANG', CLASS_PATH.CLASS_SYSTEM_PATH.'langGc.class.php');

//class file
define('CLASS_FILE', CLASS_PATH.CLASS_HELPER_PATH.'fileGc.class.php');

//class dir
define('CLASS_DIR', CLASS_PATH.CLASS_HELPER_PATH.'dirGc.class.php');

//class picture
define('CLASS_PICTURE', CLASS_PATH.CLASS_HELPER_PATH.'pictureGc.class.php');

//class sql
define('CLASS_SQL', CLASS_PATH.CLASS_HELPER_PATH.'sqlGc.class.php');

//class appDevGc
define('CLASS_appDevGc', CLASS_PATH.CLASS_SYSTEM_PATH.'appDevGc.class.php');

//class zip
define('CLASS_ZIP', CLASS_PATH.CLASS_HELPER_PATH.'zipGc.class.php');

//class mail
define('CLASS_MAIL', CLASS_PATH.CLASS_HELPER_PATH.'mailGc.class.php');

//class bbcode
define('CLASS_BBCODE', CLASS_PATH.CLASS_HELPER_PATH.'bbcodeGc.class.php');

//class modo
define('CLASS_MODO', CLASS_PATH.CLASS_HELPER_PATH.'modoGc.class.php');

//class modo
define('CLASS_TERMINAL', CLASS_PATH.CLASS_SYSTEM_PATH.'terminalGc.class.php');

//class upload
define('CLASS_UPDLOAD', CLASS_PATH.CLASS_HELPER_PATH.'uploadGc.class.php');

//class download
define('CLASS_DOWNLOAD', CLASS_PATH.CLASS_HELPER_PATH.'downloadGc.class.php');

//class date
define('CLASS_DATE', CLASS_PATH.CLASS_HELPER_PATH.'dateGc.class.php');

//class texte
define('CLASS_TEXT', CLASS_PATH.CLASS_HELPER_PATH.'textGc.class.php');

//class feed
define('CLASS_FEED', CLASS_PATH.CLASS_HELPER_PATH.'feedGc.class.php');

//class js
define('CLASS_JS', CLASS_PATH.CLASS_HELPER_PATH.'jsGc.class.php');

//class object
define('CLASS_OBJECT', CLASS_PATH.CLASS_HELPER_PATH.'objectGc.class.php');

//class social
define('CLASS_SOCIAL', CLASS_PATH.CLASS_HELPER_PATH.'socialGc.class.php');

//autoload des class
define('CLASS_AUTOLOAD', CLASS_PATH.'autoload.php');

//class gérant le fichier de config de l'application
define('CLASS_CONFIG', CLASS_PATH.CLASS_SYSTEM_PATH.'configGc.class.php');

//class gérant l'url rewrite
define('CLASS_ROUTER', CLASS_PATH.CLASS_SYSTEM_PATH.'routerGc.class.php');

//class gérant la pagination
define('CLASS_PAGINATION', CLASS_PATH.CLASS_HELPER_PATH.'paginationGc.class.php');

//GESHI
define('GESHI', LIB_PATH.'geshi/geshi.php');

//extension des fichiers de template
define('TEMPLATE_EXT', '.tpl');

//extension des fichiers de log
define('LOG_EXT', '.log');

//erreur script rubrique not found
define('RUBRIQUE_NOT_FOUND', 'Une erreur relative au script s\'est produite. La rubrique demandée n\'a pas été trouvée');

//erreur variabels manquantes
define('RUBRIQUE_MISSING_PARAMETERS', 'Il manque des paramètres pour répondre à votre demande.');

//erreur variabels manquantes
define('ACTION_NOT_FOUND', 'L\'action demandée n\'a pas été trouvée.');

/* fichier jquery */
define('JQUERYFILE', JS_PATH.'jquery.min.js');
define('JQUERYUIJS', JS_PATH.'jquery-ui.min.js');
define('JQUERYUICSS', CSS_PATH.'jquery-ui.css');

//chemin route
define('ROUTE', APP_CONFIG_PATH.'routes'.ROUTE_EXT);

//chemin route
define('MODOGCCONFIG', APP_CONFIG_PATH.'modoGc.xml');

//chemin fichier de config de l'pplication
define('APPCONFIG', APP_CONFIG_PATH.'app'.CONFIG_EXT);

/* --------------parametres de connexion a la base de donnees------------------*/

$GLOBALS['db']['bdd']['hostname']  = "localhost";
$GLOBALS['db']['bdd']['username']  = "root";
$GLOBALS['db']['bdd']['password']  = "";
$GLOBALS['db']['bdd']['database']  = "test";
$GLOBALS['db']['bdd']['extension'] = "pdo";
$GLOBALS['db']['bdd']['sgbd']      = "mysql";

/* -------------- CONSTANTE RELATIVE AU SITE OBLIGATOIRES MAIS MODIFIABLES ----------------- */

//base du site (utile pour eviter les repetition et faciliter  les changements de bdd
define('BDD', 'test');

//connexion à la bdd, true ou false
define('CONNECTBDD', false);

//connexion à la bdd, true ou false
define('REWRITE', true);

//charset
define('CHARSET', 'UTF-8');

//favicon
define('FAVICON_PATH', 'no');

//dossier où est placé le framework à partir de la racine du répertoire. sous wamp par exemple, /GCsystem2.0
define('FOLDER', '/GCsystem');

//dossier où est placé le framework à partir de la racine du répertoire
define('DEFAULTLANG', 'fr');

/** Definit l'environnement dans lequel est effectué l'application :
* development : erreurs affichées + barre de développement et présence d'un terminal
* production : erreurs non affichées **/
define('ENVIRONMENT', 'development');

/* affiche le message de maintenance */
define('MAINTENANCE', false);

/* affiche la barre de dev ou non */
define('DEVTOOL', true);

/* mot de passe pour se connecter au terminal */
define('TERMINAL_MDP', 'mdp');

/* mettre à true pour pouvoir utiliser jquery et jquery ui */
define('JQUERY', true);

/* mettre à true pour sécuriser les variables superglobales */
define('SECUREGET', true);
define('SECUREPOST', true);