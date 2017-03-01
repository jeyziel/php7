<?php

namespace Livro\Database;
use Exception;

Final class Repository
{
    private $activeRecord; //clase manipulada pelo repositorio

    public function __construct($class)
    {
        $this->activeRecord = $class;
    }

    public function load(Criteria $criteria)
    {
        $sql = "SELECT * FROM " . constant($this->activeRecord . '::TABLENAME');

        if($criteria)
        {

            $expression = $criteria->dump();
            //obtem a clausula where
            if ($expression)
            {
                $sql .= ' WHERE ' . $expression;
            }
            //obtem as propriedades do criterio
            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            if ($order)
            {
                $sql .= ' ORDER BY ' . $order;
            }

            if ($limit)
            {
                $sql .= ' LIMIT ' . $limit;
            }

            if ($offset)
            {
                $sql .= ' OFFSET '  . $offset;
            }
        }
        //obtem conexao ativa
        if($conn = Transaction::get())
        {
            Transaction::log($sql); //registra mensagem de log

            //exucuta consulta no banco de dados
            $result = $conn->Query($sql);
            $results = array();

            if($result)
            {
                //return $result->fetchAll(PDO::FETCH_CLASS,$this->activeRecord);
                //percorre os resultado da consulta,retornando um objeto
                while($row = $result->fetchObject($this->activeRecord))
                {
                    //armazena os resultado no array
                    $results[] = $row;
                }
            }
            //echo $sql;
            //var_dump($results);
            return $results;
        }
        else
        {
            throw new Exception('NAO HÁ TRANSACAO ATIVA');
        }
    }


    public function delete(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');

        if($expression)
        {
            $sql .= ' WHERE ' . $expression;
        }
        //obtem transacao ativa
        if($conn  = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->exec($sql);//executa instrucao de DELETE
            return $result;
        }
        else
        {
            throw new Exception('na ha transacao ativa');
        }
    }

    /**
     * @param Criteria $criteria
     * @return object
     * @throws Exception
     */
    public function count(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "SELECT count(*) FROM " . constant($this->activeRecord . '::TABLENAME');
        if($expression)
        {
            $sql .= ' WHERE ' . $expression;
        }

        //obtem a conexao ativa
        if($conn = Transaction::get())
        {
            Transaction::log($sql);//regista mensagem de log
            $result = $conn->query($sql);
            if($result)
            {
                $row = $result->fetch();
            }
            return $row[0]; //retorna o resultado
        }
        else
        {
            throw new Exception('NAO EXISTE CONEXAO ATIVA');
        }
    }
}