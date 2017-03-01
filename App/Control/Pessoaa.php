<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Widgets\Base\Image;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Form\CheckButton;
use Livro\Widgets\Form\CheckGroup;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\File;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Password;
use Livro\Widgets\Form\RadioGroup;
use Livro\Widgets\Form\Text;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;


class Pessoa extends Page
{
	public $form;
	public $datagrid;

	public function __construct ()
	{
		Transaction::open('sistema');

		try
		{
			$cidade = Cidade::find(1);
			$estados = $cidade->estado;
			Transaction::close();
			var_dump($estados);
		}catch (Exception $e)
		{

		}
		
	}

	public function teste ($param)
	{
		echo 'sim';
		
		
	}

	public function nao ($param)
	{
		echo 'nao';
	}
}