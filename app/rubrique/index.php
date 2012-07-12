<?php
	// $GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem'));
	// echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		// $t->assign(array(
			// 'var'=> 'salutsalut',
			// 'var2'=>'bonsoir'
		// ));
		// $t->setShow(FALSE);
		// echo $t->show();
		// echo $GLOBALS['rubrique']->getUrl('4ffdb88d4b57d', array('salut', '14', 'troisieme'));
		
		// $sql = new sqlGc($GLOBALS['base'][BDD]);
		// $sql->setVar(array('id' => array(2, sqlGc::PARAM_INT)));
		// $sql->query('query1', 'SELECT * FROM news WHERE id=:id', 5);
		
		// $data = $sql->fetch('query1');
	
		// print_r($data);
	// echo $GLOBALS['rubrique']->affFooter();
	
	header('Content-type: image/png');  
	$nombre= mt_rand(1876,10255);
	$img = new captchaGc($nombre, array(
				'textsize' => '5',
				'largeur'=>'240', 
				'hauteur'=>'40', 
				'textcolor'=>array(255,255,255), 
				'background'=>'http://v2.freddesign.net/asset/image/back.png',
				'textposition'=>array(160,10)));
	$img->show();
	$_SESSION['captcha'] = $nombre;