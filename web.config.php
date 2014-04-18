<?php
/*\
 | ------------------------------------------------------
 | @file : web.config.php
 | @author : fab@c++
 | @description : Configuration générale de l'application web et des connexions SQL
 | @version : 2.3 Bêta
 | ------------------------------------------------------
\*/

define('ASSET_PATH', 'asset/');
define('APP_PATH', 'app/');
define('SYSTEM_PATH', 'system/');
define('LOG_PATH', SYSTEM_PATH.'log/');
define('CACHE_PATH', SYSTEM_PATH.'cache/');
define('CONTROLLER_PATH', APP_PATH.'controller/');
define('MODEL_PATH', APP_PATH.'model/');
define('EVENT_PATH', APP_PATH.'event/');
define('APP_CONFIG_PATH', APP_PATH.'config/');
define('TEMPLATE_PATH', APP_PATH.'template/');
define('CLASS_PATH', SYSTEM_PATH.'core/');
define('LIB_PATH', SYSTEM_PATH.'lib/');
define('LANG_PATH', SYSTEM_PATH.'lang/');
define('ERRORDOCUMENT_PATH', 'ErrorDocument/');
define('GCSYSTEM_PATH', 'GCsystem/');
define('CLASS_SYSTEM_PATH', 'system/');
define('CLASS_HELPER_PATH', 'helper/');
define('BACKUP_PATH', SYSTEM_PATH.'backup/');

define('LANG_EXT', '.xml');
define('FILES_EXT', '.html');
define('TEMPLATE_EXT', '.tpl');
define('LOG_EXT', '.log');
define('CONTROLLER_EXT', '.class');
define('MODEL_EXT', '.model.class');
define('EVENT_EXT', '.class');

define('LOG_SYSTEM', 'system');
define('LOG_PHP', 'error');
define('LOG_HISTORY', 'history');
define('LOG_SQL', 'sql');
define('LOG_CRONS', 'crons');

define('FUNCTION_GENERIQUE', CONTROLLER_PATH.'function.php');
define('CLASS_GENERAL_INTERFACE', CLASS_PATH.CLASS_SYSTEM_PATH.'general.class.php');
define('CLASS_ENGINE', CLASS_PATH.CLASS_SYSTEM_PATH.'engine.class.php');
define('CLASS_CONTROLLER', CLASS_PATH.CLASS_SYSTEM_PATH.'controller.class.php');
define('CLASS_MODEL', CLASS_PATH.CLASS_SYSTEM_PATH.'model.class.php');
define('CLASS_LOG', CLASS_PATH.CLASS_SYSTEM_PATH.'log.class.php');
define('CLASS_CACHE', CLASS_PATH.CLASS_SYSTEM_PATH.'cache.class.php');
define('CLASS_TEMPLATE', CLASS_PATH.CLASS_SYSTEM_PATH.'template.class.php');
define('CLASS_LANG', CLASS_PATH.CLASS_SYSTEM_PATH.'lang.class.php');
define('CLASS_appDev', CLASS_PATH.CLASS_SYSTEM_PATH.'appDev.class.php');
define('CLASS_TERMINAL', CLASS_PATH.CLASS_SYSTEM_PATH.'terminal.class.php');
define('CLASS_FIREWALL', CLASS_PATH.CLASS_SYSTEM_PATH.'firewall.class.php');
define('CLASS_AUTOLOAD', CLASS_PATH.'autoload.php');
define('CLASS_CONFIG', CLASS_PATH.CLASS_SYSTEM_PATH.'config.class.php');
define('CLASS_HELPER', CLASS_PATH.CLASS_SYSTEM_PATH.'helper.class.php');
define('CLASS_ROUTER', CLASS_PATH.CLASS_SYSTEM_PATH.'router.class.php');
define('CLASS_ANTISPAM', CLASS_PATH.CLASS_SYSTEM_PATH.'antispam.class.php');
define('CLASS_INSTALL', CLASS_PATH.CLASS_SYSTEM_PATH.'install.class.php');
define('CLASS_CRON', CLASS_PATH.CLASS_SYSTEM_PATH.'cron.class.php');
define('CLASS_BACKUP', CLASS_PATH.CLASS_SYSTEM_PATH.'backup.class.php');
define('CLASS_ERROR_PERSO', CLASS_PATH.CLASS_SYSTEM_PATH.'errorperso.class.php');
define('CLASS_SQL', CLASS_PATH.CLASS_SYSTEM_PATH.'sql.class.php');
define('CLASS_EXCEPTION', CLASS_PATH.CLASS_SYSTEM_PATH.'exception.class.php');
define('CLASS_EVENT', CLASS_PATH.CLASS_SYSTEM_PATH.'event.class.php');
define('CLASS_EVENT_MANAGER', CLASS_PATH.CLASS_SYSTEM_PATH.'eventManager.class.php');

