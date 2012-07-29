<?php
	$class = new terminal();
	
	switch($_GET['action']){
		case 'terminal':
			$class->commande();
		break;
		
		default:
			$class->index();
		break;
	}
?>