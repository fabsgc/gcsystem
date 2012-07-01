<?php
	// $GLOBALS['rubrique']->setInfo(array('title'=>'é bijour'));
	// echo $GLOBALS['rubrique']->affHeader();
		// $t= new templateGC('GCsystem', 'GCsystem', '0');
		// $t->setShow(FALSE);
		// echo $t->show();
		
		// $sql = new sqlGc($GLOBALS['base'][BDD]);
		// $sql->setVar(array('id' => array(7, sqlGc::PARAM_INT)));
		// $sql->query('query1', 'SELECT * FROM membre LIMIT 0,3', '1');
		// $sql->query('query2', 'SELECT COUNT(*) as machin FROM membre', '1');
		// $sql->query('query3', 'SELECT * FROM membre WHERE ID=:id', '1');
		
		// $sql = new sqlGc($GLOBALS['base'][BDD]);
		// $sql->query('query4', 'INSERT INTO connectes() VALUES(:id, :id)', '1');
		// $sql->query('query5', 'INSERT INTO connectes() VALUES(:id, :id)', '1');
		// $sql->setVar(array('id' => 7, 'machin' => 10));
		// $sql->fetch('query4', sqlGc::PARAM_FETCHINSERT);
		// $sql->getVar();
	
		// foreach($sql->fetch('query1') as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// foreach($sql->fetch('query3') as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// foreach($sql->fetch('query3') as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// $cache = new cacheGc('cache2', $sql->fetch('query1'), 0);
		// $cache->setCache();
		
		// foreach($cache->getCache() as $data){
			// echo $data['ID'].' '.$data['pseudo'].'<br />';
		// }
		
		// $data = $sql->fetch('query2', sqlGc::PARAM_FETCHCOLUMN);
		// echo $data;
		
		// $data = new objectGc();
		// $data->addAutocomplete('id')
			// ->setList(array('yop', 'ahaha', 'prout', 'youpi'))
			// ->show();
				
		// echo '<input id="id" />';
		
		/*$message = '[abbr title="slut"]sdfklsjlddffkd[/abbr] [sup]dfsdf[/sup] [a]sqkjlqskd[/a] [a url="skd"]sddf[/a] [img]http://localhost/GCsystem2.0/asset/image/arbo.png[/img] 
		[ul]
		[li]sdf[/li]
		[/ul]
		
		[email]sdfsdf[/email]
		
		[video]http://www.youtube.com/watch?v=RoqmSkwRH4g&feature=related[/video]
		
		[code type="php"]<?php echo "salut"; ?>[/code]
		
		http://localhost/GCsystem2.0/index.php
		
		:)';*/
		
		// $code = new bbcodeGc('fr');
		// echo $code -> parse($message);
		// $code->editor('', array('id'=>'editeur', 'name'=>'message', 'theme'=>'personnalize', color => array('FE9F4B', 'FE7A04'),  'width'=>'700px', 'height'=>'300px'));
		
		// $date = new dateGc('fr');
		// echo $date->getDateFr(time(), dateGc::DATE_COMPLETE_FR_2);
		// echo $date->getDateEn(time(), dateGc::DATE_COMPLETE_FR_2);
		// echo $date->getDateNl(time(), dateGc::DATE_COMPLETE_FR_2);
		// echo $date->getDateNl(time(), dateGc::DATE_DEFAULT);
		// echo $date->getDateEs('654689847', dateGc::DATE_COMPLETE_FR_2);
		// $age = $date->getAge('154689847', dateGc::PARAM_TIMESTAMP);
		// echo $age[0].' ans '.$age[1];
		// echo $date->getAgo('1340840627');
		// echo $date->getDecalTimeZone();
		// echo $date->isBissextile();
		// echo $date->isSummer();
		
		// $GLOBALS['rubrique']->blockInfo('sa','sa',0);
		
		// $file = new fileGc('system/class/terminalGc.class.php');
		// echo $file->getFileExt();
		// $file->getFileContent();
		// echo $file->showError();
		
		// $file = new downloadGc('index.php', 'index.html', downloadGc::EXT_HTML);
		$file = new downloadGc('asset/image/memory.png', 'test.png', downloadGc::EXT_PNG);
		echo $file->download();
		// echo $file->showError();
		
	// echo $GLOBALS['rubrique']->affFooter();
?>