<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class installtest extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			$this->showDefault();
			$$this->bdd['test'];
		}
	}