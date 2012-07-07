<?php
	/**
	 * @file : pictureGc.class.php
	 * @author : fab@c++
	 * @description : class fille de fileGc.class.php gérant les images
	 * @version : 2.0 bêta
	*/
	
    class pictureGc extends fileGc{		
		protected $img;                //contient l'url de l'image
		protected $imgGD;              //objet GD
		
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
		
		public function __destruct(){
		}
	}