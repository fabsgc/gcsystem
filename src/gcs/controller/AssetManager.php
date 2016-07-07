<?php
	namespace Gcs;

	use System\Cache\Cache;
	use System\Config\Config;
	use System\Controller\Controller;
	use System\Response\Response;

	class AssetManager extends Controller {
		public function actionDefault() {
			if ($_GET['type'] == 'js' || $_GET['type'] == 'css') {
				Response::instance()->contentType("text/" . $_GET['type']);
				Response::instance()->header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));

				$cache = new Cache(html_entity_decode($_GET['id'] . '.' . $_GET['type']), Config::config()['user']['output']['asset']['cache']);

				return $cache->getCache();
			}
			else {
				Response::instance()->status(404);
			}
		}
	}