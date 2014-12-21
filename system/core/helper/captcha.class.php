<?php
	/*\
	 | ------------------------------------------------------
	 | @file : captcha.class.php
	 | @author : fab@c++
	 | @description : class permettant la génération de captchas personnalisées
	 | @version : 2.4 Bêta
	 | ------------------------------------------------------
	\*/
	
	namespace helper{
		class captcha{
			use \system\error;

			/**
			 * retourne l'image sans header
			 * @access public
			 * @param $mot string : $mot à afficher dans l'image
			 * @param $property array : liste des propriétés appliquable à l'image :
			 *			width int : largeur en px
			 * 			height int : hauteur en image
			 * 			textColor array : rgb
			 * 			textSize int : taille du texte
			 * 			textPosition int : décalage à gauche en px du texte
			 * 			background string : chemin vers l'image
			 * 			hatchingColor array : couleur des hachures en rgb
			 * 			hatching bool : hachures ou non
			 * @return string
			 * @since 2.0
			*/
			
			public static function show($mot, $property=array()){
				$_mot = $mot;
				
				$_width = 10;
				$_height = 10;
				
				$_backgroundImage = false;
				$_background = array(255,255,255);

				$_textColor = array(0,0,0);
				$_textSize = 5;
				$_textPos = array(0,0);
				
				$_hatching = false;
				$_hatchingColor = array(50,50,50);
				
				foreach($property as $cle=>$valeur){
					switch($cle){
						case 'width':
							$_width = $valeur;
						break;
						
						case 'height':
							$_height = $valeur;
						break;
						
						case 'textColor' :
							$_textColor = $valeur;
							$_textColor[0] = intval($_textColor[0]);
							$_textColor[1] = intval($_textColor[1]);
							$_textColor[2] = intval($_textColor[2]);
						break;
						
						case 'textSize' :
							$_textSize = $valeur;
						break;
						
						case 'textPosition' :
							$_textPos = $valeur;
						break;
						
						case 'background':
							if(is_array($valeur)){
								$_backgroundImage = false;
								$_background = $valeur;
							}
							else{
								$_backgroundImage = true;
								$_background = $valeur;
							}
						break;
						
						case 'hatchingColor' :
							$_hatchingColor = $valeur;
						break;
						
						case 'hatching' :
							$_hatching = $valeur;
						break;
					}
				}

				if($_backgroundImage==true){
					$_extension = substr($_background, -3 );
					$_extension = strtolower($_extension);
					
					switch ($_extension){
						case 'jpg':
							case 'peg':
								$_img = imagecreatefromjpeg($_background);
						break;

						case 'gif':
							$_img = imagecreatefromgif($_background);
							imagealphablending($_img,FALSE);
							imagesavealpha($_img,TRUE);
						break;
						
						case 'png':
							$_img = imagecreatefrompng($_background);
							imagealphablending($_img,FALSE);
							imagesavealpha($_img,TRUE);
						break;
						
						default :
							//$this->_addError('L\'extension n\est pas gérée', __FILE__, __LINE__, ERROR);
						break;
					}
					
					$_colorTextAllocate = imagecolorallocate($_img, $_textColor[0], $_textColor[1], $_textColor[2]);
					imagestring($_img, $_textSize, $_textPos[0], $_textPos[1], $_mot, $_colorTextAllocate);
				}
				else{
					$_img = imagecreate($_width, $_height);
					$_colorBackgroundAllocate = imagecolorallocate($_img, $_background[0], $_background[1], $_background[2]);
					$_colorTextAllocate = imagecolorallocate($_img, $_textColor[0], $_textColor[1], $_textColor[2]);
					imagestring($_img, $_textSize, $_textPos[0], $_textPos[1], $_mot, $_colorTextAllocate);
				}
				
				$_colorhatchingAllocate = imagecolorallocate($_img, $_hatchingColor[0], $_hatchingColor[1], $_hatchingColor[2]);

				if($_hatching==true){
					for($_i=0; $_i<1; $_i++){
						imageline($_img, 2,mt_rand(2,$_height), $_width - 2, mt_rand(2,$_height), $_colorhatchingAllocate);
					}
					
					for($_xhatching = 5; $_xhatching < $_width; $_xhatching+=14)
					{
						imageline($_img, $_xhatching,2,$_xhatching-5,$_height, $_colorhatchingAllocate);
					}
				}
				
				return imagepng($_img);
			}
		}
	}