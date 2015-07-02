<?php
	namespace gcs;

	use System\Controller\Controller;

	class Scaffolding extends Controller{
		public function init(){
			if(ENVIRONMENT != 'development')
				self::Response()->status(404);
		}
		
		public function actionDefault(){
			return $this->showDefault();
		}

		public function actionEntity(){
			return $this->showDefault();
		}

		public function actionInsert(){
			return $this->showDefault();
		}

		public function actionUpdate(){
			return $this->showDefault();
		}

		public function actionDelete(){
			return $this->showDefault();
		}
	}