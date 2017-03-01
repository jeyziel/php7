<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;

class Combo extends Field implements FormElementInterface
{
	private $items;

	public function __construct($name)
	{
		parent::__construct($name);
		$this->tag = new Element('select');
		$this->tag->class = 'combo';
	}

	public function addItems($items)
	{
		$this->items = $items; //recebe um array de itens
	}

	public function show()
	{
                
	$this->tag->name = $this->name;
        $this->tag->style = "width:{$this->size}px;";//tamanho em px

        //adiciona opçao a combo
        $option = new Element('option');
        $option->add(' ');
        $option->value = 0;
        $this->tag->add($option);

        if ($this->items)
        {
        	foreach ($this->items as $index => $label)
        	{
        		$option = new Element('option');
        		$option->value = $index;
        		$option->add($label);

        		if ($index == $this->value)
        		{
        			$option->selected = "1";
        		}	
        		//adiciona opção a combo
        		$this->tag->add($option);
        	}
        	
        }
        if (!parent::getEditable())
        {
        	$this->tag->readonly = "1";
        }
        //exibe o combo
        $this->tag->show();
	}

}