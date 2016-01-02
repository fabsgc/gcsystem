<?php
	namespace Controller\Request\Gcs;

	use \Controller\Request\Plugin\Captcha;
	use \System\Request\Form;

	class FormRequest extends Form{
		use captcha;

		public function init(){
			$this->form = 'form-request';
		}

		public function post(){
			$this->captcha();
			$this->validation->text('text', 'champs de texte')
				->equal('post', 'Le champs doit avoir la valeur "post"');
		}

		public function put(){
			$this->captcha();
			$this->validation->text('text', 'champs de texte')
				->equal('put', 'Le champs doit avoir la valeur "put"')
				->in(['un', 'deux', 'trois'], 'la valeur doit être un, deux ou trois')
				->lengthBetween([10, 20], 'la taille doit être entre 10 et 20 caractères')
				->alpha('la valeur ne doit contenir que des lettres')
				->countIn([1,2], 'il y a trop peu de valeurs')
				//->notExist('le champs ne doit pas exister')
				->exist('le champs doit exister')
				->custom('username');

			$this->validation->file('form', 'fichiers')
				->accept(['image/png'], 'le fichier doit être une image')
				->extension(['png'], 'le fichier n\'a pas la bonne extension')
				->countIn([2,2], 'il faut deux fichiers');

			$this->validation->checkbox('check', 'checkbox')
				->count(2, 'il faut cocher les deux cases');

			$this->validation->select('list', 'liste')
				->count(1, 'vous devez choisir une valeurs')
				->different('', 'vous devez donner une valeur');
		}
	}