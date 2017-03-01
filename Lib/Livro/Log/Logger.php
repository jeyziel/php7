<?php 

namespace Livro\Log;

/**
 * classe abstrada para gragas os logs
 * @author jeyziel Gama
 */
abstract class Logger
{
	protected $filename;

	public function __construct ($filename)
	{
		$this->filename = $filename;
		//reseta o conteudo do arquivo
		file_put_contents($filename,'');
	}

	//define metodo write como obrigatorio
	abstract public function write($message);
}