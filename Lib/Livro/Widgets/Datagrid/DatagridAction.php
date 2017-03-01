<?php 

namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

/**
 * Representa uma action de uma datagrid
 * @author jeyziel gama
 */

class DatagridAction extends Action
{
	private $image;
	private $field;
	private $label;

	/*atribui uma imagem a ação*/
	public function setImage($image)
	{
		$this->image = $image;
	}

	public function getImage()
	{
		return $this->image;
	}

	/**
     * Define o nome do campo que será passado juntamente com a ação
     * @param $field = nome do campo do banco de dados
     */
	public function setField($field)
	{
		$this->field = $field;
	}

	/**
     * Retorna o nome do campo definido pelo método setField()
     */
	public function getField()
	{
		return $this->field;
	}

	/** atribui um label */
	public function setLabel($label)
	{
		return $this->label = $label;
	}


	public function getLabel()
	{
		return $this->label;
	}
}