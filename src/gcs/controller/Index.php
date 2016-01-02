<?php
	namespace Gcs;

	use System\Controller\Controller;
	use System\Template\Template;

	class Index extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			return (new Template('index/default', 'gcsDefault'))
				->assign('title', 'GCsystem V'.VERSION)
				->show();
		}
	}