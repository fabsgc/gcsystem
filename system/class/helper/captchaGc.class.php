<?php
	/**
	 * @file : captchaGc.class.php
	 * @author : fab@c++
	 * @description : class permettant la génération de captcha personnalisée
	 * @version : 2.0 bêta
	*/
	
	class captchaGC{
		use errorGc;                            //trait
		
		protected $_img;
		protected $_mot;
		
		protected $_largeur;
		protected $_hauteur;
		
		protected $_backgroundImage; //true ou false
		protected $_background;
		protected $_extension;
		
		protected $_font; //true ou false
		protected $_fontLink;
		protected $_textColor;
		protected $_textSize;
		protected $_textPos;
		
		protected $_hachure; //true ou false
		protected $_hachureColor;
		
		protected $_i=0;
		protected $_xhachure=0;
		protected $_blur;
		protected $_matrix_blur=array();
		
		protected $_colorHachureAllocate;
		protected $_colorBackgroundAllocate;
		protected $_colorTextAllocate;
		protected $_colorBordureAllocate;
		
		protected $_bordure;
		protected $_bordureColor;
		
		public function __construct($mot, $property=array()){
			$this->_i = 0;
			$this->_mot = $mot;
			$this->_largeur = 10;
			$this->_hauteur = 10;
			$this->_backgroundImage = false;
			$this->_background = array(255,255,255);
			$this->_font = false;
			$this->_fontLink = "";
			$this->_textColor = array();
			$this->_textSize = 5;
			$this->_textPos = array(0,0);
			$this->_hachure = false;
			$this->_hachureColor = array(50,50,50);
			$this->_blur = false;
			
			foreach($property as $cle=>$valeur){
				switch($cle){
					case 'largeur':
						$this->_largeur = $valeur;
					break;
					
					case 'hauteur':
						$this->_hauteur = $valeur;
					break;
					
					case 'textcolor' :
						$this->_textColor = $valeur;
						$this->_textColor[0] = intval($this->_textColor[0]);
						$this->_textColor[1] = intval($this->_textColor[1]);
						$this->_textColor[2] = intval($this->_textColor[2]);
					break;
					
					case 'textsize' :
						$this->_textSize = $valeur;
					break;
					
					case 'textposition' :
						$this->_textPos = $valeur;
					break;
					
					case 'background':
						if(is_array($valeur)){
							$this->_backgroundImage = false;
							$this->_background = $valeur;
						}
						else{
							$this->_backgroundImage = true;
							$this->_background = $valeur;
						}
					break;
					
					case 'hachurecolor' :
						$this->_hachureColor = $valeur;
					break;
					
					case 'hachure' :
						$this->_hachure = $valeur;
					break;
					
					case 'blur' :
						$this->_blur = true;
					break;
					
					case 'font' :
						$this->_font = true;
						$this->_fontLink = $valeur;
					break;
					
					case 'bordure' :
						$this->_bordure = true;
						$this->_bordureColor = $valeur;
					break;
				}
			}
		}
		
		public function show(){
			if($this->_backgroundImage==true){
				$this->_extension = substr($this->_background, -3 );
				$this->_extension = strtolower($this->_extension);
				
				switch ($this->_extension){
					case 'jpg':
						case 'peg':
							$this->_img = imagecreatefromjpeg($this->_background);
					break;

					case 'gif':
						$this->_img = imagecreatefromgif($this->_background);
						imagealphablending($this->_img,FALSE);
						imagesavealpha($this->_img,TRUE);
					break;
					
					case 'png':
						$this->_img = imagecreatefrompng($this->_background);
						imagealphablending($this->_img,FALSE);
						imagesavealpha($this->_img,TRUE);
					break;
					
					default :
						$this->_addError('L\'extension n\est pas gérée');
					break;
				}
				
				$this->_colorTextAllocate = imagecolorallocate($this->_img, $this->_textColor[0], $this->_textColor[1], $this->_textColor[2]);
				imagestring($this->_img, $this->_textSize, $this->_textPos[0], $this->_textPos[1], $this->_mot, $this->_colorTextAllocate);
			}
			else{
				$this->_img = imagecreate($this->_largeur, $this->_hauteur);
				$this->_colorBackgroundAllocate = imagecolorallocate($this->_img, $this->_background[0], $this->_background[1], $this->_background[2]);
				$this->_colorTextAllocate = imagecolorallocate($this->_img, $this->_textColor[0], $this->_textColor[1], $this->_textColor[2]);
				imagestring($this->_img, $this->_textSize, $this->_textPos[0], $this->_textPos[1], $this->_mot, $this->_colorTextAllocate);
			}
			
			$this->_colorHachureAllocate = imagecolorallocate($this->_img, $this->_hachureColor[0], $this->_hachureColor[1], $this->_hachureColor[2]);
			$this->_colorBordureAllocate = imagecolorallocate($this->_img, $this->_bordureColor[0], $this->_bordureColor[1], $this->_bordureColor[2]);
				
			
			if($this->_hachure==true){
				for($this->_i=0; $this->_i<1; $this->_i++){
					imageline($this->_img, 2,mt_rand(2,$this->_hauteur), $this->_largeur - 2, mt_rand(2,$this->_hauteur), $this->_colorHachureAllocate);
				}
				
				for($this->_xhachure = 5; $this->_xhachure < $this->_largeur; $this->_xhachure+=14)
				{
					imageline($this->_img, $this->_xhachure,2,$this->_xhachure-5,$this->_hauteur, $this->_colorHachureAllocate);
				}
			}
			
			if($this->_blur==true){
				$this->_matrix_blur = array(array(1,1,1),
										array(1,1,1),
										array(1,1,1));
			}
			
			return imagepng($this->_img);
		}
		
		public function __destruct(){
		}
	}