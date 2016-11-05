<?php
	namespace Gcs;

	use System\Config\Config;
	use System\Controller\Controller;
	use System\Response\Response;

	/**
	 * Class Lang
	 * @package Gcs
	 * @Before(class="\Gcs\Lang", method="init")
	 */

	class Lang extends Controller {

		public function init() {
			if (Config::config()['user']['debug']['environment'] != 'development') {
				Response::instance()->status(404);
			}
		}

		/**
		 * @Routing(name="gcs.lang.default", url="/gcs/lang(/*)", method="get")
		 * @return mixed
		 */

		public function actionDefault() {
			return $this->showDefault();
		}
	}