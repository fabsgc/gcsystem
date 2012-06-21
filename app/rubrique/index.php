<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'é bijour'));
	echo $GLOBALS['rubrique']->affHeader();
		echo $GLOBALS['rubrique']->getLangClient(); //constructeur
		$t= new templateGC('gcsystem', 'GCsystem', '0');
		$t->setShow(FALSE);
		echo $t->showError();
		echo $t->show();

		// $sql = new sqlGc($GLOBALS['base'][BDD]);
		// $sql->setVar(array('id' => array(7, sqlGc::PARAM_INT)));
		// $sql->query('query1', 'SELECT * FROM membre LIMIT 0,3', '1000');
		// $sql->query('query2', 'SELECT COUNT(*) as machin FROM membre', '10');
		
		// foreach($sql->fetch('query1') as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// $cache = new cache('cache2', $sql->fetch('query1'), 0);
		// $cache->setCache();
		
		// foreach($cache->getCache() as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// $data = $sql->fetch('query2', sqlGc::PARAM_FETCHCOLUMN);
		// echo $data;
		
		// $forms= new formsGC("livreor", "livreor".FILES_EXT, "post", "", "", "");
		// $forms->addFieldset('ajouter un message');
		// $forms->addHtml('ajouter un message', '<label style="margin: auto; width: 680px;">Message</label><br />');
		// $forms->addTextarea('ajouter un message', '', '', array('name'=>'message', 'id'=>'textarea', 'cols'=>60, 'rows'=>10), 2);
		// $forms->addHtml('ajouter un message', '<label><img src="captcha.html" alt="captcha" /></label>');
		// $forms->addInputText('ajouter un message', '', '', array('name'=>"captcha", 'size'=>25),  2);
		// $forms->addSubmitReset("submit", array('value'=>'envoyer', 'name'=>"button_livreor"), 0);
		// $forms->showForms();
			
	echo $GLOBALS['rubrique']->affFooter();
?>