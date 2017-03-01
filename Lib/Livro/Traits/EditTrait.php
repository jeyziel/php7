<?php 

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

trait EditTrait
{
	public function onEdit($param)
	{
		try
		{
			if ($param)
			{
				Transaction::open($this->connection);
				$class = new $this->activeRecord;
				$object = $class::find($param);
				

				$this->form->setData($object);

				Transaction::close();
			}
			else
			{
				new Message('error','id nao cadastro');
			}
		}
		catch (Exception $e)
		{
			new Message('error',$e->getMessage());
			Transaction::rollback();
		}
	}
}