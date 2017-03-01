<?php 
use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Traits\EditTrait;
use Livro\Traits\SaveTrait;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\RadioGroup;
use Livro\Widgets\Wrapper\FormWrapper;

class ProdutosForm extends Page
{
	private $connection;
	private $activeRecord;
	private $form; //formulario
	use SaveTrait;
	use EditTrait;

	public function __construct()
	{
		parent::__construct();
		$this->connection = 'sistema';
		$this->activeRecord = 'Produto';
		//instancia o formulario
		$this->form = new FormWrapper(new Form('form_produtos'));
		//cria os campos do formulario
		$codigo = new Entry('id');
		$descricao = new Entry('descricao');
		$estoque = new Entry('estoque');
		$preco_custo = new Entry('preco_custo');
		$preco_venda = new Entry('preco_venda');
		$fabricante = new Combo('id_fabricante');
		$tipo = new RadioGroup('id_tipo');
		$unidade = new Combo('id_unidade');
		//carrega os itens do fabricante
		Transaction::open('sistema');
		$fabricantes = Fabricante::all();
		$items = array();
		foreach ($fabricantes as $obj_fabricante)
		{
			$items[$obj_fabricante->id] = $obj_fabricante->nome;
		}
		$fabricante->addItems($items);
		//carrega os itens dos tipos
		$tipos = Tipo::all();
		$items = array();
		foreach ($tipos as $obj_tipo)
		{
			$items[$obj_tipo->id] = $obj_tipo->nome;
		}
		$tipo->addItems($items);
		//carrega os tipos de combo
		$unidades = Unidade::all();
		$items = array();
		foreach ($unidades as $obj_unidade)
		{
			$items[$obj_unidade->id] = $obj_unidade->nome;
		}
		$unidade->addItems($items);
		Transaction::close();
		//define alguns atributos para os campos
		$codigo->setEditable(FALSE);
		$this->form->addField('Código',$codigo,100);
		$this->form->addField('Descricao',$descricao,300);
		$this->form->addField('Estoque',$estoque,300);
		$this->form->addField('Preço custo',$preco_custo,200);
		$this->form->addField('Preço Venda',$preco_venda,200);
		$this->form->addField('Fabricante',$fabricante,300);
		$this->form->addField('Tipo',$tipo,300);
		$this->form->addField('Unidade',$unidade,300);
		$this->form->addAction('Salvar',new Action(array($this,'onSave')));
		//cria um panel para conter
		$panel = new Panel('Produtos');
		$panel->add($this->form);
		parent::add($panel);
		
	}
}