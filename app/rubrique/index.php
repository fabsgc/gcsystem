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
		// $zip = new zipGc('C:\wamp\www\GCsystem.zip');
		//echo $zip->getFilePath();
		// echo $zip->getFilesCompressedSize();
		// print_r($zip->getContentZip());
		// print_r($zip->getContentFileZip());
		// $zip->putFileToFtp('test/', zipGc::NOPUTDIR, array());
		// $zip->putFileToFtp('test/', zipGc::PUTDIR, array('css'));
		// $zip->putFileToFtp('test/', zipGc::NOPUTDIR, array('php', 'css'));
		
		// foreach($zip->getFileCompressedSize() as $clé => $val){
			// if(is_file($cle)){
				// echo 'salope';
			// }
			// else{
				// echo 'merde';
			// }
		// }	
		
	echo $GLOBALS['rubrique']->affFooter();