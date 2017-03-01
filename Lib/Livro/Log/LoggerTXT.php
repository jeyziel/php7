<?php 

namespace Livro\Log;

use Livro\Log\Logger;

/**
 * implementa o algoritmo de logger em txt
 * @author jeyziel gama
 */

class LoggerTXT extends Logger
{
	/**
	 * [write description]
	 * @param  [string] $message [Mensagem a ser escrita]
	 */
	public function write ($message)
	{
		date_default_timezone_set('America/Sao_Paulo');
		$time = date('d-m-Y H:i:s');

		//monta a string
		$text = "$message ::: $time\n";

		$handler = fopen($this->filename,'a');
		fwrite($handler,$text);
		fclose($handler);

	}
}