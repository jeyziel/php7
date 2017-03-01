<?php 

namespace Livro\Database;

use PDO;
use Exception;

/**
 * cria conexoes com o banco de dados
 * @author : jeyziel Gama
 */

Final class Connection
{
	private function __construct (){}//nao pode ser instanciada

	 /**
     * Recebe o nome do conector de BD e instancia o objeto PDO
     */
	public static function open ($name)
	{
		if (file_exists("App/Config/{$name}.ini"))
		{
			$db = parse_ini_file("App/Config/{$name}.ini");
			
		}
		else
		{
			throw new Exception("Arquivo '$name' não encontrado");
		}

		//le as informações do arquivo
		$user = $db['user'] ?? NULL;
		$name = $db['name'] ?? NULL;
		$pass = $db['pass'] ?? NULL;
		$host = $db['host'] ?? NULL;
		$type = $db['type'] ?? NULL;
		$port = $db['port'] ?? NULL;

		switch ($type)
		{
			case 'mysql':
				$port = $port ? $port : '3306';
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
			case 'sqlite':
				$conn = new PDO("sqlite:{$name}");
				break;		
		} 
		// define para que o PDO lance exceções na ocorrência de erros
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}


}