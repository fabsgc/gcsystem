<?php
	namespace Gcs;

	use System\Config\Config;
	use System\Controller\Controller;
	use System\Response\Response;
	use System\Template\Template;

	/**
	 * Class Index
	 * @package Gcs
	 * @Before(class="\Gcs\Index", method="init")
	 */

	class Index extends Controller {

		public function init() {
			if (Config::config()['user']['debug']['environment'] != 'development') {
				Response::instance()->status(404);
			}
		}

		/**
		 * @Routing(name="index", url="(/*)", method="get")
		 */

		public function actionDefault() {
			return (new Template('index/default', 'gcsDefault'))
				->assign('title', 'GCsystem V' . VERSION)
				->show();
		}
	}