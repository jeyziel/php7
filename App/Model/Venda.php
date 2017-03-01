<?php 

use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Record;
use Livro\Database\Repository;

class Venda extends Record
{
    private $itens;
    private $cliente;
    const TABLENAME = 'venda';

    public function set_cliente(Pessoa $c)
    {
        $this->cliente = $c;
        $this->id_cliente = $c->id;
    }

    public function get_cliente()
    {
        if (empty($this->cliente))
        {
            $this->cliente = new Pessoa($this->id_cliente);
        }
    }

    public function addItem(Produto $p,$quantidade)
    {
        $item = new ItemVenda();
        $item->produto = $p; //id produto
        $item->preco = $p->preco_venda;
        $item->quantidade = $quantidade;


        //armazena os items em um array
        $this->itens[] = $item;
    }

    public function store()
    {
       
        if ($this->itens)
        {
            foreach ($this->itens as $item)
            {
                $item->id_venda = $this->id;
                $item->store();

            }
            parent::store();
        }
         
    }

    /**
    *retorna os itens vendidos de uma venda
    */
    public function get_itens()
    {   
        $repository = new Repository('ItemVenda');
        $criteria = new Criteria();
        $criteria->add(new Filter('id_venda','=',$this->id));
        $this->itens = $repository->load($criteria);
        return $this->itens;
    }
}