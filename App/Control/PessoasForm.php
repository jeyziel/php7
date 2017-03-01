<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\CheckGroup;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\FormWrapper;

class PessoasForm extends Page
{
	private $form;
	private $activeRecord;
	private $connection;

	public function __construct()
	{
		parent::__construct();
		

		//instancia o formularioi
		$this->form = new FormWrapper(new Form());
		$id = new Entry('id');
		$nome = new Entry('nome');
		$endereco = new Entry('endereco');
		$bairro = new Entry('bairro');
		$telefone = new Entry('telefone');
		$email = new Entry('email');
		$cidade = new Combo('id_cidade');
		$grupo = new CheckGroup('ids_grupos');

		//carrega todas as cidades 
		Transaction::open('sistema');
		$cidades = Cidade::all();
		$items = array();

		//adiciona as cidades no combo do formulario
		foreach ($cidades as $obj_cidade)
		{
			$items[$obj_cidade->id] = $obj_cidade->nome;
		}
		$cidade->addItems($items);

		//adiciona os grupos no formulario
		$items = array();
		$grupos = Grupo::all();
		foreach ($grupos as $obj_grupo)
		{
			$items[$obj_grupo->id] = $obj_grupo->nome;
		}
		$grupo->addItems($items);

		Transaction::close();//fecha a transacao

		//define alguns atributos das coluna
		$id->setEditable(FALSE);
		$grupo->setLayout('horizontal');

		//adiciona os campos ao formulario
		$this->form->addField('Código',$id,100);
		$this->form->addField('Nome',$nome,300);
		$this->form->addField('Endereço',$endereco,300);
		$this->form->addField('Bairro',$bairro,200);
		$this->form->addField('Telefone',$telefone,200);
		$this->form->addField('Email',$email,200);
		$this->form->addField('Cidade',$cidade,200);
		$this->form->addField('Grupo',$grupo,200);

		//adiciona as ações
		$this->form->addAction('Salvar',new Action(array($this,'onSave')));

		$panel = new Panel('Formulario de Cadastro de Pessoas');
		$panel->add($this->form);

		parent::add($panel);
	}

	public function onSave()
	{
		try
		{
			$dados = $this->form->getData();
			if (!empty($dados->nome))
			{
				Transaction::open('sistema');
				$this->form->setData($dados);

				$pessoa = new Pessoa();
				$pessoa->fromArray((array) $dados);
				$pessoa->store();

				if ($dados->ids_grupos)
				{
					if (empty($dados->id))
					{
						foreach ($dados->ids_grupos as $grupo_id)
						{
							$pessoa->addGrupo(new Grupo($grupo_id));
						}
					}
					else
					{
						$pessoa->delGrupo();
						foreach ($dados->ids_grupos as $grupo_id)
						{
							$pessoa->addGrupo(new Grupo($grupo_id));
						}
					}
				}
				Transaction::close(); //Finaliza a transação
				new Message('info','Dados armazenados com sucesso');
			}
			else
			{
				new Message('info','Preencha todos os dados');
			}
		}catch (Exception $e)
		{
			//exibe a mensagem de exceção 
			new Message('error', '<b>Erro  </b>' . $e->getMessage());
			//desgas todas alterações no banco de dados
			Transaction::rollback();
		}

	}

	public function onEdit($id)
	{
		try
		{
			Transaction::open('sistema');
			$pessoa = Pessoa::find($id);

			$pessoa->ids_grupos = $pessoa->getIdGrupos();
			$this->form->setData($pessoa);

			Transaction::close();
		}
		catch (Exception $e)
		{
			new Message('error', '<b>Error<b>' . $e->getMessage());
			//desgas todas alterações no banco de dados
			Transaction::rollback();
		}
	}

	
}