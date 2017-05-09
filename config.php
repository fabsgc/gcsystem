<?php
	/*\
	 | ------------------------------------------------------
	 | @file : config.php
	 | @author : Fabien Beaujean
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
	define('APP_RESOURCE_PATH', APP_PATH . 'Resource/');
	define('APP_RESOURCE_CONFIG_PATH', APP_RESOURCE_PATH . 'config/');
	define('APP_RESOURCE_EVENT_PATH', APP_RESOURCE_PATH . 'Event/');
	define('APP_RESOURCE_LANG_PATH', APP_RESOURCE_PATH . 'lang/');
	define('APP_RESOURCE_ENTITY_PATH', APP_RESOURCE_PATH . 'Entity/');
	define('APP_RESOURCE_LIBRARY_PATH', APP_RESOURCE_PATH . 'Library/');
	define('APP_RESOURCE_REQUEST_PATH', APP_RESOURCE_PATH . 'Request/');
	define('APP_RESOURCE_TEMPLATE_PATH', APP_RESOURCE_PATH . 'template/');
	define('APP_RESOURCE_TEMPLATE_ERROR_PATH', APP_RESOURCE_TEMPLATE_PATH . 'error/');

	define('SRC_PATH', 'src/');
	define('SRC_CONTROLLER_PATH', 'Controller/');
	define('SRC_CONTROLLER_FUNCTION_PATH', 'functions.php');
	define('SRC_RESOURCE_PATH', 'Resource/');
	define('SRC_RESOURCE_CONFIG_PATH', SRC_RESOURCE_PATH . 'config/');
	define('SRC_RESOURCE_EVENT_PATH', SRC_RESOURCE_PATH . 'Event/');
	define('SRC_RESOURCE_LANG_PATH', SRC_RESOURCE_PATH . 'lang/');
	define('SRC_RESOURCE_REQUEST_PATH', SRC_RESOURCE_PATH . 'Request/');
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

	define('APP_FUNCTION', APP_PATH . 'functions.php');
	define('SRC_FUNCTION', SRC_CONTROLLER_PATH . 'functions.php');

	define('SRC_CONFIG_FIREWALL', SRC_RESOURCE_CONFIG_PATH . 'firewall.xml');
	define('SRC_CONFIG_ROUTE', SRC_RESOURCE_CONFIG_PATH . 'route.xml');

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