<?php 

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\FormElementInterface;

/**
 * representa um campo de formulario
 * @author jeyziel gama
 */
abstract class Field implements FormElementInterface
{
	protected $name;
	protected $value;
	protected $editable;
	protected $size;
	protected $tag;
	protected $formLabel; //label

	public function __construct($name)
	{
		//define algumas caracteristicas iniciais
		$this->setName($name);
		$this->setSize(200);
		$this->setEditable(TRUE);

		//cria uma tag html do tipo input
		$this->tag = new Element('input');
		$this->tag->class = 'field';
		$this->tag->id = $this->getName();
	}

	/**
     * Intercepta a atribuição de propriedades
     * @param $name     Nome da propriedade
     * @param $value    Valor da propriedade
     */
    public function __set ($name,$value)
    {
    	if (is_scalar($value))
    	{
    		$this->setProperties($name,$value);
    	}
    }

    /**
     * Intercepta o retorno das propriedades
     */
    public function __get ($name)
    {
    	return $this->getProperties($name);
    }

    public function setName($name)
    {
    	$this->name = $name;
    }

    public function getName()
    {
    	return $this->name;
    }

    public function setValue ($value)
    {
    	$this->value = $value;
    }

    public function getValue ()
    {
    	return $this->value;
    }

    public function setLabel ($label)
    {
    	$this->formLabel = $label;
    }

    public function getLabel ()
    {
    	return $this->formLabel;
    }

    /**
     * [setEditable description]
     * @param [bollean] $editable = informe TRUE OR FALSE
     */
    public function setEditable ($editable)
    {
    	$this->editable = $editable;
    }

    public function getEditable()
    {
    	return $this->editable;
    }

    /**
     * Define uma propriedade para o campo
     * @param $name = nome da propriedade
     * @param $valor = valor da propriedade
     */
    public function setProperties ($name,$value)
    {
    	$this->tag->$name = $value;
    }

    /**
     * retorna a propriedade do campo
     */
    public function getProperties($name)
    {
    	return $this->tag->$name;
    }

    public function setSize ($width)
    {
    	$this->size = $width;
    }




    
} 