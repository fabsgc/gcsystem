<?php
/*\

 | ------------------------------------------------------

 | @file : web.config.php
 | @author : fab@c++
 | @description : Configuration générale de l'application web et des connexions SQL
 | @version : 1.0 Bêta
 
 | ------------------------------------------------------
 
\*/

//chemins d'accès
define('ASSET_PATH', 'asset/');
define('APP_PATH', 'app/');
define('SYSTEM_PATH', 'system/');
define('LOG_PATH', SYSTEM_PATH.'log/');
define('CACHE_PATH', APP_PATH.'cache/');
define('FILE_PATH', ASSET_PATH.'file/');
define('RUBRIQUE_PATH', APP_PATH.'rubrique/');
define('MODEL_PATH', APP_PATH.'model/');
define('APP_CONFIG_PATH', APP_PATH.'config/');
define('TEMPLATE_PATH', APP_PATH.'template/');
define('CLASS_PATH', SYSTEM_PATH.'class/');
define('LIB_PATH', SYSTEM_PATH.'lib/');
define('LANG_PATH', SYSTEM_PATH.'lang/');
define('ERRORDUOCUMENT_PATH', 'ErrorDocument/');
define('GCSYSTEM_PATH', 'GCsystem/');
define('CLASS_SYSTEM_PATH', 'system/');
define('CLASS_HELPER_PATH', 'helper/');

//extensions de fichier
define('LANG_EXT', '.xml');
define('FILES_EXT', '.html');
define('TEMPLATE_EXT', '.tpl');
define('LOG_EXT', '.log');
define('RUBRIQUE_EXT', '.class');
define('MODEL_EXT', '.model.class');

//fonctions et class
define('FUNCTION_GENERIQUE', RUBRIQUE_PATH.'function.php');
define('CLASS_GENERAL_INTERFACE', CLASS_PATH.CLASS_SYSTEM_PATH.'generalGc.class.php');
define('CLASS_RUBRIQUE', CLASS_PATH.CLASS_SYSTEM_PATH.'Gcsystem.class.php');
define('CLASS_APPLICATION', CLASS_PATH.CLASS_SYSTEM_PATH.'applicationGc.class.php');
define('CLASS_MODEL', CLASS_PATH.CLASS_SYSTEM_PATH.'modelGc.class.php');
define('CLASS_LOG', CLASS_PATH.CLASS_SYSTEM_PATH.'logGc.class.php');
define('CLASS_CACHE', CLASS_PATH.CLASS_SYSTEM_PATH.'cacheGc.class.php');
define('CLASS_EXCEPTION', CLASS_PATH.CLASS_SYSTEM_PATH.'exceptionGc.class.php');
define('CLASS_TEMPLATE', CLASS_PATH.CLASS_SYSTEM_PATH.'templateGc.class.php');
define('CLASS_LANG', CLASS_PATH.CLASS_SYSTEM_PATH.'langGc.class.php');
define('CLASS_APPDEVGC', CLASS_PATH.CLASS_SYSTEM_PATH.'appDevGc.class.php');
define('CLASS_TERMINAL', CLASS_PATH.CLASS_SYSTEM_PATH.'terminalGc.class.php');
define('CLASS_FIREWALL', CLASS_PATH.CLASS_SYSTEM_PATH.'firewallGc.class.php');
define('CLASS_AUTOLOAD', CLASS_PATH.'autoload.php');
define('CLASS_CONFIG', CLASS_PATH.CLASS_SYSTEM_PATH.'configGc.class.php');
define('CLASS_PLUGIN', CLASS_PATH.CLASS_SYSTEM_PATH.'pluginGc.class.php');
define('CLASS_ROUTER', CLASS_PATH.CLASS_SYSTEM_PATH.'routerGc.class.php');
define('CLASS_ANTISPAM', CLASS_PATH.CLASS_SYSTEM_PATH.'antispamGc.class.php');
define('CLASS_INSTALL', CLASS_PATH.CLASS_SYSTEM_PATH.'installGc.class.php');
define('CLASS_CRON', CLASS_PATH.CLASS_SYSTEM_PATH.'cronGc.class.php');
define('CLASS_BACKUP', CLASS_PATH.CLASS_SYSTEM_PATH.'backupGc.class.php');

//lib
define('GESHI', LIB_PATH.'geshi/geshi.php');

//fichiers de config
define('ROUTE', APP_CONFIG_PATH.'routes.xml');
define('MODOGCCONFIG', APP_CONFIG_PATH.'modoGc.xml');
define('APPCONFIG', APP_CONFIG_PATH.'app.xml');
define('PLUGIN', APP_CONFIG_PATH.'plugin.xml');
define('FIREWALL', APP_CONFIG_PATH.'firewall.xml');
define('ASPAM', APP_CONFIG_PATH.'antispam.xml');
define('INSTALLED', APP_CONFIG_PATH.'installed.xml');
define('CRON', APP_CONFIG_PATH.'cron.xml');
define('ERRORPERSO', APP_CONFIG_PATH.'errorpersoGc.xml');

//logs messages
define('WARNING', 'WARNING');
define('ERROR', 'ERROR');
define('INFORMATION', 'INFORMATION');

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

//utilisation du routeur
define('REWRITE', true);

//utilisation du parefeu
define('SECURITY', true);

//utilisation de l'antispam
define('ANTISPAM', true);

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

/* prefixe des constantes de l'utilisateur */
define('CONST_APP_PREFIXE', 'USER_');

/* à ne pas modifier */
define('IMG_PATH', FOLDER.'/'.ASSET_PATH.'image/');
define('CSS_PATH', FOLDER.'/'.ASSET_PATH.'css/');
define('JS_PATH', FOLDER.'/'.ASSET_PATH.'js/');
define('UPLOAD_PATH', FOLDER.'/'.ASSET_PATH.'upload/');
define('JQUERYFILE', JS_PATH.'jquery.min.js');
define('JQUERYUIJS', JS_PATH.'jquery-ui.min.js');
define('JQUERYUICSS', CSS_PATH.'jquery-ui.css');