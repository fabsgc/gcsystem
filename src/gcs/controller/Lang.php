<?php
	namespace Gcs;

	use System\Controller\Controller;
	use System\Response\Response;

	class Lang extends Controller {
		public function init() {
			if (ENVIRONMENT != 'development') {
				Response::getInstance()->status(404);
			}
		}

		public function actionDefault() {
			return $this->showDefault();
		}
	}