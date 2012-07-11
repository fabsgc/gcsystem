<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem'));
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		$t->setShow(FALSE);
		echo $t->show();
		$GLOBALS['rubrique']->getUrl('4ffdb88d4b57d', array('salut', '14', 'troisieme'));
	echo $GLOBALS['rubrique']->affFooter();