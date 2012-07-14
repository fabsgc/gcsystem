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
		
		// echo $GLOBALS['rubrique']->getUrl('4ffdb88d4b57d', array('salut', '14', 'troisieme'));		
		
		// $mail = new mailGc(array(
			// 'expediteur' => array('moi', 'fabienbeaudimi@hotmail.fr'),
			// 'reply' => array('moi', 'fabienbeaudimi@hotmail.fr'),
			// 'destinataire' => array('fabienbeaudimi@hotmail.fr', 'contact@legeekcafe.com', 'alex5190@live.fr'),
			// 'sujet' => 'salut',
			// 'priority' => '5',
			// 'cc' => array('blob@hotmail.fr', 'ct@legeekcafe.com')
		// ));
		
		// $mail->addTemplate('email', array());
		// $mail->addText('email');
		// $mail->addFile('asset/image/GCsystem/empty_avatar.png', 'image 3', 'image/png');
		// $mail->addFile('asset/image/GCsystem/logo300_6.png', 'image 1', 'image/png');
		// $mail->addFile('asset/image/GCsystem/logo.png', 'image 2', 'image/png');
		// $mail->send();
		
		// $sql = new sqlGc($GLOBALS['base'][BDD]);
		// $sql->setVar(array('id' => array(2, sqlGc::PARAM_INT)));
		// $sql->query('query1', 'SELECT * FROM news WHERE id=:id', 5);
		
		// $data = $sql->fetch('query1');
	
		// print_r($data);
	echo $GLOBALS['rubrique']->affFooter();