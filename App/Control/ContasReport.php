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

class ContasReport extends Page
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
		$template = $twig->loadTemplate('contas_report.html');
        
        //obtem os valores do formulario
        $dados = $this->form->getData();

        //joga de volta no formulario
        $this->form->setData($dados);

        //converte data par ao padrão americano
        $data_conv_to_us = function($data)
        {
            if (!empty($data))
            {
                $data = explode('/',$data);
                return "$data[2]-$data[1]-$data[0]";
            }
        };

        //ler os campos do formulario
        $data_ini = $data_conv_to_us($dados->data_ini);
        $data_fim = $data_conv_to_us($dados->data_fim);

        //vetor de parametro para o template
        $replaces = array();
        $replaces['data_ini'] = $data_ini;
        $replaces['data_fim'] = $data_fim;

        try
        {
            //inicia a transação com o banco livro
            Transaction::open('sistema');

            //instancia um repositorio da classe conta
            $repositorio = new Repository('Conta');

            //cria um criterio
            $criteria = new Criteria();
            $criteria->setProperty('order','dt_vencimento');

            if ($data_ini)
            {
                $criteria->add(new Filter('dt_vencimento','>=',$data_ini));
            }
            if ($data_fim)
            {
                $criteria->add(new Filter('dt_vencimento','<=',$data_fim));
            }

            //lê todas as contas que satisfazem ao criterio
			$contas = $repositorio->load($criteria);

            if ($contas)
            {
                foreach ($contas as $conta)
                {
                    $conta_array = $conta->toArray();
                    $conta_array['nome_cliente'] = $conta->cliente->nome;
					$replaces['contas'][] = $conta_array;
                }
            }

           

            //var_dump($replaces);
            Transaction::close();
            $content = $template->render($replaces);
		    parent::add($content);

        }
        catch(Exception $e)
        {
            new Message('error',$e->getMessage());
        }

        




    }
}