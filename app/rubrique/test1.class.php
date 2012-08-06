<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class test1 extends applicationGc{
		protected $forms                = array();
		protected $sql                  = array();
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			$this->showDefault();
		}
	}