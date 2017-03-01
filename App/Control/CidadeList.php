<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Traits\DeleteTrait;
use Livro\Traits\EditTrait;
use Livro\Traits\ReloadTrait;
use Livro\Traits\SaveTrait;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;

class CidadeList extends Page
{
	private $form;
	private $datagrid;
	private $activeRecord;
	private $connection;
	private $loaded;

	use EditTrait;
	use DeleteTrait;
	use ReloadTrait
	{
		onReload as onReloadTrait;
	}
	use SaveTrait
	{
		onSave as onSaveTrait;
	}

	public function __construct()
	{
		parent::__construct();

		$this->connection = 'sistema';
		$this->activeRecord = 'Cidade';

	 	//instancia o formulario
		$this->form = new FormWrapper(new Form('form_cidade'));
		//campos do formulario
		$codigo = new Entry('id');
		$codigo->setEditable(FALSE);
		$cidade = new Entry('nome');
		$estado = new Combo('id_estado');

		Transaction::open($this->connection);
		//obtem os estádos no banco
		$estados = Estado::all();
		$items = array();
		foreach ($estados as $obj_estado)
		{
			$items[$obj_estado->id] = $obj_estado->nome;
		}
		//adiciona os itens ao combo estado
		$estado->addItems($items);

		//adiciona os campos do formulario
		$this->form->addField('id',$codigo,50);
		$this->form->addField('Nome',$cidade,300);
		$this->form->addField('Estado',$estado,300);
		

		//adiciona as actions ao formulario
		$this->form->addAction('Salvar',new Action(array($this,'onSave')));
		$this->form->addAction('Cancelar',new Action(array($this,'clear')));

		//intancia a datagrid
		$this->datagrid = new DatagridWrapper(new Datagrid());

		//instancia as colunas da datagrid
		$codigo = new DatagridColumn('id','Código','center',50);
		$nome = new DatagridColumn('nome','Nome','left',150);
		$nome_estado = new DatagridColumn('nome_estado','Estado','left',150);

		//intancia as acoes da datagrid
		$action1 = new DatagridAction(array($this,'onEdit'));
		$action1->setField('id');
		$action1->setLabel('Editar');
		$action1->setImage('ico_edit.png');

		$action2 = new DatagridAction(array($this,'Delete'));
		$action2->setField('id');
		$action2->setLabel('Deletar');
		$action2->setImage('ico_delete.png');

		//adiciona a coluna a datagrid
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($nome);
		$this->datagrid->addColumn($nome_estado);

		//adiciona as acoes a datagrid
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);

		//cria o modelo da datagrid
		$this->datagrid->createModel();

		$panel1 = new Panel('Cidades');
		$panel1->add($this->form);

		$panel2 = new Panel();
		$panel2->add($this->datagrid);


		parent::add($panel1);
		parent::add($panel2);

		

	}

	public function onSave()
	{
		$this->onSaveTrait();
		$this->onReload();
	}

	public function clear()
	{

	}

	public function onReload()
	{
		$this->onReloadTrait();
		$this->loaded = true;
	}

	public function show ()
	{
		if (!$this->loaded)
		{
			$this->onReload();
		}
		parent::show();
	}

}
