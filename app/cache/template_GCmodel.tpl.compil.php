<?php $var ="<?php
	/**
	 * @info :manager créé automatiquement par le GCsystem
	*/
	
	class manager".$rubrique." extends modelGc{
		protected ".'$forms'."                = array();
		protected ".'$sql'."                  = array();
		protected ".'$bdd'."                           ;
		
		public function init(){
		}
		
		public function actionDefault(){
		}
	}"; ?>
<?php echo ($var); ?>