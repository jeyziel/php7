<?php 

namespace Livro\Widgets\Datagrid;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridColumn;

/**
 * cria um modelo de datagrid
 * @author jeyziel gama
 */
class Datagrid extends Table
{

	private $column;
	private $action;

	public function addColumn(DatagridColumn $column)
	{
		$this->column[] = $column;
	}

	public function addAction(DatagridAction $action)
	{
		$this->action[] = $action;
	}

	public function createModel()
	{

		$thead = new Element('thead');

        // adiciona uma linha à tabela
        $row = new Element('tr');
        $thead->add($row);

        parent::add($thead);

		if ($this->action)
		{
			foreach ($this->action as $action)
			{
				$celula = new Element('th');
                $celula->width = '40px';
                $row->add($celula);
			}
		}

		if ($this->column)
		{
			foreach ($this->column as $column)
			{
				// obtém as propriedades da coluna $name, $label, $align, $width
				$name = $column->getName();
				$label = $column->getLabel();
				$align = $column->getAlign();
				$width = $column->getWidth();

				$celula = new Element('th');
				$celula->add($label);
				$celula->align = $align;
                $celula->width = $width;

                //adiciona a celula a linha <tr>
                $row->add($celula);

                if ($column->getAction())
                {
                	$url = $column->getAction(); //ja vem serializado :P
                	$celula->onclick = "document.location='$url'";
                }
			}
		}
	}

	public function clear()
	{
		// faz uma cópia do cabeçalho
        $copy = $this->children[0];
        
        // inicializa o vetor de linhas
        $this->children = array();
        
        // acrescenta novamente o cabeçalho
        $this->children[] = $copy;
        
        // zera a contagem de linhas
        $this->rowcount = 0;
	}

	public function addItems($object)
	{
		//adiciona uma linha
		$row = parent::addRow();

		//cria as actions
		if ($this->action)
		{
			foreach ($this->action as $action)
			{
				$url = $action->serialize(); //url
				$label = $action->getLabel(); //rotulo
				$image = $action->getImage(); //imagem
				$field = $action->getField(); //campo do banco

				// obtém o campo do objeto que será passado adiante
                $key    = $object->$field;

                //cria o link
                $link = new Element('a');
               	$link->href = "{$url}" . '/' . $key;

               	if ($image)
               	{
               		$img = new Element('img');
               		$img->src = "http://localhost:8888/App/Images/{$image}";
               		$img->title = $label;
               		$link->add($img);
               	}
               	else
               	{
               		$link->add($label);
               	}
               	//adiciona uma celula a linha
               	$row->addCell($link);
			}
		}

		//cria as colunas
		if ($this->column)
		{
			foreach ($this->column as $column)
			{
				//obtém as propriedades das colunas
				$name = $column->getName(); //nome do campo no banco
				$align = $column->getAlign(); 
				$width = $column->getWidth();
				$function = $column->getTransformer();
				$url = $column->getAction();

				$data = $object->$name; //obtem o dado do objeto de acordo com o campo do banco

				// verifica se há função para transformar os dados
				if ($function)
				{
					$data = call_user_func($funcion,$data);
				}

				//adiciona os dados na celula
				$celula = $row->addCell($data);
				$celula->width = $width;
				$celula->align = $align;

			}
		}
		 // incrementa o contador de linhas
        $this->rowcount ++;
	}




}

