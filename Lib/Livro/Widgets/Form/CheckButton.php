<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class CheckButton extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->type = 'checkbox';
		$this->tag->name = $this->name;
		$this->tag->value = $this->value;

		if (!parent::getEditable())
		{
			$this->tag->readonly = "1";
		}

		$this->tag->show();

	}
}