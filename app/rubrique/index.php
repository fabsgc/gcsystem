<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'GCsystem', 'doctype' => 'html5'));
	echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC(GCSYSTEM_PATH.'GCsystem', 'GCsystem', '0');
		// $t->assign(array(
			// 'var'=> 'salutsalut',
			// 'var2'=>'bonsoir'
		// ));
		// $t->setShow(FALSE);
		// echo $t->show();
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
		
		$feed = new feedGc();
		$feed->newRss('new');
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
		
		$feed->addItem('new', array(
			array(
				'title' => 'merde',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100'),
			array(
				'title' => 'merde2222',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100')
		));
		
		$feed->addItem('new', 
			array(
				'title' => 'merde4',
				'link' => 'http://www.legeekcafe.com/news-240.html',
				'guid' => 'http://www.legeekcafe.com/news-240.html',
				'pubDate' => 'Sun, 15 Jul 2012 11:27:28 +0100')
		);
		
		print_r($feed->getGenRss());
		
	echo $GLOBALS['rubrique']->affFooter();