<?php $var ="<?php
	class ".$rubrique." extends applicationGc{
		".'$forms'."                = array();
		".'$sql'."                  = array();
		
		public function init(){
		}
		
		public function actionDefault(){
		}
	}"; ?>
<?php echo ($var); ?>