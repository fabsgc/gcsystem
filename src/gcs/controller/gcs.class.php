<?php
	namespace gcs;

	class gcs extends \system\controller{		
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
		}
	}