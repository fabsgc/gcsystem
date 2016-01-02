<?php
	namespace Gcs;

	use System\Controller\Controller;
	use System\Cache\Cache;

	class AssetManager extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			if($_GET['type'] =='js' || $_GET['type'] == 'css'){
				self::Response()->contentType("text/".$_GET['type']);
				self::Response()->header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));

				$cache = new Cache(html_entity_decode($_GET['id'].'.'.$_GET['type']), 0);

				return $cache->getCache();
			}
			else{
				self::Response()->status(404);
			}
		}
	}