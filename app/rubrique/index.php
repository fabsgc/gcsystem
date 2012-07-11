<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem'));
	echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		// $t->assign(array(
			// 'var'=> 'salutsalut',
			// 'var2'=>'bonsoir'
		// ));
		// $t->setShow(FALSE);
		// echo $t->show();
		// echo $GLOBALS['rubrique']->getUrl('4ffdb88d4b57d', array('salut', '14', 'troisieme'));
		
		$sql = new sqlGc($GLOBALS['base'][BDD]);
		$sql->setVar(array('id' => array(2, sqlGc::PARAM_INT)));
		$sql->query('query1', 'SELECT * FROM news WHERE id=:id', 5);
		
		$data = $sql->fetch('query1');
	
		print_r($data);
	echo $GLOBALS['rubrique']->affFooter();