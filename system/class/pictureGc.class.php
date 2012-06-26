<?php
	/*\
	 | ------------------------------------------------------
	 | @file : pictureGc.class.php
	 | @author : fab@c++
	 | @description : class fille de fileGc.class.php gérant les images
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
    class pictureGc extends fileGc{
		private $img;                //contient l'url de l'image
		private $imgGD;              //objet GD
		private $error = array();    //erreurs
		
		public function __construct($img){
			$this->img = $img;
		}
		
		public function _setImg($img){
			$this->img = $img;
		}
		
		public function _fromTo($to){
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
						$this->_addError('L\'extension n\'est pas gérée');
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
						$this->_addError('L\'extension n\'est pas gérée');
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
						$this->_addError('L\'extension n\'est pas gérée');
					break;
				}
		}
		
		private function _addError($error){
			array_push($this->error, $error);
		}
		
		private function _showError(){
			foreach($this->error as $error){
				$erreur .=$error."<br />";
			}
			return $erreur;
		}
		
		public function __destruct(){
		}
	}