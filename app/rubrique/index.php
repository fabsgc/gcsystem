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
			
		header('Content-Type: text/xml');
		header('Content-Type: application/xml');
		$GLOBALS['rubrique']->setDevTool(false);
		$feed = new feedGc();
		$feed->newRss('new', '10');
		$feed->addHeader('new', array(
			'title' => 'page test',
			'link' => 'http://www.legeekcafe.com/',
			'description' => 'Description du flux',
			'copyright' => 'Le geek cafe',
			'language' => 'fr',
			'image' => array(
				'title' => 'Le geek cafe',
				'url' => 'http://www.legeekcafe.com/image/icone.png',
				'link' => 'http://www.legeekcafe.com/'),
			'pubDate' => 'Wed, 25 Jul 2012 21:36:56 +0100'
		));
		
		echo $_SERVER['HTTP_CONTENT_TYPE'];
		
		$feed->addItem('new', array(
			array(
				'title' => 'merde-dfmlo',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100',
				'description' => 'une appli xml'),
			array(
				'title' => 'merde2222',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100',
				'description' => 'une appli xml é<br />'),
		));
		
		$feed->addItem('new', 
			array(
				'title' => 'merde4',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100',
				'description' => 'une appli xml')
		);
		
		echo $feed->showRss('new');		
	//echo $GLOBALS['rubrique']->affFooter();