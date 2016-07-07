<?php
	/*\
	 | ------------------------------------------------------
	 | @file : config.php
	 | @author : fab@c++
	 | @description : Configuration of the application
	 | @version : 3.0 Bêta
	 | ------------------------------------------------------
	\*/

	define('VERSION', '3.1');

	/* ############### PATH ############### */

	define('APP_PATH', 'app/');
	define('APP_CACHE_PATH', APP_PATH . 'cache/');
	define('APP_CACHE_PATH_DEFAULT', APP_PATH . 'cache/default/');
	define('APP_CACHE_PATH_TEMPLATE', APP_PATH . 'cache/template/');
	define('APP_LOG_PATH', APP_PATH . 'log/');
	define('APP_RESOURCE_PATH', APP_PATH . 'resource/');
	define('APP_RESOURCE_CONFIG_PATH', APP_RESOURCE_PATH . 'config/');
	define('APP_RESOURCE_EVENT_PATH', APP_RESOURCE_PATH . 'event/');
	define('APP_RESOURCE_LANG_PATH', APP_RESOURCE_PATH . 'lang/');
	define('APP_RESOURCE_ENTITY_PATH', APP_RESOURCE_PATH . 'entity/');
	define('APP_RESOURCE_LIBRARY_PATH', APP_RESOURCE_PATH . 'library/');
	define('APP_RESOURCE_REQUEST_PATH', APP_RESOURCE_PATH . 'request/');
	define('APP_RESOURCE_TEMPLATE_PATH', APP_RESOURCE_PATH . 'template/');
	define('APP_RESOURCE_TEMPLATE_ERROR_PATH', APP_RESOURCE_TEMPLATE_PATH . 'error/');

	define('SRC_PATH', 'src/');
	define('SRC_CONTROLLER_PATH', 'controller/');
	define('SRC_CONTROLLER_FUNCTION_PATH', 'function.php');
	define('SRC_RESOURCE_PATH', 'resource/');
	define('SRC_RESOURCE_CONFIG_PATH', SRC_RESOURCE_PATH . 'config/');
	define('SRC_RESOURCE_EVENT_PATH', SRC_RESOURCE_PATH . 'event/');
	define('SRC_RESOURCE_LANG_PATH', SRC_RESOURCE_PATH . 'lang/');
	define('SRC_RESOURCE_LIBRARY_PATH', SRC_RESOURCE_PATH . 'library/');
	define('SRC_RESOURCE_REQUEST_PATH', SRC_RESOURCE_PATH . 'request/');
	define('SRC_RESOURCE_TEMPLATE_PATH', SRC_RESOURCE_PATH . 'template/');

	define('WEB_PATH', 'web/');
	define('WEB_CSS_PATH', 'css/');
	define('WEB_FILE_PATH', 'file/');
	define('WEB_IMAGE_PATH', 'img/');
	define('WEB_JS_PATH', 'js/');

	define('VENDOR_PATH', 'vendor/');
	define('SYSTEM_PATH', VENDOR_PATH . 'gcsystem/framework/');
	define('SYSTEM_CORE_PATH', SYSTEM_PATH . 'core/');
	define('SYSTEM_CORE_SYSTEM_PATH', SYSTEM_CORE_PATH . 'System/');
	define('SYSTEM_CORE_HELPER_PATH', SYSTEM_CORE_PATH . 'Helper/');

	define('LOG_SYSTEM', 'system');
	define('LOG_HISTORY', 'history');
	define('LOG_SQL', 'sql');
	define('LOG_ERROR', 'error');
	define('LOG_CRONS', 'cron');
	define('LOG_EVENT', 'event');

	define('APP_FUNCTION', APP_PATH . 'function.php');
	define('SRC_FUNCTION', SRC_CONTROLLER_PATH . 'function.php');

	define('APP_CONFIG_CRON', APP_RESOURCE_CONFIG_PATH . 'cron.xml');
	define('APP_CONFIG_DEFINE', APP_RESOURCE_CONFIG_PATH . 'define.xml');
	define('APP_CONFIG_LIBRARY', APP_RESOURCE_CONFIG_PATH . 'library.xml');
	define('APP_CONFIG_SPAM', APP_RESOURCE_CONFIG_PATH . 'spam.xml');
	define('APP_CONFIG_TEMPLATE', APP_RESOURCE_CONFIG_PATH . 'template.xml');

	define('SRC_CONFIG_CRON', SRC_RESOURCE_CONFIG_PATH . 'cron.xml');
	define('SRC_CONFIG_DEFINE', SRC_RESOURCE_CONFIG_PATH . 'define.xml');
	define('SRC_CONFIG_FIREWALL', SRC_RESOURCE_CONFIG_PATH . 'firewall.xml');
	define('SRC_CONFIG_LIBRARY', SRC_RESOURCE_CONFIG_PATH . 'library.xml');
	define('SRC_CONFIG_ROUTE', SRC_RESOURCE_CONFIG_PATH . 'route.xml');

	define('CLASS_GENERAL', SYSTEM_CORE_SYSTEM_PATH . 'General/General.php');
	define('CLASS_AUTOLOAD', SYSTEM_CORE_PATH . 'Autoload.php');

	define('ERROR_WARNING', 'WARNING');
	define('ERROR_ERROR', 'ERROR');
	define('ERROR_INFORMATION', 'INFORMATION');
	define('ERROR_FATAL', 'FATAL');
	define('ERROR_EXCEPTION', 'EXCEPTION');

	define('RESOLVE_ROUTE', 'route');
	define('RESOLVE_LANG', 'lang');
	define('RESOLVE_TEMPLATE', 'template');
	define('RESOLVE_CSS', 'css');
	define('RESOLVE_IMAGE', 'img');
	define('RESOLVE_FILE', 'file');
	define('RESOLVE_JS', 'js');

	define('MODE_CONSOLE', 1);
	define('MODE_HTTP', 0);

	define('DOCUMENT_ROOT', str_replace('\\', '/', __DIR__) . '/');

	return require_once('app/config.php');