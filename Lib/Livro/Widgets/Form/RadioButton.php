<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class RadioButton extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->type = 'radio';
		$this->tag->name = $this->name;
		$this->tag->value = $this->value;

		//se o campo nao é editavel
        if(!parent::getEditable())
        {
            $this->tag->readonly = "1";
        }

        //exibi a tag
        $this->tag->show();
	}
}