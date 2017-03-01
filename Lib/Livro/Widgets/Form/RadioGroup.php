<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\FormElementInterface;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\RadioButton;

class RadioGroup extends Field implements FormElementInterface
{
	private $layout = 'vertical';
	private $items;

	public function setLayout ($dir)
	{
		$this->layout = $dir;
	}

	public function addItems($items)
	{
		$this->items = $items;
	}

	public function show ()
	{
		if ($this->items)
		{
			foreach ($this->items as $index => $label)
			{
				$button = new RadioButton($this->name);
				$button->setValue($index);

				if ($index == $this->value)
				{
					 //marca o radio button
                    $button->setProperties('checked','1');
				}
				$label = new Label($label);
				$label->add($button);
				$label->show();

				if ($this->layout == 'vertical')
				{
					//exibe a tag de quebra de linha
					$br = new Element('br');
					$br->show();
				}
				echo "\n";
			}
		}
	}
}