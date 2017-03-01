<?php 

namespace Livro\Widgets\Base;

/**
 * classe suporte para tag
 * @author jeyziel gama
 */
class Element 
{
	protected $tagname; //nome das tag
	protected $properties; //propriedades das tags
	protected $children; //filhos das tag

	public function __construct($tagname)
	{
		$this->tagname = $tagname;
	}

	/**
     * Intercepta as atribuições à propriedades do objeto
     * @param $name   = nome da propriedade
     * @param $value  = valor
     */
    public function __set($name, $value)
    {
    	$this->properties[$name] = $value;
    }

    /**
     * retorna a propriedade
     */
    public function __get ($name)
    {
    	// retorna o valores atribuídos ao array properties
        return isset($this->properties[$name])? $this->properties[$name] : NULL;
    }

    /**
     * adiciona um elemento filho
     */
    public function add ($children)
    {
    	$this->children[] = $children;
    }

    /**
     * exibe a abertura
     */
    public function open ()
    {
    	echo "<{$this->tagname}";
    	if ($this->properties)
    	{
    		foreach ($this->properties as $name => $value)
    		{
    			if (is_scalar($value))
    			{
    				echo " {$name}=\"{$value}\"";
    			}
    		}	
    	}
    	echo '>';
    }

    /**
     * exibe a tag na tela com seu conteudo
     */
    public function show ()
    {
    	//abre a tag
    	$this->open();
    	echo "\n";
    	if ($this->children)
    	{
    		foreach ($this->children as $child)
    		{
    			if (is_object($child))
    			{
    				$child->show();
    			}
    			else if (is_scalar($child))
    			{
    				echo $child;
    			}
    		}
    		//fecha a tag
    		$this->close();
    	}
    }

    /** fecha tag html */
    public function close ()
    {
    	echo "</{$this->tagname}>\n";
    }

    public function __toString()
    {
    	ob_start();
    	$this->show();
    	$content = ob_get_clean(); //ob_get_contens
    	return $content;
    }
    


}