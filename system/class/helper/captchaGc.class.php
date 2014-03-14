<?php
	/**
	 * @file : captchaGc.class.php
	 * @author : fab@c++
	 * @description : class permettant la génération de captcha personnalisée
	 * @version : 2.2 bêta
	*/
	
	class captchaGC{
		use errorGc;                            //trait
		
		protected $_img;
		protected $_mot;
		
		protected $_width;
		protected $_height;
		
		protected $_backgroundImage; //true ou false
		protected $_background;
		protected $_extension;
		
		protected $_font; //true ou false
		protected $_fontLink;
		protected $_textColor;
		protected $_textSize;
		protected $_textPos;
		
		protected $_hatching; //true ou false
		protected $_hatchingColor;
		
		protected $_i=0;
		protected $_xhatching=0;
		protected $_blur;
		protected $_matrix_blur=array();
		
		protected $_colorhatchingAllocate;
		protected $_colorBackgroundAllocate;
		protected $_colorTextAllocate;
		protected $_colorborderAllocate;
		
		protected $_border;
		protected $_borderColor;
		
		/**
		 * Crée l'instance de la classe
		 * @param string $mot : mot à afficher dans l'image
		 * @param array $property : propriétés de l'image
		 * @access public
		 * @return void
		 * @since 2.0
		*/

		public function __construct($mot, $property=array()){
			$this->_i = 0;
			$this->_mot = $mot;
			$this->_width = 10;
			$this->_height = 10;
			$this->_backgroundImage = false;
			$this->_background = array(255,255,255);
			$this->_font = false;
			$this->_fontLink = "";
			$this->_textColor = array();
			$this->_textSize = 5;
			$this->_textPos = array(0,0);
			$this->_hatching = false;
			$this->_hatchingColor = array(50,50,50);
			$this->_blur = false;
			
			foreach($property as $cle=>$valeur){
				switch($cle){
					case 'width':
						$this->_width = $valeur;
					break;
					
					case 'height':
						$this->_height = $valeur;
					break;
					
					case 'textColor' :
						$this->_textColor = $valeur;
						$this->_textColor[0] = intval($this->_textColor[0]);
						$this->_textColor[1] = intval($this->_textColor[1]);
						$this->_textColor[2] = intval($this->_textColor[2]);
					break;
					
					case 'textSize' :
						$this->_textSize = $valeur;
					break;
					
					case 'textPosition' :
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
					
					case 'hatchingColor' :
						$this->_hatchingColor = $valeur;
					break;
					
					case 'hatching' :
						$this->_hatching = $valeur;
					break;
					
					case 'blur' :
						$this->_blur = true;
					break;
					
					case 'font' :
						$this->_font = true;
						$this->_fontLink = $valeur;
					break;
					
					case 'border' :
						$this->_border = true;
						$this->_borderColor = $valeur;
					break;
				}
			}
		}

		/**
		 * retourne l'image (qu'il faut afficher après le header png)
		 * @access public
		 * @return imagepng
		 * @since 2.0
		*/
		
		public function show(){
			$orange = imagecolorallocate($this->_img, 255, 128, 0);

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
						$this->_addError('L\'extension n\est pas gérée', __FILE__, __LINE__, ERROR);
					break;
				}
				
				$this->_colorTextAllocate = imagecolorallocate($this->_img, $this->_textColor[0], $this->_textColor[1], $this->_textColor[2]);
				imagestring($this->_img, $this->_textSize, $this->_textPos[0], $this->_textPos[1], $this->_mot, $this->_colorTextAllocate);
			}
			else{
				$this->_img = imagecreate($this->_width, $this->_height);
				$this->_colorBackgroundAllocate = imagecolorallocate($this->_img, $this->_background[0], $this->_background[1], $this->_background[2]);
				$this->_colorTextAllocate = imagecolorallocate($this->_img, $this->_textColor[0], $this->_textColor[1], $this->_textColor[2]);
				imagestring($this->_img, $this->_textSize, $this->_textPos[0], $this->_textPos[1], $this->_mot, $this->_colorTextAllocate);
			}
			
			$this->_colorhatchingAllocate = imagecolorallocate($this->_img, $this->_hatchingColor[0], $this->_hatchingColor[1], $this->_hatchingColor[2]);
			$this->_colorborderAllocate = imagecolorallocate($this->_img, $this->_borderColor[0], $this->_borderColor[1], $this->_borderColor[2]);
				
			
			if($this->_hatching==true){
				for($this->_i=0; $this->_i<1; $this->_i++){
					imageline($this->_img, 2,mt_rand(2,$this->_height), $this->_width - 2, mt_rand(2,$this->_height), $this->_colorhatchingAllocate);
				}
				
				for($this->_xhatching = 5; $this->_xhatching < $this->_width; $this->_xhatching+=14)
				{
					imageline($this->_img, $this->_xhatching,2,$this->_xhatching-5,$this->_height, $this->_colorhatchingAllocate);
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