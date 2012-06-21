<?php
	/*\
	 | ------------------------------------------------------
	 | @file : appDev.class.php
	 | @author : fab@c++
	 | @description : class à utiliser lors du développement de l'application
	 | @version : 2.0 bêta
	 | ------------------------------------------------------
	\*/
	
	class appDev{
		
		public  function __construct(){
			echo '<div id="dev" style="position: fixed;
				background-color: #F7F7F7;
				background-image: -moz-linear-gradient(-90deg, #E4E4E4, white);
				background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#E4E4E4), to(white));
				bottom: 0;
				left: 0;
				margin: 0;
				padding: 5px;
				z-index: 6000000;
				width: 100%;
				border-top: 1px solid 
				#BBB;
				font: 16px Verdana, Arial, sans-serif;
				text-align: left;
				color: 
				#2F2F2F;">
					<div id="dev_logo" style=" display: inline-block;"><img src="'.IMG_PATH.'logo.png" style="width: 25px" /></div>
					<div id="dev_text" style="position: relative; top: -6px; display: inline-block;">interface de développement en cours de création</div>
				</div>';
		}
		
		public  function __desctuct(){
		
		}
	}
?>