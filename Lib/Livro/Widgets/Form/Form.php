<?php 

namespace Livro\Widgets\Form;

use Livro\Control\ActionInterface;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\HBox;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Form\Label;

class Form extends Element
{
	protected $fields; //array de campos
	protected $actions; //array de ações
	protected $table;
	private $has_action;
	private $actions_container;

	public function __construct ($name = 'my_form')
	{
		parent::__construct('form');
		$this->enctype = "multipart/form-data";
        $this->method  = 'post';    // método de transferência
		$this->setName($name);

		//cria a tabela
		$this->table = new Table;
        $this->table->width = '100%';
        $this->table->border = '2px';
        parent::add($this->table);
	}

	public function setName ($name)
	{
		$this->name = $name;
	}

	public function getName ()
	{
		return $this->name;
	}

	//define um titulo de formulario
	public function setFormTitle ($title)
	{
		$row = $this->table->addRow();
		$row->{'class'} = 'form-title';
		$cell = $row->addCell($title);
		$cell->{'colspan'} = 2;
	}

	/**
     * Add a form field
     * @param $label     Field Label
     * @param $object    Field Object
     * @param $size      Field Size
     */
    public function addField($label,$object,$size = 100)
    {
    	$this->fields[$object->getName()] = $object;
    	$object->setSize($size);
    	$object->setLabel($label);

    	//adiciona a linha
    	$row = $this->table->addRow();
    	//cria um label
    	$label_field = new Label($label);
    	$label_field->for = $object->getName();

    	if ($object instanceof Hidden)
        {
            $row->addCell( '' );

        }
        else
        {
        	//<td>nome</td>
            $row->addCell( $label_field );
        }
        //td com input
        $row->addCell($object);

        return $row;
    }

    public function addAction($label, ActionInterface $action)
    {
    	$name = strtolower(str_replace('','_',$label));

    	//instancia um botao
    	$button = new Button($name);
    	$button->setFormName($this->name);
    	$button->setAction($label,$action);

    	if (!$this->has_action)
        {
            $this->actions_container = new HBox;

            $row  = $this->table->addRow();
            $row->{'class'} = 'formaction';
            $cell = $row->addCell( $this->actions_container );
            $cell->colspan = 2;
        }
        //add cell for button
        $this->actions_container->add($button);

        $this->has_action = true;
        $this->actions[] = $button;

        return $button;  
    }

    /**
     * retorna os campos
     */
    public function getFields()
    {
    	return $this->fields;
    }

    /**
     * retorna as ações
     */
    public function getActions()
    {
    	return $this->actions;
    }

    /**
     * Atribui dados aos campos do formulário
     * @param $object = objeto com dados
     * $object->id_grupos
     */
    public function setData($object)
    {
    	foreach ($this->fields as $name => $field)
    	{

    		if ($name AND isset($object->$name))
    		{

    			$field->setValue($object->$name);
    		}
    	}
    }

    public function getData($class = 'stdClass')
    {
    	$class = new $class;

        

    	foreach ($this->fields as $name => $object)
    	{
    		$val = isset($_POST[$name]) ? $_POST[$name] : '';

            if (!$object instanceof Button)
            {
                $class->$name = $val;
            }   
	    }
	    // percorre os arquivos de upload
	    foreach ($_FILES as $key => $content)
	    {
	       $class->$key = $content['tmp_name'];
	    }
	    return $class;
    }


}