<?php 
require "Config/Config.php";

use Livro\Control\Page;
use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Repository;
use Livro\Database\Transaction;
use Livro\Log\LoggerTXT;




$a = Page::getClass();

$template = file_get_contents('App/Templates/template.php');
$content = '';
$class   = 'Home';

if (class_exists($a))
{
	$class = $a;
	try
	{
		$pagina = new $a;
		ob_start();
        $pagina->show();
        $content = ob_get_contents();
        ob_end_clean();
	}
	catch (Exception $e)
	{
		 $content = $e->getMessage() . '<br>' .$e->getTraceAsString();	
	}
}

$output = str_replace('{URL_PATH}','http://localhost:8888/',$template);
$output = str_replace('{content}', $content, $output);
$output = str_replace('{class}',   $class, $output);
echo $output;





