<?php
	namespace gcs;

	use System\Controller\Controller;

	class Profiler extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			self::Profiler()->enable(false);

			if(isset($_POST['id'])){
				if($_POST['id'] == '')
					$cache = self::Cache('gcsProfiler', 0);
				else
					$cache = self::Cache('gcsProfiler_'.$_POST['id'], 0);
			}
			else
				$cache = self::Cache('gcsProfiler', 0);

			$data = $cache->getCache();

			if($data != ''){
				$t = $this->Template('profiler/default', 'gcsProfiler', '0');
				$t->assign(array('data' => $cache->getCache()));

				return $t->show();
			}
			else{
				self::Response()->status(404);
			}
		}
	}