<?php
	require_once(INCLUDE_PATH.'index'.FUNCTION_EXT.'.php');
	require_once(SQL_PATH.'index'.SQL_EXT.'.php');
	require_once(FORMS_PATH.'index'.FORMS_EXT.'.php');
	
	$GLOBALS['rubrique']->setInfo();	
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC('news', 'news', '0', 'fr');
		$t->assign(array(
			'val1'=>'salut les nazes',
			'val2'=>'salut les mecs',
			'val3'=>'salut les mecs',
			'age'=>'20',
			'chaine'=>"ééééééééééééééééééééé"
		));

		for($i=0; $i<10; $i++){
			$t->assignArray('list',array(
					'val'	=> $i,
					'timestamp'	=> time()
			));
		}
		
		// echo $GLOBALS['rubrique']->useLang('test_1');
		// $GLOBALS['rubrique']->setLang('en');
		// echo $GLOBALS['rubrique']->useLang('test_1');
		$t->show();
		
		$GLOBALS['rubrique']->windowInfo('titre', 'salut salut', 0, 'index.php');
		$GLOBALS['rubrique']->blockInfo('titre', 'salut salut', 0, 'index.php');

		$file = new file('prout.txt');
		
	echo $GLOBALS['rubrique']->affFooter();
?>