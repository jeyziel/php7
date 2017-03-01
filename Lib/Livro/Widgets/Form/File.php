<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class File extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->name = $this->name;
		$this->tag->value = $this->value;
		$this->tag->type = 'file';

		if (!parent::getEditable())
		{
			$this->tag->readonly = "1";
		}

		$this->tag->show();
	}
}