<?php 

namespace Livro\Control;

use Livro\Widgets\Base\Element;

class Page extends Element
{
	private $router;
	private static $class;
	private static $url;

	public function __construct()
	{
		parent::__construct('div');
	}

	public function getUrl ()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	public static function getClass()
	{
		self::$url = self::getUrl();
		self::$url = explode('/', self::$url);

		self::$class = isset(self::$url[1]) ? ucfirst(self::$url[1]) : null;
		return self::$class;

	}

	public function show ()
	{
		$class = self::$class ?? null;
		$method = isset(self::$url[2]) ? ucfirst(self::$url[2]) : null;

		if (class_exists($class))
		{
			$object = $class == get_class($this) ? $this : new $class;
			if (method_exists($object, $method))
			{
					call_user_func(array($object, $method), self::$url[3]?? null);
			}
		}
		else
		{
		    if (file_exists($class))
			{
				require_once "$class";

			}
			else
			{
				$className = 'notFound';
				new $className;
			}	
		}	

		parent::show();
			
	}


}