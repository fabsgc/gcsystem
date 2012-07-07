<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'é bijour'));
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC('GCsystem', 'GCsystem', '0');
		$t->setShow(FALSE);
		echo $t->show();
		$modo = new modoGc('sale pute va te faire foutre');
		print_r($modo->parse());
	echo $GLOBALS['rubrique']->affFooter();