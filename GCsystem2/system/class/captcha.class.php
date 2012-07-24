<?php
	class captchaGC{
		private $img;
		private $mot;
		
		private $largeur;
		private $hauteur;
		
		private $backgroundImage; //true ou false
		private $background;
		private $extension;
		
		private $font; //true ou false
		private $fontLink;
		private $textColor;
		private $textSize;
		private $textPos;
		
		private $hachure; //true ou false
		private $hachureColor;
		
		private $i=0;
		private $xhachure=0;
		private $blur;
		private $matrix_blur=array();
		
		private $colorHachureAllocate;
		private $colorBackgroundAllocate;
		private $colorTextAllocate;
		private $colorBordureAllocate;
		
		private $bordure;
		private $bordureColor;
		
		public function __construct($mot, $property=array()){
			$this->i = 0;
			$this->mot = $mot;
			$this->largeur = 10;
			$this->hauteur = 10;
			$this->backgroundImage = false;
			$this->background = array(255,255,255);
			$this->font = false;
			$this->fontLink = "";
			$this->textColor = array(0,0,0);
			$this->textSize = 5;
			$this->textPos = array(0,0);
			$this->hachure = false;
			$this->hachureColor = array(50,50,50);
			$this->blur = false;
			
			foreach($property as $cle=>$valeur){
				switch($cle){
					case 'largeur':
						$this->largeur = $valeur;
					break;
					
					case 'hauteur':
						$this->hauteur = $valeur;
					break;
					
					case 'textcolor' :
						$this->textColor = $valeur;
					break;
					
					case 'textsize' :
						$this->textSize = $valeur;
					break;
					
					case 'textposition' :
						$this->testPos = $valeur;
					break;
					
					case 'background':
						if(is_array($valeur)){
							$this->backgroundImage = true;
							$this->background = $valeur;
						}
						else{
							$this->backgroundImage = false;
							$this->background = $valeur;
						}
					break;
					
					case 'hachurecolor' :
						$this->hachureColor = $valeur;
					break;
					
					case 'hachure' :
						$this->hachure = $valeur;
					break;
					
					case 'blur' :
						$this->blur = true;
					break;
					
					case 'font' :
						$this->font = true;
						$this->fontLink = $valeur;
					break;
					
					case 'bordure' :
						$this->bordure = true;
						$this->bordureColor = $valeur;
					break;
				}
			}
		}
		
		public function show(){
			if($this->backgroundImage==true){
				$this->extension = substr($this->background, -3 );
				$this->extension = strtolower($this->extension);
				
				switch ($this->extension){
					case 'jpg':
						case 'peg':
							$this->img = imagecreatefromjpeg($this->background);
					break;

					case 'gif':
						$this->img = imagecreatefromgif($this->background);
					break;
					
					case 'png':
						$this->img = imagecreatefrompng($this->background);
					break;
				}
				
				imagestring($this->img, 4, $this->textPos[0], $this->textPos[1], $this->mot, $this->colorTextAllocate);
			}
			else{
				$this->img = imagecreate($this->largeur, $this->hauteur);
				$this->colorBackgroundAllocate = imagecolorallocate($this->img, $this->background[0], $this->background[1], $this->background[2]);
				$this->colorTextAllocate = imagecolorallocate($this->img, $this->textColor[0], $this->textColor[1], $this->textColor[2]);
				imagestring($this->img, $this->textSize, $this->textPos[0], $this->textPos[1], $this->mot, $this->colorTextAllocate);
			}
			
			$this->colorHachureAllocate = imagecolorallocate($this->img, $this->hachureColor[0], $this->hachureColor[1], $this->hachureColor[2]);
			$this->colorBordureAllocate = imagecolorallocate($this->img, $this->bordureColor[0], $this->bordureColor[1], $this->bordureColor[2]);
				
			
			if($this->hachure==true){
				for($this->i=0; $this->i<1; $this->i++){
					imageline($this->img, 2,mt_rand(2,$this->hauteur), $this->largeur - 2, mt_rand(2,$this->hauteur), $this->colorHachureAllocate);
				}
				
				for($this->xhachure = 5; $this->xhachure < $this->largeur; $this->xhachure+=14)
				{
					imageline($this->img, $this->xhachure,2,$this->xhachure-5,$this->hauteur, $this->colorHachureAllocate);
				}
			}
			
			if($this->blur==true){
				$this->matrix_blur = array(array(1,1,1),
										array(1,1,1),
										array(1,1,1));
			}
			
			return imagepng($this->img);
		}
		
		public function __destruct(){
		}
	}