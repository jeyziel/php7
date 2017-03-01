<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class Entry extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->name = $this->getName();
		$this->tag->value = $this->getValue();
		$this->tag->type = 'text';
        $this->tag->style = "width:{$this->size}px";

        if (!parent::getEditable())
        {
        	$this->tag->readonly = "1";
        }
        $this->tag->show();

	}
}