<?php
	$forms = new formsGc(array('name' => 'nom', 'action' => 'index.html'));
	$forms->addFieldset('field1');
	$forms->addInputText('field1',  'label', 'text', array('readonly' => 'readonly'), 0);