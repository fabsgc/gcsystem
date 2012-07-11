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
		
		public function __construct($filepath){
			if(is_file($filepath)){
				$this->setFile($filepath);
			}
			else{
				$this->_addError(self::NOACCESS);
			}
		}
		
		public function setFile($filepath){
			$filepath = strval($filepath);
			if(is_file($filepath)){
				$this->_setFilePath($filepath);
				$this->_setFileName($filepath);
				$this->_setFileExt($filepath);
				$this->_setFileInfo($filepath);
				$this->_setFileContent($filepath);
				$this->_setFileChmod($filepath);
				$this->_setFileChmod($filepath);
				$this->_isExist = true;
				$this->_setFileGd($filepath);
			}
			else{
				$this->_addError(self::NOACCESS);
			}
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