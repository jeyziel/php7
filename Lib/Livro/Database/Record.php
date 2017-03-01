<?php 

namespace Livro\Database;

use Livro\Database\Criteria;
use Livro\Database\RecordInterface;
use Livro\Database\Repository;
use Livro\Database\Transaction;

/**
 * permite definir um activeRecord
 */
abstract class Record implements RecordInterface
{
	protected $data;

	/**
     * Instancia um Active Record. Se passado o $id, já carrega o objeto
     * @param [$id] = ID do objeto
     */
	public function __construct ($id = null)
	{	
		if ($id)
		{
			$object = $this->load($id);
			if ($object)
			{
				$this->fromArray($object->toArray());
			}
		}
	}

	/**
     * Limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone()
    {
        unset($this->data['id']);
    }

	public function __set($name,$value)
	{
		if (method_exists($this,'set_' . $name))
		{
			call_user_func(array($this,'set_' . $name),$value);
		}
		else
		{
			if ($value === NULL)
			{
				unset($this->data[$name]);
			}
			else
			{
				$this->data[$name] = $value;
			}
		}
	}

	public function __get($name)
	{
		if (method_exists($this,'get_' . $name))
		{
			return call_user_func(array($this,'get_' . $name));
		}
		else
		{
			if (isset($this->data[$name]))
			{
				return $this->data[$name];
			}
		}
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	/**
	 * 
     * Retorna o nome da entidade (tabela)
     */
    public function getEntity ()
    {
    	$class = get_class($this);
    	return constant("{$class}::TABLENAME");
    }

	/**
	 * preenche os dados com objetos de um array
	 */
	public function fromArray($data)
	{
		$this->data = $data;
	}

	/**
	 * retorna o objeto $this-<data de um array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * armazena os objetos na base de dados
	 */
	public function store ()
	{
		$prepared = $this->prepare($this->data);
		
		if (empty($this->data['id']) or (!$this->load($this->id)))
		{
			if (empty($this->data['id'])) {
                $this->id = $this->getLast() +1;
              
            }
			 // cria uma instrução de insert
            $sql = "INSERT INTO {$this->getEntity()} " . 
                   '('. implode(', ', array_keys($prepared))   . ' )'.
                   ' values ' .
                   '('. implode(', ', array_values($prepared)) . ' )';


		}
		else
		{
			$sql = "UPDATE {$this->getEntity()} ";
			//monta os pares coluna valor
			if ($prepared)
			{

				foreach ($prepared as $column => $value)
				{
					if ($column !== 'id')
					{
						$set[] = "{$column} = {$value}";
					}
				}
			}
			$sql .= ' SET ' .   implode(', ',$set) ;
			$sql .= ' WHERE id=' . (int) $this->data['id'];
		}
		//obtem conexao ativa
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result;
        }
        else {
            throw new Exception('Não há transação ativa!!');
        }
	}

	/*
     * Recupera (retorna) um objeto da base de dados pelo seu ID
     * @param $id = ID do objeto
     */
    public function load ($id)
    {
    	$sql = "SELECT * FROM {$this->getEntity()}";
    	$sql .= ' WHERE id= ' . (int) $id;

    	//obtem a conexão ativa
    	if ($conn = Transaction::get())
    	{
    		Transaction::log($sql);
    	 	$result = $conn->query($sql);

    	 	if ($result)
    	 	{
    	 		$object = $result->fetchObject(get_class($this));
    	 	}
    	 	return $object;
    	}
    	else
    	{
    		throw new \Exception("Não existe conexão ativa");
    		
    	}
    }

    public static function all()
    {
    	$className = get_called_class();
    	$rep = new Repository($className);
    	return $rep->load(new Criteria);
    }

    /**
     * busca um objeto pelo id
     */
    public static function find ($id)
    {
    	$className = get_called_class();
    	$ar = new $className;
    	return $ar->load($id);
    } 

    /**
     * retorna todos os objetos
     */

    public function delete ($id = null)
    {
    	$id = $id ? $id : $this->id;
    	$sql = "DELETE FROM {$this->getEntity()}";
    	$sql .= ' WHERE id=' . (int) $id;

    	if ($conn = Transaction::get())
    	{
    		//faz o log e executa o sql
    		Transaction::log($sql);
    		$result = $conn->exec($sql);
    		return $result;
    	}
    	else
    	{
    		throw new \Exception("Não existe conexão ativa");
    	}
    }

    private function getLast()
    {
        if ($conn = Transaction::get()) {
            $sql  = "SELECT max(id) FROM {$this->getEntity()}";

            // cria log e executa instrução SQL
            Transaction::log($sql);
            $result= $conn->query($sql);

            // retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        }
        else {
            throw new Exception('Não há transação ativa!!');
        }
    }

	public function prepare($data)
	{
		$prepared = array();
		foreach ($data as $key => $value)
		{
			if (is_scalar($value))
			{
				$prepared[$key] = $this->escape($value);
			}
		}
		return $prepared;
	}

	public function escape ($value)
	{
		if (is_string($value) and (!empty($value)))
		{
			$value = addslashes($value);
			return "'$value'";
		}
		else if (is_bool($value))
		{
			return $value ? 'TRUE' : 'FALSE';
		}
		else if ($value !== '')
		{
			return $value;
		}
		else
		{
			return "NULL";
		}
	}


} 