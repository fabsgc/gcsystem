{{php: $var='<?php
	namespace Orm\Entity;

	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	/**

	*/

	class '.$class.' extends Entity{
		public function tableDefinition(){
			$this->name(\''.$table.'\');
'.$field.'		}
	}';
}}
{$var}