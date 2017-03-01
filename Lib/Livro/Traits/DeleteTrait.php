<?php 

namespace Livro\Traits;

use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;

trait DeleteTrait
{
	public function delete($param)
	{
		$action1 = new Action(array($this,'onDelete'));
		$action1->setParameters('id',$param);
		new Question('Deseja Realmente Excluir?',$action1);
	}

	public function onDelete($param)
	{
		try
		{
			
			Transaction::open($this->connection); //abre a transação
			$class = $this->activeRecord;

			$object = new $class($param);

			if ($object)
			{
				$object->delete();
			}
			Transaction::close();
			$this->onReload();
			new Message('info',"Registro Excluido com Sucesso");
		}
		catch (Exception $e)
		{
			new Message('erro',$e->getMessage());
		}
	}


}