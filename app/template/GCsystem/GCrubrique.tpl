<gc:variable var ="<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class ".$rubrique." extends applicationGc{
		protected ".'$model'."                         ;
		protected ".'$bdd'."                           ;
		protected ".'$forms'."                = array();
		protected ".'$sql'."                           ;

		public function init(){
			".'$this->model = $this->loadModel();'." //chargement du model
		}
		
		public function actionDefault(){
			".'$this'."->showDefault();
		}
	}" />
{var}