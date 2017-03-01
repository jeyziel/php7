<?php 
use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Filter;
use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;

class ProdutosList extends Page
{
	private $form;
	private $datagrid;
	private $loaded;
	private $connection;
	private $activeRecord;
	private $filter = array();
	use DeleteTrait;
	use ReloadTrait
	{
		onReload as onReloadTrait;
	}
	public function __construct()
	{
		parent::__construct();
		$this->connection = 'sistema';
		$this->activeRecord = 'Produto';
		//instancia um formulario
		$this->form = new FormWrapper(new Form('form_busca_produtos'));
		//cria os campos do formulario
		$descricao = new Entry('descricao');
		$this->form->addField('Descrição',$descricao,300);
		$this->form->addAction('Cadastrar',new Action(array(new ProdutosForm,'onSave')));
		$this->form->addAction('Buscar',new Action(array($this,'onReload')));
		//intancia uma datagrid
		$this->datagrid = new DatagridWrapper(new Datagrid);
		//intancia as colunas da Datagrid
		$codigo = new DatagridColumn('id','Codigo','right',50);
		$descricao = new DatagridColumn('descricao','Descrição','left',270);
		$fabrica = new DatagridColumn('nome_fabricante','Fabricante','left',80);
		$estoque = new DatagridColumn('estoque','Estoq','right',40);
		$preco = new DatagridColumn('preco_venda','Venda','right',40);
		//adiciona as colunas á Datagrid
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($descricao);
		$this->datagrid->addColumn($fabrica);
		$this->datagrid->addColumn($estoque);
		$this->datagrid->addColumn($preco);
		//instancia duas ações a datagrid
		$action1 = new DatagridAction(array(new ProdutosForm,'onEdit'));
		$action1->setLabel('Editar');
		$action1->setImage('ico_edit.png');
		$action1->setField('id');
		$action2 = new DatagridAction(array($this,'Delete'));
		$action2->setLabel('Deletar');
		$action2->setImage('ico_delete.png');
		$action2->setField('id');
		//adiciona as acoes a datagrid
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);
		//cria o modelo de datagrid, montando sua estrutua
		$this->datagrid->createModel();
		$panel = new Panel('Produtos');
		$panel->add($this->form);
		$panel2 = new Panel();
		$panel2->add($this->datagrid);
		//monta a pagina por meiro de uma box
		$box = new VBox;
		$box->style = 'display-block';
		$box->add($panel);
		$box->add($panel2);
		parent::add($box);
		
	}
	public function onReload ()
	{
		//obtém os dados do formulário de buscas
		$dados = $this->form->getData();
		//verifica se o usuário preencheu o formulario
		if ($dados->descricao)
		{
			//filtra pela descrição do produto
			$this->filter[] = new Filter('descricao','like',"%{$dados->descricao}%");
		}
		$this->onReloadTrait();
		$this->loaded = true;
	}
	public function show ()
	{
		//se a listagem ainda nao foi carregada
		if (!$this->loaded)
		{
			$this->onReload();
		}
		parent::show();
	}
}