<?php
	namespace Gcs;

	use System\Controller\Controller;
	use System\Template\Template;
	use System\Cache\Cache;

	class Profiler extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			self::Profiler()->enable(false);

			if(isset($_POST['id'])){
				if($_POST['id'] == '')
					$cache = new Cache('gcsProfiler', 0);
				else
					$cache = new Cache('gcsProfiler_'.$_POST['id'], 0);
			}
			else
				$cache = new Cache('gcsProfiler', 0);

			$data = $cache->getCache();

			if($data != ''){
				return (new Template('profiler/default', 'gcsProfiler', '0'))
					->assign('data', $cache->getCache())
					->assign('title', 'Profiler ['.$data['url'].']')
					->show();
			}
			else{
				self::Response()->status(404);
			}
		}
	}