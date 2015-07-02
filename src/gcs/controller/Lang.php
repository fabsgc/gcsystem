<?php
	namespace gcs;

	use System\Controller\Controller;

	class Lang extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			return $this->showDefault();
		}
	}