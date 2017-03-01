<?php 

namespace Livro\Control;

interface ActionInterface
{
	public function setParameters($param,$value);
	public function serialize();
}