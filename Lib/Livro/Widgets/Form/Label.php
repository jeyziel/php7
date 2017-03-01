<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class Label extends Field implements FormElementInterface
{
	public function __construct($name)
	{
		$this->tag = new Element('label');
		$this->setName($name);	
	}

	public function add($child)
	{
		$this->tag->add($child);
	}

	public function show ()
	{
		$this->tag->add($this->name);
		$this->tag->show();
	}
}