<?php 

namespace Livro\Control;

use Livro\Control\ActionInterface;

class Action implements ActionInterface
{
	private $action;
	private $param;

	public function __construct (callable $action)
	{
		$this->action = $action;
	}

	public function setParameters($param,$value)
	{
		$this->param[$param] = $value;
	}

	/**
	 * transforma uma ação em url
	 */
	public function serialize()
	{
		if (is_array($this->action))
		{
			$url['class'] = is_object($this->action[0]) ? get_class($this->action[0]) : $this->action[0];
			$url['method'] = $this->action[1];

			if ($this->param)
			{
				$url = array_merge($url,$this->param);
			}

			$url = '/' . implode('/',$url);

			return $url;


		}
	}
}