define('ROUTE', APP_CONFIG_PATH.'route.xml');
define('MODOCONFIG', APP_CONFIG_PATH.'modo.xml');
define('APPCONFIG', APP_CONFIG_PATH.'app.xml');
define('HELPER', APP_CONFIG_PATH.'helper.xml');
define('FIREWALL', APP_CONFIG_PATH.'firewall.xml');
define('ASPAM', APP_CONFIG_PATH.'antispam.xml');
define('ADDON', APP_CONFIG_PATH.'addon.xml');
define('CRON', APP_CONFIG_PATH.'cron.xml');
define('ERRORPERSO', APP_CONFIG_PATH.'errorperso.xml');

define('WARNING', 'WARNING');
define('ERROR', 'ERROR');
define('INFORMATION', 'INFORMATION');
define('FATAL', 'FATAL');

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

//dossier où est placé le framework à partir de la racine du répertoire. sous wamp par exemple, /GCsystem2.0
define('FOLDER', '');

//langue par défaut du système
define('DEFAULTLANG', 'fr');

/** Definit l'environnement dans lequel est effectué l'application :
* development : erreurs affichées + barre de développement et présence d'un terminal
* production : erreurs non affichées **/
define('ENVIRONMENT', 'development');

/* affiche le message de maintenance */
define('MAINTENANCE', false);

/* affiche la barre de dev ou non */
define('DEVTOOL', false);

/* mot de passe pour se connecter au terminal */
define('TERMINAL_MDP', 'mdp');

/* mettre à true pour sécuriser les variables superglobales */
define('SECUREGET', true);
define('SECUREPOST', true);

/* prefixe des constantes de l'utilisateur */
define('CONST_APP_PREFIXE', 'GCS_');

/* le nom des fichiers de cache sera hashé ou non */
define('CACHE_SHA1', false);

/* le nom des fichiers de cache sera hashé ou non */
define('LOG_ENABLED', true);

/* le cache peut-être désactivé pour pouvoir tester facilement quelque chose. Dans ce cas, le cache est mis toujours à 0 */
define('CACHE_ENABLED', true);

/* réduis la taille des fichiers html en supprimant les tabulations (attention avec les zones de texte) */
define('MINIFY_OUTPUT_HTML', false);

/* affiche les log d'erreurs [FATAL] (erreurs qui entravent gravement le fonctionnement de l'application) */
define('DISPLAY_ERROR_FATAL', false);

/* à ne pas modifier */
define('IMG_PATH', FOLDER.'/'.ASSET_PATH.'image/');
define('CSS_PATH', FOLDER.'/'.ASSET_PATH.'css/');
define('JS_PATH', FOLDER.'/'.ASSET_PATH.'js/');
define('FILE_PATH', FOLDER.'/'.ASSET_PATH.'file/');

define('IMG_PATH_PHP', ASSET_PATH.'image/');
define('CSS_PATH_PHP', ASSET_PATH.'css/');
define('JS_PATH_PHP', ASSET_PATH.'js/');
define('FILE_PATH_PHP', ASSET_PATH.'file/');

//timezone
define('TIMEZONE', 'Europe/Paris');