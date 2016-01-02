<?php
	namespace Orm\Entity;

	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	class Page extends Entity{
		public function tableDefinition(){
			$this->name('page');
			$this->field('id')
				->primary(true)
				->unique(true)
				->type(Field::INCREMENT)
				->beNull(false);
			$this->field('image_id')
				->unique(true)
				->type(Field::INT)
				->beNull(false)
				->foreign(['type' => ForeignKey::ONE_TO_ONE, 'reference' => ['Image', 'id']]);
		}
	}