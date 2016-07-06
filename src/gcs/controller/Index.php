<?php
	namespace Gcs;

	use System\Config\Config;
	use System\Controller\Controller;
	use System\Response\Response;
	use System\Template\Template;

	class Index extends Controller {
		public function init() {
			if (Config::config()['user']['debug']['environment'] != 'development') {
				Response::instance()->status(404);
			}
		}

		public function actionDefault() {
			return (new Template('index/default', 'gcsDefault'))
				->assign('title', 'GCsystem V' . VERSION)
				->show();
		}
	}
