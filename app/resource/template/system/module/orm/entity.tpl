{{php: $var='<?php
	namespace Orm\Entity;

	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	/**
'.$property.'	*/

	class '.$class.' extends Entity{
		public function tableDefinition(){
			$this->name(\''.$table.'\');
			$this->form(\'form-'.strtolower($table).'\');
'.$field.'		}

        public function beforeInsert(){

        }

        public function beforeUpdate(){

        }

        public function beforeDelete(){

        }
	}';
}}
{$var}