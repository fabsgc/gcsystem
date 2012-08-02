<?php
	class managerIndex extends modelGc{
		protected $bdd                           ;
		
		public function init(){
			//é
		}
		
		public function actionDefault(){
			$sql = new sqlGc($this->bdd[BDD]);
			$sql->query('nom', 'SELECT * FROM news');

			foreach($sql->fetch('nom') as $val){
				echo $val['titre'].'<br />';
			}
		}
	}