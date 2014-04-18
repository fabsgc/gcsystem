<?php
	class gcsystem extends system\controller{		
		public function init(){
		}

		public function end(){
		}
		
		public function actionTerminal(){
			$t= new system\template(GCSYSTEM_PATH.'GCterminal', 'GCterminal', '0');
			if(ENVIRONMENT == 'development') $t->assign(array('moins' => 50, 'moins2'=>80));
				else $t->assign(array('moins' => 0, 'moins2' => 30));
			$t->show();
		}
		
		public function actionTerminalParse(){
			$terminal = new terminal(strip_tags(html_entity_decode($_POST['command'])), $this->bdd);
			echo $terminal->parse();
		}

		public function actionLang(){
			if(ENVIRONMENT == 'development'){
				$dir = new \helper\dir(LANG_PATH);
				$data = array();

				//on récupère toutes les données
				foreach ($dir->getDirArbo() as $value) {
					$dataLang = array();
					$name = new \helper\file($value);
					$lang = new system\lang($name->getName());
					$dataLang[0] = $name->getName();
					$dataLang[1] = $lang->loadAllSentence();
					array_push($data, $dataLang);
				}

				//si le formulaire a été validé
				if(isset($_POST['save']) && isset($_GET['lang'])){
					//on récupère le numéro de la case du tableau qui contient les infos sur la langue de référence
					foreach ($dir->getDirArbo() as $key => $value) {
						if(LANG_PATH.$_GET['lang'].LANG_EXT == $value){
							$LangId = $key;
						}
					}

					//on parcours les données du tableau
					foreach ($dir->getDirArbo() as $value) {
						if(LANG_PATH.$_GET['lang'].LANG_EXT != $value){
							$result = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>'."\n";
							$result .='<lang>'."\n";

							$name = new \helper\file($value);
							$nameLang = $name->getName();

							foreach ($data[$LangId][1] as $key => $value) {
								$result .="\t".'<sentence id="'.$key.'">'.html_entity_decode($_POST[$nameLang.'_'.$key]).'</sentence>'."\n";
							}

							$result .='</lang>'."\n";

							file_put_contents(LANG_PATH.$nameLang.LANG_EXT, $result);
						}
					}
				}

				$data = array();

				//on met à jour avant d'afficher
				foreach ($dir->getDirArbo() as $value) {
					$dataLang = array();
					$name = new \helper\file($value);
					$lang = new system\lang($name->getName());
					$dataLang[0] = $name->getName();
					$dataLang[1] = $lang->loadAllSentence();
					array_push($data, $dataLang);
				}

				$t= new system\template(GCSYSTEM_PATH.'GClangSynchronization', 'GClangSynchronization', '0');
				$t->assign(array('data' => $data));
				$t->show();
			}
			else{
				$this->redirect404();
			}
		}
	}