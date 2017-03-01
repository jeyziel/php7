<?php 

namespace Livro\Database;

/**
 * CLASSE ABSTRATA PARA PERMITIR DEFINIÇÕES DE EXPRESSOES
 * @author jeyziel gama
 */
abstract class Expression 
{
	const AND_OPERATOR = 'AND ';
	const OR_OPERATOR = 'OR ';

	//marca metodo dump como obrigatório
	abstract public function dump();
}
