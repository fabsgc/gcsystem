<?php
	switch($_GET['action']){
		case 'terminal':
			$terminal = new terminalGC(htmlentities($_POST['message']));
			echo $terminal ->parse();
		break;
		
		default:
			$GLOBALS['rubrique']->setInfo(array('title'=>'é bijour', 'css'=>''));
			echo $GLOBALS['rubrique']->affHeader();
				$t= new templateGC('GCterminal', 'GCterminal', '0');
				if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
					else $t->assign(array('moins' => 0, 'moins2' => 30));
				$t->show();
			echo $GLOBALS['rubrique']->affFooter();
		break;
	}
?>