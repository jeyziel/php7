<?php 

use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Record;
use Livro\Database\Repository;

/**
 * Model Pessoa
 * @author Jeyziel Gama
 */
class Pessoa extends Record 
{
	const TABLENAME = 'pessoa';
	private $cidade;

	public function get_cidade()
	{
		if (empty($this->cidade))
		{
			$this->cidade = new Cidade($this->id_cidade);
		}
		return $this->cidade->nome;
	}

	//adiciona um grupo 
	public function addGrupo(Grupo $grupo)
	{
		$pessoa_grupo = new PessoaGrupo();
		$pessoa_grupo->id_grupo = $grupo->id;
		$pessoa_grupo->id_pessoa = $this->id;
		$pessoa_grupo->store();
	}
	//exclui os grupos de uma determinada pessoa
	public function delGrupo()
	{
		//instancia e cria o criterio
		$criteria = new Criteria();	
		$criteria->add(new Filter('id_pessoa','=',$this->id));
		//instancia o repositorio
		$repository = new Repository('PessoaGrupo');
		return $repository->delete($criteria);
	}

	//retorna os grupos de uma pessoa
	public function getGrupos()
	{
		$grupos = array();
		$criteria = new Criteria;
		$criteria->add(new Filter('id_pessoa','=',$this->id));

		$repo = new Repository('PessoaGrupo');
		$results = $repo->load($criteria);

		if ($results)
		{
			foreach ($results as $result)
			{
				$grupos[] = new Grupo($result->id_grupo);
			}
		} 
		return $grupos;

	}

   /**
    * Retorna os ids de grupos da pessoa
    */
	public function getIdGrupos()
	{
		$grupos_ids = array();

		$grupos = $this->getGrupos();
		if ($grupos)
		{
			foreach ($grupos as $grupo)
			{
				$grupos_ids[] = $grupo->id;
			}
		}
		return $grupos_ids;

	}

	 /**
     * Retorna as contas em aberto
     */
	public function getContasEmAberto()
	{
		return Conta::getByPessoa($this->id);
	}

	/**
	 * Retorna o total de debitos
	 */
	public function totalDebitos()
	{
		return Conta::debitoPorPessoa($this->id);
	}

	


}