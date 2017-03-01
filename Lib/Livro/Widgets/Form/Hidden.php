<?php 

namespace Livro\Widgets\Form;



use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;



class Hidden extends Field implements FormElementInterface
{
	public function show ()
	{
		$this->tag->name = $this->name;
		$this->tag->value = $this->value;
		$this->tag->type = 'hidden';
		$this->tag->style = "width:{$this->size}px"; //tamanho em pixels

       //exibe a tag
       $this->tag->show();
	}
}