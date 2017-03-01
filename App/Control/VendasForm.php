<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Session\Session;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;


class VendasForm extends Page
{
    private $form;
    private $datagrid;
    private $loaded;
    private $activeRecord;
    private $connection;

    public function __construct()
    {
        parent::__construct();

        //intancia a session
        new Session;

        //instancia o formulario
        $this->form = new FormWrapper(new Form('form_vendas'));

        //cria as colunas
        $codigo = new Entry('id_produto');
        $quantidade = new Entry('quantidade');

        //adiciona os campos e actions no formulario
        $this->form->addField('Código',$codigo,50);
        $this->form->addField('Qtd.',$quantidade,100);
        $this->form->addAction('Adicionar',new Action(array($this,'onAdiciona')));
        $this->form->addAction('Concluir',new Action(array(new ConcluiVendaForm,'onLoad')));

        //intancia a datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //cria as colunas
        $codigo    = new DatagridColumn('id_produto', 'Código', 'center', 50);
        $descricao = new DatagridColumn('descricao',   'Descrição','left', 200);
        $quantidade= new DatagridColumn('quantidade',  'Qtde',      'left', 40);
        $preco     = new DatagridColumn('preco',       'Preço',    'left', 70);

        //cria as ações
        $action = new DatagridAction(array($this,'onDelete'));
        $action->setLabel('Deletar');
        $action->setImage('ico_delete.png');
        $action->setField('id_produto');

        //adiciona as colunas
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($preco);

        //adiciona as ações
        $this->datagrid->addAction($action);

        //cria o modelo
        $this->datagrid->createModel();


        $panel = new Panel('Adicionar Produtos');
        $panel->add($this->form);

        $panel2 = new Panel();
        $panel2->add($this->datagrid);

        parent::add($panel);
        parent::add($panel2);
       
    }

    public function onAdiciona()
    {
        try
        {
           $item = $this->form->getData();
           Transaction::open('sistema');

           $produto = Produto::find($item->id_produto);

           if ($produto)
           {
               $item->descricao = $produto->descricao;
               $item->preco = $produto->preco_venda * $item->quantidade;
               $list = Session::getValue('list');
               $list[$item->id_produto] = $item;
               Session::setValue('list',$list);


           }
           
           Transaction::close();
        }
        catch(Exception $e)
        {
            new Message('error',$e->getMessage());  
        }
        $this->onReload();
    }

    public function onDelete($param)
    {
        //le variavel $list da sessao
        $list = Session::getValue('list');
        unset($list[$param]);
        //grava a variavel de volta
        Session::setValue('list',$list);
        $this->onReload();
    }

    public function onReload()
    {
        $items = Session::getValue('list');

        //limpa a datagrid
		$this->datagrid->clear();

        if ($items)
        {
            foreach ($items as $item)
            {
                $this->datagrid->addItems($item);
            }
        }
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