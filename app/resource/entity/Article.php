<?php
	namespace Orm\Entity;

	use System\Orm\Builder;
	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	class Article extends Entity{
		public function tableDefinition(){
			$this->name('article');
			$this->form('form-article');
			$this->field('id')
				->primary(true)
				->type(Field::INCREMENT);
			$this->field('title')
				->type(Field::STRING)
				->size(255)
				->beNull(false);
			$this->field('content')
				->type(Field::TEXT)
				->size(65536)
				->beNull(false);
			$this->field('posts')
				->foreign([
					'type' => ForeignKey::ONE_TO_MANY,
					'reference' => ['Post', 'article'],
					'current' => ['Article', 'id'],
					'belong' => ForeignKey::COMPOSITION,
					'join' => Builder::JOIN_LEFT
				]);
		}

		public function beforeUpdate(){
			//echo 'test';
		}
	}