<?php 

namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\TableRow;

/**
 * cria um tabela
 * @author jeyziel
 * 
 */
class Table extends Element
{
	public function __construct ()
	{
		parent::__construct('table');
		
	}

	public function addRow()
	{
		$row = new TableRow;
		parent::add($row);
		return $row;
	}
}