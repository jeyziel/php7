<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Repository;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;

class PessoasList extends Page 
{
	private $form;
	private $datagrid;
	private $loaded;

	public function __construct()
	{
		parent::__construct();
		//instancia o formulario de contato
		$this->form = new FormWrapper(new Form('form_pessoa'));

		//instancia as colunas
		$nome = new Entry('nome');
		//instancia as ações e coluna do formulario
		$this->form->addField('Nome',$nome,200);
		$this->form->addAction('Buscar',new Action(array($this,'onReload')));
		$this->form->addAction('Novo',new Action(array('PessoasForm','onSave')));

		//instancia a datagrod
		$this->datagrid = new DatagridWrapper(new Datagrid());

		//adiciona as colunas
		$codigo = new DatagridColumn('id','Codigo','center',100);
		$nome = new DatagridColumn('nome','Nome','center',300);
		$endereco = new DatagridColumn('endereco','Endereço','center',300);
		$cidade = new DatagridColumn('cidade','Cidade','center',300);

		//instancia duas ações da datagrid
		$action1 = new DatagridAction(array(new PessoasForm,'onEdit'));
		$action1->setField('id');
		$action1->setLabel('Editar');
		$action1->setImage('ico_edit.png');
		

		$action2 = new DatagridAction(array($this,'Delete'));
		$action2->setLabel('Deletar');
		$action2->setImage('ico_delete.png');
		$action2->setField('id');
		//adiciona as colunas da datagrid
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($nome);
		$this->datagrid->addColumn($endereco);
		$this->datagrid->addColumn($cidade);

		//adiciona as acoes
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);

		$this->datagrid->createModel();

		$panel = new Panel('Formulario De Pessoas');
		$panel->add($this->form);

		$panel2 = new Panel();
		$panel2->add($this->datagrid);

		parent::add($panel);
		parent::add($panel2);


		

	}

	public function onReload()
	{
		try
		{
			Transaction::open('sistema');

			$repository = new Repository('Pessoa');

			//cria o criterio de seleção de dados
			$criteria = new Criteria();
			$criteria->setProperty('order','id');

			$dados = $this->form->getData();

			if ($dados->nome)
			{
				$criteria->add(new Filter('nome','like',"%{$dados->nome}%"));
			}

			//carrega as pessoas que satisfazem o criterio
			$pessoas = $repository->load($criteria);

			$this->datagrid->clear();

			if ($pessoas)
			{
				foreach ($pessoas as $pessoa)
				{
					$this->datagrid->addItems($pessoa);
				}
			}
			//finaliza transacao
			Transaction::close();
			$this->loaded = true;
		}
		catch (Exception $e)
		{
			new Message('error','<b>Error<b>' . $e->getMessage());
		}
	}

	public function Delete($param)
	{
		$action = new Action(array($this,'onDelete'));
		$action->setParameters('id',$param);

		new Question('Deseja Realmente Deletar?',$action);
	}

	public function onDelete($param)
	{
		try
		{
			Transaction::open('sistema');
			$pessoa = Pessoa::find($param);
			$pessoa->delete();
			$pessoa->delGrupo();
			Transaction::close();

			$this->onReload();
			new Message('info',"Registro Excluido com Sucesso");
		}
		catch (Exception $e)
		{
			new Message('error',$e->getMessage());
		}
	}

	public function show()
	{
		if (!$this->loaded)
		{
			$this->onReload();
		}
		parent::show();
	}
}