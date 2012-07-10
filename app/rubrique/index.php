<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem'));
	echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		// $t->setShow(FALSE);
		// echo $t->show();
		ini_set('xdebug.max_nesting_level', 500);
		$f = new dirGc('system/');
		echo $f->getSize();
	echo $GLOBALS['rubrique']->affFooter();