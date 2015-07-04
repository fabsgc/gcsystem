<?php
	namespace Gcs;

	use Controller\Request\Gcs\FormRequest;
	use System\Controller\Controller;
	use System\Orm\Entity;

	class Index extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			return self::Template('index/default', 'gcsDefault')
				->assign('title', 'GCsystem V'.VERSION)
				->show();
		}
	}