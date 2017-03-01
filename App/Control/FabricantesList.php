<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Traits\DeleteTrait;
use Livro\Traits\EditTrait;
use Livro\Traits\ReloadTrait;
use Livro\Traits\SaveTrait;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;

class FabricantesList extends Page
{
	private $form;
	private $datagrid;
	private $connection;
	private $activeRecord;
	private $loaded;

	use EditTrait;
	use DeleteTrait;
	use SaveTrait
	{
		onSave as onSaveTrait;
	}
	use ReloadTrait
	{
		onReload as onReloadTrait;
	}



	public function __construct()
	{
		parent::__construct();

		$this->connection = 'sistema';
		$this->activeRecord = 'Fabricante';

		//intancia o formulario
		$this->form = new FormWrapper(new Form('form_fabricante'));

		//campos do formulario
		$codigo = new Entry('id');
		$codigo->setEditable(FALSE);
		$nome = new Entry('nome');
		$site = new Entry('site');

		//ação do formulario]
		$action = new Action(array($this,'onSave'));
		

		//adiciona os campos ao formulario
		$this->form->addField('Código',$codigo,50);
		$this->form->addField('Nome',$nome,300);
		$this->form->addField('Site',$site,300);

		//adiciona a ação do formulario
		$this->form->addAction('Enviar',$action);

		//instancia a datagrid
		$this->datagrid =  new DatagridWrapper(new Datagrid());

		//adiciona as ações
		$action1 =  new DatagridAction(array($this,'onEdit'));
		$action1->setField('id');
		$action1->setLabel('Editar');
		$action1->setImage('ico_edit.png');

		$action2 = new DatagridAction(array($this,'Delete'));
		$action2->setField('id');
		$action2->setLabel('Deletar');
		$action2->setImage('ico_delete.png');

		//instancia as colunas da datagrid
		$codigo = new DatagridColumn('id','Código','center',100);
		$nome = new DatagridColumn('nome','Descricao','center',300);
		$site = new DatagridColumn('site','Site','center',300);

		//adiciona as action ao datagrid
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);

		//adiiciona as coluna ao datagrid
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($nome);
		$this->datagrid->addColumn($site);

		$this->datagrid->createModel();

		$panel = new Panel('Formulario de Funcionarios');
		$panel->add($this->form);

		$panel2 = new Panel();
		$panel2->add($this->datagrid);

		parent::add($panel);
		parent::add($panel2);
	}

	public function onSave()
	{
		$this->onSaveTrait();
		$this->onReload();
	}

	public function onReload()
	{
		$this->onReloadTrait();
		$this->loaded = true;
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