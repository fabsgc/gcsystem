{{php: $var='<?php
	namespace '.ucfirst($src).';

	use System\Controller\Controller;

	class '.ucfirst($controller).' extends Controller{
		public function actionDefault(){
			return $this->showDefault();
		}
	}';
}}
{$var}