<?php
	namespace gcs;

	use System\Controller\Controller;

	class Index extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			return self::Template('index/default', 'gcsDefault')
				->show();
		}
	}