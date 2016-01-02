<?php
	namespace Orm\Entity;

	use System\Orm\Entity\Entity;
	use System\Orm\Entity\Field;
	use System\Orm\Entity\ForeignKey;

	/**
	 * @property integer id
	 * @property string content
	 * @property integer article
	 * @property \System\Orm\Entity\Type\File file
	*/

	class Post extends Entity{
		public function tableDefinition(){
			$this->name('post');
			$this->form('form-post');
			$this->field('id')
				->primary(true)
				->type(Field::INCREMENT);
			$this->field('content')
				->type(Field::TEXT)
				->beNull(false);
			$this->field('article')
				->type(Field::INT)
				->beNull(false)
				->foreign(['type' => ForeignKey::MANY_TO_ONE, 'reference' => ['Article', 'id']]);
			$this->field('file')
				->type(Field::FILE)
				->beNull(false);
		}

		public function beforeInsert(){
			$this->validation->text('content', 'contenu')
				->equal('content', 'vous devez écrire "content"')
				->custom('title');

			$this->validation->text('article.content', 'content')
				->equal('content', 'vous devez écrire "content"');

			$this->validation->text('article.title', 'article nom')
				->sql([
					'query' => 'SELECT COUNT(*) FROM article WHERE title = :value',
					'constraint' => '==', 
					'value' => 0, 
					'vars' => []
				],
				'cet article existe déjà')
				->custom('title');

			$this->validation->file('file', 'fichier')
				->accept(['image/png', 'image/jpeg'], 'le fichier doit être une image png')
				->extension(['png', 'jpeg', 'jpg'], 'le fichier n\'a pas la bonne extension');
		}
	}