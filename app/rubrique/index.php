<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'é bijour'));
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC('GCsystem', 'GCsystem', '0');
		$t->setShow(FALSE);
		echo $t->show();
		$modo = new modoGc('sale pute va te faire foutre', 25);
		print_r($modo->parse());
		echo $modo->censure();
	echo $GLOBALS['rubrique']->affFooter();