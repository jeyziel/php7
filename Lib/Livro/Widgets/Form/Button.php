<?php
/**
 * Created by PhpStorm.
 * User: jeyzi
 * Date: 06/01/2017
 * Time: 15:04
 */

namespace Livro\Widgets\Form;

use Livro\Control\Action;
use Livro\Control\ActionInterface;


class Button extends Field implements FormElementInterface
{
    private $action;
    private $label;
    private $formName;

    public function setAction($label, ActionInterface $action)
    {
        $this->action = $action;
        $this->label = $label;
    }

    public function setFormName($name)
    {
        $this->formName = $name;
    }

    public function show()
    {
        $url = $this->action->serialize();
        //define as propriedades do botao
        $this->tag->name = $this->name; //nome da tag
        $this->tag->type = 'button'; //tipo do input
        $this->tag->value = $this->label; //eótulo do botao

        //define a ação do botao
        //define a ação do botao
        $this->tag->onclick = "document.{$this->formName}.action='{$url}';" .
                              "document.{$this->formName}.submit()";
        //exibe o botao
        $this->tag->show();
    }




}