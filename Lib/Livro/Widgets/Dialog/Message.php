<?php

/**
 * Created by PhpStorm.
 * User: jeyzi
 * Date: 01/01/2017
 * Time: 22:05
 */

namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

class Message
{
    public function __construct($type, $message)
    {
        $div = new Element('div');
        if($type == 'info'){
            $div->class = 'alert alert-info';
        }
        elseif ($type == 'error'){
            $div->class = 'alert alert-danger';
        }
        $div->add($message);
        //var_dump($div);
        $div->show();
    }
}