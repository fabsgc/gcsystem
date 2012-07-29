<?php
	$class = new index();	
	
	$sql = new sqlGc($GLOBALS['base'][BDD]);
	$sql -> query('nom', 'SELECT * FROM news WHERE id=:id');
	$sql -> query('nom2', 'SELECT * FROM news WHERE id=:id');
	
	$class->setVarArray(array(
		'sql' => $sql,
		'forms' => $forms,
		'id' => 1));
	
	$class->devClass();