<?php
	// $GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
	// echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		// $t->assign(array(
			// 'var'=> 'salutsalut',
			// 'var2'=>'bonsoir'
		// ));
		// $t->setShow(FALSE);
		// echo $t->show();
			
		// header('Content-Type: text/xml');
		// header('Content-Type: application/xml');
		$GLOBALS['rubrique']->setDevTool(false);
		$feed = new feedGc();
		$feed->addRss('rss1', 'http://www.legeekcafe.com/rss-design.xml');	
		//print_r($feed->getRss('rss1'));
		
		echo $feed->getRssTitle('rss1');
		echo $feed->getRssLink('rss1');
		echo $feed->getRssPubDate('rss1');
		print_r($feed->getItemTitle('rss1'));
		print_r($feed->getItemGuid('rss1'));
		echo $feed->showError();
	//echo $GLOBALS['rubrique']->affFooter();