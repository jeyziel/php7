<?php 

namespace Livro\Session;

class Session
{
	public function __construct ()
	{
		if (!session_id())
		{
			session_start();
		}
	}

	public static function setValue(string $name,$value)
	{
		$_SESSION[$name] = $value;
	}

	public static function getValue ($name)
	{
		if (isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
	}

	public static function freeSession()
	{
		$_SESSION = array();
		session_destroy();
	}


}