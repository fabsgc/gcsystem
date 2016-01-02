<?php
	namespace Orm\Entity;

	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	class Book extends Entity{
		public function tableDefinition(){
			$this->name('book');
			$this->field('id')
				->primary(true)
				->unique(true)
				->type(Field::INCREMENT);
			$this->field('content')
				->type(Field::STRING)
				->size(512);
		}
	}