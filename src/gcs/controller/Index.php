<?php
	namespace gcs;

	use System\Template\Template;

	class Index extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			$t = new Template('index/default', 'gcsDefault');
			return $t->show();
		}
	}