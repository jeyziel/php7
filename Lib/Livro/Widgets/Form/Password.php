<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

/**
 * classe responsavel por criar um input de password
 * @author jeyziel gama
 */
class Password extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->name = $this->name;
		$this->tag->value = $this->value; 
		$this->tag->type = 'password';

		if (!parent::getEditable())
		{
			$this->tag->readonly = "1";
		}

		$this->tag->show();
	}
}