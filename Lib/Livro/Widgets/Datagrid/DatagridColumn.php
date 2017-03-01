<?php 

namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridColumn
{
	private $name;
	private $width;
	private $label;
	private $align;
	private $action;
	private $transformer;

	 /**
     * Instancia uma coluna nova
     * @param $name = nome da coluna no banco de dados
     * @param $label = rótulo de texto que será exibido
     * @param $align = alinhamento da coluna (left, center, right)
     * @param $width = largura da coluna (em pixels)
     */
	public function __construct($name, $label, $align, $width)
	{
		$this->name = $name;
		$this->label = $label;
		$this->align = $align;
		$this->width = $width;
	}

	/*retorna a colina no bando de dados */
	public function getName()
	{
		return $this->name;
	}

	/** retorna o rótulo de texto */
	public function getLabel()
	{
		return $this->label;
	}

	/** retorna o alinhamento */
	public function getAlign()
	{
		return $this->align;
	}

	/** retorna a largura */
	public function getWidth()
	{
		return $this->width;
	}

	public function setAction(Action $action)
	{
		$this->action = $action;
	}

	/** ja retorna a url */
	public function getAction()
	{
		if ($this->action)
		{
			return $this->action->serialize();
		}
	}

	/** recebe uma funcao do tipo callable */
	public function setTransformer(callable $transformer)
	{
		$this->transformer = $transformer;
	}

	/** retorna uma funcao callable */
	public function getTransformer()
	{
		return $this->transformer;
	}




}