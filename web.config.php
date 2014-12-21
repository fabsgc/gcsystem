<?php
/*\
 | ------------------------------------------------------
 | @file : web.config.php
 | @author : fab@c++
 | @description : Configuration of the application
 | @version : 3.0 Bêta
 | ------------------------------------------------------
\*/

define('VERSION', '3.0');

/* ############### PATH ############### */

define('APP_PATH', 'app/');
define('APP_CACHE_PATH', APP_PATH.'cache/');
define('APP_CACHE_PATH_DEFAULT', APP_PATH.'cache/default/');
define('APP_CACHE_PATH_TEMPLATE', APP_PATH.'cache/template/');
define('APP_LOG_PATH', APP_PATH.'log/');
define('APP_RESOURCE_PATH', APP_PATH.'resource/');
define('APP_RESOURCE_CONFIG_PATH', APP_RESOURCE_PATH.'config/');
define('APP_RESOURCE_EVENT_PATH', APP_RESOURCE_PATH.'event/');
define('APP_RESOURCE_LANG_PATH', APP_RESOURCE_PATH.'lang/');
define('APP_RESOURCE_ENTITY_PATH', APP_RESOURCE_PATH.'entity/');
define('APP_RESOURCE_LIBRARY_PATH', APP_RESOURCE_PATH.'library/');
define('APP_RESOURCE_TEMPLATE_PATH', APP_RESOURCE_PATH.'template/');
define('APP_RESOURCE_TEMPLATE_ERROR_PATH', APP_RESOURCE_TEMPLATE_PATH.'error/');

define('SRC_PATH', 'src/');
define('SRC_CONTROLLER_PATH', 'controller/');
define('SRC_CONTROLLER_FUNCTION_PATH', SRC_CONTROLLER_PATH.'function.php');
define('SRC_MODEL_PATH', 'model/');
define('SRC_RESOURCE_PATH', 'resource/');
define('SRC_RESOURCE_CONFIG_PATH', SRC_RESOURCE_PATH.'config/');
define('SRC_RESOURCE_EVENT_PATH', SRC_RESOURCE_PATH.'event/');
define('SRC_RESOURCE_LANG_PATH', SRC_RESOURCE_PATH.'lang/');
define('SRC_RESOURCE_LIBRARY_PATH', SRC_RESOURCE_PATH.'library/');
define('SRC_RESOURCE_TEMPLATE_PATH', SRC_RESOURCE_PATH.'template/');

define('WEB_PATH', 'web/');
define('WEB_CSS_PATH', 'css/');
define('WEB_FILE_PATH', 'file/');
define('WEB_IMAGE_PATH', 'image/');
define('WEB_JS_PATH', 'js/');

define('ASSET_PATH', 'asset/');

define('SYSTEM_PATH', 'system/');
define('SYSTEM_BACKUP', SYSTEM_PATH.'backup/');
define('SYSTEM_CORE_PATH', SYSTEM_PATH.'core/');
define('SYSTEM_CORE_SYSTEM_PATH', SYSTEM_CORE_PATH.'system/');
define('SYSTEM_CORE_HELPER_PATH', SYSTEM_CORE_PATH.'helper/');

define('EXT_LANG', '.xml');
define('EXT_TEMPLATE', '.tpl');
define('EXT_COMPILED_TEMPLATE', '.tpl.compil.php.cache');
define('EXT_LOG', '.log');
define('EXT_CONTROLLER', '.class');
define('EXT_MODEL', '.model.class');
define('EXT_EVENT', '.event.class');
define('EXT_ENTITY', '.entity.class');

define('LOG_SYSTEM', 'system');
define('LOG_HISTORY', 'history');
define('LOG_SQL', 'sql');
define('LOG_ERROR', 'error');
define('LOG_CRONS', 'cron');
define('LOG_EVENT', 'event');

define('APP_FUNCTION', APP_PATH.'function.php');
define('SRC_FUNCTION', SRC_CONTROLLER_PATH.'function.php');

define('APP_CONFIG_PLUGIN', APP_RESOURCE_CONFIG_PATH.'plugin.xml');
define('APP_CONFIG_SRC', APP_RESOURCE_CONFIG_PATH.'src.xml');
define('APP_CONFIG_CRON', APP_RESOURCE_CONFIG_PATH.'cron.xml');
define('APP_CONFIG_DEFINE', APP_RESOURCE_CONFIG_PATH.'define.xml');
define('APP_CONFIG_LIBRARY', APP_RESOURCE_CONFIG_PATH.'library.xml');
define('APP_CONFIG_SPAM', APP_RESOURCE_CONFIG_PATH.'spam.xml');

define('SRC_CONFIG_CRON', SRC_RESOURCE_CONFIG_PATH.'cron.xml');
define('SRC_CONFIG_DEFINE', SRC_RESOURCE_CONFIG_PATH.'define.xml');
define('SRC_CONFIG_FIREWALL', SRC_RESOURCE_CONFIG_PATH.'firewall.xml');
define('SRC_CONFIG_LIBRARY', SRC_RESOURCE_CONFIG_PATH.'library.xml');
define('SRC_CONFIG_ROUTE', SRC_RESOURCE_CONFIG_PATH.'route.xml');
define('SRC_CONFIG_SPAM', SRC_RESOURCE_CONFIG_PATH.'spam.xml');

