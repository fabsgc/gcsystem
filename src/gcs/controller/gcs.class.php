<?php
	namespace gcs;

	use system\Controller\Controller;

	class Gcs extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development'){
				$this->response->status(404);
			}
		}

		public function end(){
		}
		
		public function actionDefault(){
			$t = $this->template('gcs/default', 'gcsDefault', '0');
			return $t->show();
		}

		public function actionLang(){
		}

		public function actionProfiler(){
			$this->profiler->enable(false);

			if(isset($_POST['id'])){
				if($_POST['id'] == '')
					$cache = $this->cache('gcsProfiler', 0);
				else
					$cache = $this->cache('gcsProfiler_'.$_POST['id'], 0);
			}
			else
				$cache = $this->cache('gcsProfiler', 0);

			$t = $this->template('gcs/profiler', 'gcsProfiler', '0');
			$t->assign(array('data' => $cache->getCache()));

			return $t->show();
		}

		public function actionAssetManager(){
			if($_GET['type'] =='js' || $_GET['type'] == 'css'){
				$cache = $this->cache(html_entity_decode($_GET['id']).'.'.html_entity_decode($_GET['type']), 0);
				$this->response->contentType("text/".$_GET['type']);
				$this->response->header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
				header("Content-type: text/".$_GET['type']);
				return $cache->getCache();
			}
			else
				$this->response->status(404);
		}
	}