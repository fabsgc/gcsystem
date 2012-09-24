<?php
	/**
	 * @info : contrôleur créé automatiquement par le GCsystem
	*/
	
	class test extends applicationGc{
		protected $model                         ;
		protected $bdd                           ;
		
		public function init(){
			$this->model = $this->loadModel(); //chargement du model
		}
		
		public function actionDefault(){
			$this->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
			echo $this->showHeader();
			$_SESSION['test'] = 1
			?>
				<script type="text/javascript">

					function refresh() {
						$.ajax({
							url: "<?php echo FOLDER.$this->getUrl('test2', array()); ?>", // Ton fichier ou se trouve ton div
							success: function(retour){
								$('#bruit_arene').html(retour); // rafraichi toute ta DIV "bien sur il lui faut un id "
							}
						});
					}
					
					window.onload = function (){
						var timer=setInterval("refresh()", 1); // Rafraichit le minichat toute les 5s
					}
				</script>
				<div id="bruit_arene">
				</div>
			<?php
			echo $this->showFooter();
		}

		public function actionAjax(){
			$_SESSION['test']++;
			echo $_SESSION['test'];
		}
	}