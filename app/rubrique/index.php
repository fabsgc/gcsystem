<?php
	require_once(INCLUDE_PATH.'index'.FUNCTION_EXT.'.php');
	require_once(SQL_PATH.'index'.SQL_EXT.'.php');
	require_once(FORMS_PATH.'index'.FORMS_EXT.'.php');

	$GLOBALS['rubrique']->setInfo(array('title'=>'émerde'));
	echo $GLOBALS['rubrique']->affHeader();
		echo $GLOBALS['rubrique']->getLangClient(); //constructeur
		$t= new templateGC('gcsystem', 'GCsystem', '0', 'nl');
		$t->setShow(FALSE);
		echo $t->show();

		$sql = new sqlGc($GLOBALS['base'][BDD]);
		$sql->setVar(array('id' => array(7, sqlGc::PARAM_INT)));
		$sql->query('query1', 'SELECT * FROM membre LIMIT 0,3');
		$sql->query('query2', 'SELECT COUNT(*) as machin FROM membre');
		
		foreach($sql->fetch('query1') as $data){
			echo $data['ID'].' '.$data['pseudo'].'<br />';
		}
		
		$data = $sql->fetch('query2', sqlGc::PARAM_FETCHCOLUMN);
		echo $data;
			
	echo $GLOBALS['rubrique']->affFooter();
?>