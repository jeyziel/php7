<?php 

namespace Livro\Traits;

use Livro\Database\Criteria;
use Livro\Database\Repository;
use Livro\Database\Transaction;

trait ReloadTrait
{
	public function onReload()
	{
		try
		{
			Transaction::open($this->connection);
			$this->datagrid->clear();
			
			$repository = new Repository($this->activeRecord);
			$criteria = new Criteria();
			$criteria->setProperty('order','id');

			if (isset($this->filter))
			{
				foreach ($this->filter as $filter)
				{
					$criteria->add($filter);
				}
			}

			$objects = $repository->load($criteria);
			
			if ($objects)
			{
				foreach ($objects as $object)
				{
					$this->datagrid->addItems($object);
				}
			}

			//fecha a conexao com o banco de dados;
			Transaction::close();
		}
		catch (Exception $e)
		{
			new Message('error',$e->getMessage());
		}
	}
}