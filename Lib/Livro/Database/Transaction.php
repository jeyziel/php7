<?php 

namespace Livro\Database;

use Livro\Log\Logger;

/**
 * classe para realizar a transacao com o bando de dados
 * @author jeyziel gama
 */
class Transaction
{
	private static $conn;
	private  static $logger;

	/**
	 * nao pode ser instanciado
	 */
	private function __construct(){}

	public static function open($database)
	{
		if (empty(self::$conn))
		{
			self::$conn = Connection::open($database);
			//iniciao transacao
			self::$conn->beginTransaction();
			//desliga o log de sql
			self::$logger = null;
		}
	}

	/**
	 * retorna a conexao ativa da transação
	 */
	public static function get ()
	{
		return self::$conn;
	}

	/**
	 * desfaz todas as operações realizas na transação
	 */
	public static function rollback ()
	{
		self::$conn->rollback();
		self::$conn = null;
	}

	/**
	 * aplica todas as operãções realizadas e fecha a conexao
	 */
	
	public static function close ()
	{
		if (self::$conn)
		{
			self::$conn->commit();
			self::$conn = null;
		}

	}

	/**
	 * define qual algoritmo de log será usado
	 */
	public static function setLogger (Logger $logger)
	{
		self::$logger = $logger;
	}

	/**
	 * armazena uma mensagem no arquivo de log
	 */
	public static function log ($message)
	{
		if (self::$logger)
		{
			self::$logger->write($message);
		}
	}
}

