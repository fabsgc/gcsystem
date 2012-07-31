<?php $var ="<?php
	class ".$rubrique." extends applicationGc{
		public ".'$forms'."                = array();
		public ".'$sql'."                  = array();
		public ".'$model'."                         ;
		public ".'$bdd'."                           ;
		
		public function init(){
			".'$this->model = $this->loadModel();'." //chargement du model
		}
		
		public function actionDefault(){
		}
	}"; ?>
<?php echo ($var); ?>