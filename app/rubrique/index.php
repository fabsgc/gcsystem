<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		$t->assign(array(
			'var'=> 'salutsalut',
			'var2'=>'bonsoir'
		));
		$t->setShow(FALSE);
		echo $t->show();

		// $zip = new zipGc('CodeIgniter_2.1.0.zip');
		// echo $zip->getFilePath();
		
		
	echo $GLOBALS['rubrique']->affFooter();