<?php
	$GLOBALS['rubrique']->setInfo(array('title'=>'é bijour'));
	echo $GLOBALS['rubrique']->affHeader();
		$t= new templateGC('GCsystem', 'GCsystem', '0');
		$t->setShow(FALSE);
		echo $t->show();
		
		$sql = new sqlGc($GLOBALS['base'][BDD]);
		$sql->setVar(array('id' => array(7, sqlGc::PARAM_INT)));
		$sql->query('query1', 'SELECT * FROM membre LIMIT 0,3', '1');
		$sql->query('query2', 'SELECT COUNT(*) as machin FROM membre', '1');
		$sql->query('query3', 'SELECT * FROM membre WHERE ID=:id', '1');
		
		foreach($sql->fetch('query1') as $data){
			echo $data['ID'].' '.$data['pseudo'].'<br />';
		}
		
		foreach($sql->fetch('query3') as $data){
			echo $data['ID'].' '.$data['pseudo'].'<br />';
		}
		
		foreach($sql->fetch('query3') as $data){
			echo $data['ID'].' '.$data['pseudo'].'<br />';
		}
		
		$cache = new cacheGc('cache2', $sql->fetch('query1'), 0);
		$cache->setCache();
		
		foreach($cache->getCache() as $data){
			echo $data['ID'].' '.$data['pseudo'].'<br />';
		}
		
		$data = $sql->fetch('query2', sqlGc::PARAM_FETCHCOLUMN);
		echo $data;
		
	echo $GLOBALS['rubrique']->affFooter();
?>