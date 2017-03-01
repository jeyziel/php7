<?php 

namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\TableCell;

class TableRow extends Element
{
	public function __construct ()
	{
		parent::__construct('tr');
	}

	public function addCell($value)
	{
		$cell = new TableCell($value);
		parent::add($cell);
		return $cell;
	}
}