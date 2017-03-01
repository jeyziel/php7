<?php 

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

trait SaveTrait
{
	public function onSave()
	{
		try
		{
			$dados = $this->form->getData();
			$dadosArray = (array) $dados;

			if (isset($dados->nome) || isset($dados->descricao))
			{
				Transaction::open($this->connection);
				$this->form->setData($dados);

				$object = new $this->activeRecord;
				$object->fromArray($dadosArray);
				$object->store();
				
				Transaction::close(); // finaliza a transação
                new Message('info', 'Dados armazenados com sucesso');

			}
			else
			{
				new Message('error','Preencha todos os campos corretamente');
			}
		}
		catch(Exception $e)
		{
			new Message('error',$e->getMessage());
		}

	}
}