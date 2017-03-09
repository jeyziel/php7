<?php 

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Repository;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\FormWrapper;

class VendasReport extends Page
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        //instancia o formulario
        $this->form = new FormWrapper(new Form('contas_report'));

        $data_ini = new Entry('data_ini');
        $data_ini->placeholder = '00/00/0000';
        $data_fim = new Entry('data_fim');
        $data_fim->placeholder = '00/00/0000';

        //adiciona as fields e actions
        $this->form->addField('Data_inicial',$data_ini,300);
        $this->form->addField('Data_Final',$data_fim,300);
        $this->form->addAction('Gerar',new Action(array($this,'onGera')));

        $panel = new Panel('Relatorios de Conta');
        $panel->add($this->form);

        parent::add($panel);



    }

    public function onGera()
    {
         require_once('Lib/Twig/Autoloader.php');
         Twig_Autoloader::register();
         $loader = new Twig_Loader_Filesystem('App/Resources');
		 $twig = new Twig_Environment($loader);
		 $template = $twig->loadTemplate('vendas_report.html');

         //obtem os dados do formulario
         $dados = $this->form->getData();

         //joga de volta os valores no formulario
         $this->form->setData($dados);

         $data_conv_to_us = function($data)
         {
             if (!empty($data))
             {
                 $data = explode('/',$data);
                 return "$data[2]-$data[1]-$data[0]";
             }
         };

         $data_ini = $data_conv_to_us($dados->data_ini);
         $data_fim = $data_conv_to_us($dados->data_fim);

         $replaces['data_ini'] = $data_ini;
         $replaces['data_fim'] = $data_fim;

         try
         {
            Transaction::open('sistema');

            $repository = new Repository('Venda');
            $criteria = new Criteria();
            $criteria->setProperty('order','data_venda');  

            if ($dados->data_ini)
            {
                $criteria->add(new Filter('data_venda','>=',$data_ini));
            } 
            if ($dados->data_fim)
            {
                $criteria->add(new Filter('data_venda','<=',$data_fim));
            }

            $vendas = $repository->load($criteria);

            if ($vendas)
            {
                foreach ($vendas as $venda)
                {
                    $venda_array = $venda->toArray();
                    $venda_array['nome_cliente'] = $venda->cliente->nome;
                    $items = $venda->itens;

                    if ($items)
                    {
                        foreach($items as $item)
                        {
                            $item_array = $item->toArray();
                            $item_array['descricao'] = $item->produto->descricao;
                            $venda_array['itens'][] = $item_array;
                        }
                    }
                    $replaces['vendas'][] = $venda_array;
                }
            }

           
            

            Transaction::close();
         }
         catch (Exception $e)
         {
            new Message('error',$e->getMessage());
            Transaction::rollback();
         }
        $content = $template->render($replaces);
        parent::add($content);
    }
}