define('CLASS_SRC', SYSTEM_CORE_SYSTEM_PATH.'src.class.php');
define('CLASS_BACKUP', SYSTEM_CORE_SYSTEM_PATH.'backup.class.php');
define('CLASS_DEFINE', SYSTEM_CORE_SYSTEM_PATH.'define.class.php');
define('CLASS_AUTOLOAD', SYSTEM_CORE_PATH.'autoload.php');
define('CLASS_CACHE', SYSTEM_CORE_SYSTEM_PATH.'cache.class.php');
define('CLASS_CRON', SYSTEM_CORE_SYSTEM_PATH.'cron.class.php');
define('CLASS_CONTROLLER', SYSTEM_CORE_SYSTEM_PATH.'controller.class.php');
define('CLASS_MODEL', SYSTEM_CORE_SYSTEM_PATH.'model.class.php');
define('CLASS_ENGINE', SYSTEM_CORE_SYSTEM_PATH.'engine.class.php');
define('CLASS_EXCEPTION', SYSTEM_CORE_SYSTEM_PATH.'exception.class.php');
define('CLASS_EVENT', SYSTEM_CORE_SYSTEM_PATH.'event.class.php');
define('CLASS_EVENT_MANAGER', SYSTEM_CORE_SYSTEM_PATH.'eventManager.class.php');
define('CLASS_FACADE', SYSTEM_CORE_SYSTEM_PATH.'facade.class.php');
define('CLASS_FIREWALL', SYSTEM_CORE_SYSTEM_PATH.'firewall.class.php');
define('CLASS_GENERAL', SYSTEM_CORE_SYSTEM_PATH.'general.class.php');
define('CLASS_LIBRARY', SYSTEM_CORE_SYSTEM_PATH.'library.class.php');
define('CLASS_INSTALL', SYSTEM_CORE_SYSTEM_PATH.'install.class.php');
define('CLASS_LANG', SYSTEM_CORE_SYSTEM_PATH.'lang.class.php');
define('CLASS_PROFILER', SYSTEM_CORE_SYSTEM_PATH.'profiler.class.php');
define('CLASS_TEMPLATE', SYSTEM_CORE_SYSTEM_PATH.'template.class.php');
define('CLASS_TERMINAL', SYSTEM_CORE_SYSTEM_PATH.'terminal.class.php');
define('CLASS_ROUTER', SYSTEM_CORE_SYSTEM_PATH.'router.class.php');
define('CLASS_RESPONSE', SYSTEM_CORE_SYSTEM_PATH.'response.class.php');
define('CLASS_REQUEST', SYSTEM_CORE_SYSTEM_PATH.'request.class.php');
define('CLASS_SPAM', SYSTEM_CORE_SYSTEM_PATH.'spam.class.php');
define('CLASS_SQL', SYSTEM_CORE_SYSTEM_PATH.'sql.class.php');

define('FUNCTION', APP_PATH.'function.php');

define('ERROR_WARNING', 'WARNING');
define('ERROR_ERROR', 'ERROR');
define('ERROR_INFORMATION', 'INFORMATION');
define('ERROR_FATAL', 'FATAL');
define('ERROR_EXCEPTION', 'EXCEPTION');

define('RESOLVE_ROUTE', 'route');
define('RESOLVE_LANG', 'lang');
define('RESOLVE_TEMPLATE', 'template');
define('RESOLVE_CSS', 'css');
define('RESOLVE_IMAGE', 'image');
define('RESOLVE_FILE', 'file');
define('RESOLVE_JS', 'js');

define('MODE_CONSOLE', 1);
define('MODE_HTTP', 0);

define('DOCUMENT_ROOT', str_replace('\\', '/', __DIR__).'/');

/* ############### DATABASE ############### */

$GLOBALS['db']['hostname'] = "localhost";
$GLOBALS['db']['username'] = "root";
$GLOBALS['db']['password'] = "";
$GLOBALS['db']['database'] = "test";
$GLOBALS['db']['driver']   = "pdo";
$GLOBALS['db']['type']     = "mysql";
$GLOBALS['db']['charset']  = "utf8";
$GLOBALS['db']['collation']= "utf8_unicode_ci";

/* ############### USER ############### */

// open database connection
define('DATABASE', false);

// use firewall
define('SECURITY', true);

// use spam filter
define('SPAM', true);

// charset
define('CHARSET', 'UTF-8');

// where the framework is placed. If it's the root, keep empty, otherwise : /projet
define('FOLDER', '');

// default language
define('LANG', 'fr');

/* Define the environment  of the application :
 * development : errors + logs + profiler + terminal
 * production : nothing */
define('ENVIRONMENT', 'development');

// application in maintenance
define('MAINTENANCE', false);

// profiler
define('PROFILER', true);

// escape GET and POST
define('SECURE_GET', true);
define('SECURE_POST', true);

// define prefix (define.xml)
define('DEFINE_PREFIX', 'USER_');

// hash cache file name
define('CACHE_SHA1', false);

// enable the log
define('LOG_ENABLED', true);

// minify html output
define('MINIFY_OUTPUT_HTML', true);

// display in the page fatal and exception error
define('DISPLAY_ERROR_FATAL', true);
define('DISPLAY_ERROR_EXCEPTION', true);
define('DISPLAY_ERROR_ERROR', true);

// enable asset manager
define('ASSET_MANAGER', true);

// enable config cache
define('CACHE_CONFIG', false);

// enable cache
define('CACHE_ENABLED', true);

// don't modify
define('IMAGE_PATH', FOLDER.'/'.ASSET_PATH.'image/');
define('CSS_PATH', FOLDER.'/'.ASSET_PATH.'css/');
define('JS_PATH', FOLDER.'/'.ASSET_PATH.'js/');
define('FILE_PATH', FOLDER.'/'.ASSET_PATH.'file/');

define('IMAGE_PATH_PHP', ASSET_PATH.'image/');
define('CSS_PATH_PHP', ASSET_PATH.'css/');
define('JS_PATH_PHP', ASSET_PATH.'js/');
define('FILE_PATH_PHP', ASSET_PATH.'file/');

// timezone
define('TIMEZONE', 'Europe/Paris');