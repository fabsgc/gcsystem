<?php
	/*\
	 | ------------------------------------------------------
	 | @file : TerminalDelete.class.php
	 | @author : fab@c++
	 | @description : terminal command delete
	 | @version : 3.0 bêta
	 | ------------------------------------------------------
	\*/
	
	namespace system\Terminal;

	class TerminalDelete extends TerminalCommand{
		public function module(){
			//choose the module name
			while(1==1){
				echo ' - choose the module you want to delete : ';
				$src = argvInput::get(STDIN);

				if(file_exists(DOCUMENT_ROOT.SRC_PATH.$src.'/')){
					break;
				}
				else{
					echo "[ERROR] this module doesn't exist\n";
				}
			}

			$xml = simplexml_load_file(APP_CONFIG_SRC);
			$datas =  $xml->xpath('//src');

			foreach ($datas as $data) {
				if($data['name'] == $src){
					$dom = dom_import_simplexml($data);
    				$dom->parentNode->removeChild($dom);
				}
			}

			$xml->asXML(APP_CONFIG_SRC);
			$dom = new \DOMDocument("1.0");
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->load(APP_CONFIG_SRC);
			$dom->save(APP_CONFIG_SRC);

			terminal::rrmdir(SRC_PATH.$src, true);
			terminal::rrmdir(WEB_PATH.$src, true);
			rmdir(SRC_PATH.$src);
			rmdir(WEB_PATH.$src);

			echo ' - the module has been successfully delete';
		}

		public function controller(){

		}
	}