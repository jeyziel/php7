<?php 

namespace Livro\Widgets\Dialog;

use Livro\Control\Action;
use Livro\Widgets\Base\Element;

class Question
{
	public function __construct($message,Action $action_yes, Action $action_no = null)
	{
		$div = new Element('div');
		$div->class = 'alert alert-warning';

		//cria a action URL
		$url_yes = $action_yes->serialize();

		//cria o link yes
		$link_yes = new Element('a');
		$link_yes->href = $url_yes;
		$link_yes->class = 'btn btn-success';
		$link_yes->add('SIM');

		//adiciona action sim a question
		$message .= '&nbsp;' . $link_yes; //cria o link yes

		if ($action_no)
		{
			$url_no = $action_no->serialize();

			//cria o link no
			$link_no = new Element('a');
			$link_no->href = $url_no;
			$link_no->class = 'btn btn-default';
			$link_no->add('Não');

			$message .= '&nbsp' . $link_no;
		}

		$div->add($message);
		$div->show();




	}
}