<?php 

namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Form;

/**
 * classe para criar um formulario usando a biblioteca bootstrap
 * @author jeyzielGama
 */
class FormWrapper
{
	private $decorated;

	public function __construct(Form $form)
	{
		$this->decorated = $form;
	}

	public function __call($method,$parameters)
	{
		return call_user_func_array(array($this->decorated,$method),$parameters);
	}
    
	public function show ()
	{
		$element = new Element('form');
        $element->class = "form-horizontal";
        $element->enctype = "multipart/form-data";
        $element->method = 'post';
        $element->name = $this->decorated->getName();

        foreach ($this->decorated->getFields() as $field)
        {
        	 $group = new Element('div');
        	 $group->class = 'form-group';

        	 //cria o rotulo de texto
        	 $label = new Element('label');
             $label->class = 'col-sm-2 control-label';
             $label->for = $field->getName();   //nome do campo no bando
             $label->add($field->getLabel()); //rotulo de texto

             //cria a coluna
             $col = new Element('div');
             $col->class = 'col-sm-10';
             $col->add($field);

             $field->class = 'form-control';

             //adiciona elementos ao grupo
             $group->add($label);
             $group->add($col);
            

             $element->add($group);
        }

        $group = new Element('div');
        $group->class = 'form-group';

        $col = new Element('div');
        $col->class = 'col-sm-offset-2 col-sm-10';

        $i = 0;

        foreach ($this->decorated->getActions() as $action)
        {
        	$col->add($action);
        	$class = ($i == 0) ? 'btn-success' : 'btn-default';
        	$action->class = 'btn ' . $class;
        	$i++;
        }

        //adiciona a coluna ao grupo
        $group->add($col);
        $element->add($group);

        //realiza o metodo show do formulario
        $element->width = '100%';
        $element->show();


	}

}