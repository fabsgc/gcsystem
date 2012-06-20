<?php
    class picture extends file{
		private $img;                //contient l'url de l'image
		private $imgGD;              //objet GD
		private $error = array();    //erreurs
		
		public function __construct($img){
			$this->img = $img;
		}
		
		public function setImg($img){
			$this->img = $img;
		}
		
		public function fromTo($to){
			switch ($this->img){
					case 'jpg':
						case 'peg':
							$this->imgGD = imagecreatefromjpeg($this->img = $img);
					break;

					case 'gif':
						$this->imgGD = imagecreatefromgif($this->img = $img);
					break;
					
					case 'png':
						$this->imgGD = imagecreatefrompng($this->img = $img);
					break;
					
					case 'bmp':
						$this->imgGD = imagecreatefromxbmp($this->img = $img);
					break;
					
					default :
						$this->addError('L\'extension n\'est pas gérée');
					break;
				}
		}
		
		public function resize($size){
			switch ($this->extension){
					case 'jpg':
						case 'peg':
					break;

					case 'gif':
					break;
					
					case 'png':
					break;
					
					case 'bmp':
					break;
					
					default :
						$this->addError('L\'extension n\'est pas gérée');
					break;
				}
		}
		
		public function size($sizeX, $sizeY){
			switch ($this->extension){
					case 'jpg':
						case 'peg':
					break;

					case 'gif':
					break;
					
					case 'png':
					break;
					
					case 'bmp':
					break;
					
					default :
						$this->addError('L\'extension n\'est pas gérée');
					break;
				}
		}
		
		private function addError($error){
			array_push($this->error, $error);
		}
		
		public function __destruct(){
		}
	}