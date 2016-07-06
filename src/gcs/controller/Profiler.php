<?php
	namespace Gcs;

	use System\Cache\Cache;
	use System\Config\Config;
	use System\Controller\Controller;
	use System\Response\Response;
	use System\Template\Template;

	class Profiler extends Controller {
		public function init() {
			if (Config::config()['user']['debug']['environment'] != 'development') {
				Response::instance()->status(404);
			}
		}

		public function actionDefault() {
			\System\Profiler\Profiler::instance()->enable(false);

			if (isset($_POST['id'])) {
				if ($_POST['id'] == '') {
					$cache = new Cache('gcsProfiler', 0);
				}
				else {
					$cache = new Cache('gcsProfiler_' . $_POST['id'], 0);
				}
			}
			else {
				$cache = new Cache('gcsProfiler', 0);
			}

			$data = $cache->getCache();

			if ($data != '') {
				return (new Template('profiler/default', 'gcsProfiler', '0'))
					->assign('data', $cache->getCache())
					->assign('title', 'Profiler [' . $data['url'] . ']')
					->show();
			}
			else {
				Response::instance()->status(404);
			}
		}
	}