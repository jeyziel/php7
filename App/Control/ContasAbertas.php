<?php 

use Livro\Control\Page;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Control\Action;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Repository;
use Livro\Database\Transaction;
use Livro\Database\Criteria;
use Livro\Database\Filter;


class ContasAbertas extends Page
{
    private $form;
    private $datagrid;
    private $loaded;

    public function __construct()
    {
        parent::__construct();

        //intancia o formulario
        $this->form = new FormWrapper(new Form('contas_abertas'));

        //campos do formulario
        $codigo = new Entry('id_cliente');

        $this->form->addField('C.Cliente',$codigo,200);
        $this->form->addAction('Buscar',new Action(array($this,'onReload')));

        //instancia a datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid());

        //cria as colunas da datagrid
        $codigo = new DatagridColumn('id_cliente','Código Cliente','center',100);
        $dtEmissao = new DatagridColumn('dt_emissao','Data Emissão','center',200);
        $dt_vencimento = new DatagridColumn('dt_vencimento','Data Vencimento','center',200);
        $valor = new DatagridColumn('valor','Preco','center',100);
        $valor->setTransformer(array($this,'formata_money'));

        //cria as actions
        $action1 = new DatagridAction(array($this,'pagar'));
        $action1->setField('id');
        $action1->setImage('ico_delete.png');
        $action1->setLabel('PAGAR');

        //adiciona as colunas e ação no datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($dtEmissao);
        $this->datagrid->addColumn($dt_vencimento);
        $this->datagrid->addColumn($valor);

        $this->datagrid->addAction($action1);

        $this->datagrid->createModel();

        $panel = new Panel('buscar Debitos');
        $panel->add($this->form);

        $panel2 = new Panel();
        $panel2->add($this->datagrid);


        parent::add($panel);
        parent::add($panel2);
    }

    

    public function pagar($id)
    {
        try
        {

            $dados = $this->form->getData(); 

            Transaction::open('sistema');
            $conta = Conta::editConta($id);

            if ($conta)
            {
                new Message('info','Conta Paga com Sucesso');
            }
            else
            {
                new Message('info','Erro ao pagar conta');
            }
            $this->onReload();  

        }
        catch (Exception $e)
        {
            new Message('error',$e->getMessage());
        }
    }

    /*
    *methodo onReload
    */
    public function onReload()
    {
        try
        {
            $dados = $this->form->getData(); 

            Transaction::open('sistema');

            $pessoa = Pessoa::find($dados->id_cliente);

            if ($pessoa)
            {
                $contas = $pessoa->getContasEmAberto();

                if ($contas)
                {
                    foreach ($contas as $conta)
                    {
                        $this->datagrid->addItems($conta);
                    }
                }
            }
            else
            {
               $repository = new Repository('Conta');
               $criteria = new Criteria;
               $criteria->add(new Filter('paga','=','N'));
               $results = $repository->load($criteria);

               if ($results)
               {
                    foreach ($results as $result)
                    {
                        $this->datagrid->addItems($result);
                    }
               }

                
            }

            Transaction::close();
            $this->loaded = true;

        }
        catch (Exception $e)
        {
            new Message('error',$e->getMessage());
        }
    }

    public function formata_money($valor)
    {
        return number_format($valor,'2',',','.');
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

