<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Session\Session;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Text;
use Livro\Widgets\Wrapper\FormWrapper; 

class ConcluiVendaForm extends Page
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        
        new Session;

        //intancia o formulario
        $this->form = new FormWrapper(new Form('form_conclui_venda'));

        //cria os campos do formulario
		$cliente = new Entry('id_cliente');
		$valor_venda = new Entry('valor_venda');
		$desconto = new Entry('desconto');
		$acrescimos = new Entry('acrescimos');
		$valor_final = new Entry('valor_final');
		$parcelas = new Combo('parcelas');
		$obs = new Text('obs');
		$parcelas->addItems(array(1=> 'Uma', 2=> 'Duas', 3 =>'Três'));
		$parcelas->setValue(1);

        // define uma ação de cálculo Javascript
        $desconto->onBlur = "$('[name=valor_final]').val( Number($('[name=valor_venda]').val()) + Number($('[name=acrescimos]').val()) - Number($('[name=desconto]').val()));";
        $acrescimos->onBlur = $desconto->onBlur;

        $this->form->addField('Cliente', $cliente,   200);
        $this->form->addField('Valor', $valor_venda, 200);
        $this->form->addField('Desconto', $desconto, 200);
        $this->form->addField('Acréscimos', $acrescimos, 200);
        $this->form->addField('Final', $valor_final, 200);
        $this->form->addField('Parcelas', $parcelas, 200);
        $this->form->addField('Obs', $obs, 200);
        $this->form->addAction('Salvar', new Action(array($this, 'onGravaVenda')));

        $panel = new Panel('Conclui venda');
        $panel->add($this->form);
        
        parent::add($panel);

    }

    public function onLoad()
    {
        $total = 0;
        $itens = Session::getValue('list');

        if ($itens)
        {
            foreach ($itens as $item)
            {
                $total += $item->preco;
            }
        }

        $dados = new StdClass;
        $dados->valor_final = $total;
        $dados->valor_venda = $total;

        $this->form->setData($dados);


    }

    public function onGravaVenda()
    {
        try
        {
            $dados = $this->form->getData();
            Transaction::open('sistema');

            $cliente = Pessoa::find($dados->id_cliente);
            
            if (!$cliente)
            {
                throw new Exception("Cliente nao cadastrado");
            }

            if ($cliente->totalDebitos() > 0)
            {
                throw new Exception("Existe débitos em aberto por favor pague eles");
            }

            $venda = new Venda;
            $venda->cliente     = $cliente; //set_cliente
            $venda->data_venda  = date('Y-m-d');
            $venda->valor_venda = $dados->valor_venda;
            $venda->desconto    = $dados->desconto;
            $venda->acrescimos  = $dados->acrescimos;
            $venda->valor_final = $dados->valor_final;
            $venda->obs         = $dados->obs;

            $items = Session::getValue('list');

            foreach ($items as $item)
            {
                $venda->addItem(new Produto($item->id_produto),$item->quantidade);
            }

            //armazena venda no banco de dados
            $venda->store();

             //gera o financeiro
            Conta::gerarParcelas($dados->id_cliente,2,$dados->valor_final,$dados->parcelas);

            Transaction::close(); //finaliza a transação

            Session::setValue('list',array());

            //exibe mensagem de sucesso
            new Message('info','Venda registrada com sucesso');

            $this->form->setData($dados);  

        }
        catch (Exception $e)
        {
            new Message('error',$e->getMessage());
        }


    }

    
}