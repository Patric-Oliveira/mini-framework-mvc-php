<?php

namespace App\Core;

use PDO;

/**
 * PDO PHP Persistence Class
 *
 * @author Patric Oliveira - patric.eng.computation@gmail.com
 */
class Model
{

    private static $connection;
    private $debug;
    private $server;
    private $user;
    private $password;
    private $database;

    public function __construct()
    {
        $this->debug = true;

        $this->server   =  DB_HOST;
        $this->user     =  DB_USER;
        $this->password =  DB_PASS;
        $this->database =  DB_NAME;
    }

    /**
     * Crie uma conexão de banco de dados ou retorne a conexão já aberta usando Singleton Design Pattern
     * @return PDOConnection|null
     */
    public function getConnection()
    {
        try {
            if (self::$connection == null) {
                self::$connection = new PDO("mysql:host={$this->server};dbname={$this->database};charset=utf8", $this->user, $this->password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                self::$connection->setAttribute(PDO::ATTR_PERSISTENT, true);
            }
            return self::$connection;
        } catch (\PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on getConnection(): </b>" . $ex->getMessage() . "<br/>";
            }
            return null;
        }
    }

    /**
     * Desativar conexão
     * @return void
     */
    public function Disconnect()
    {
        self::$connection = null;
    }

    /**
     * Retorna o último id da instrução insert
     * @return int
     */
    public function getLastID()
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Iniciar uma transação de banco de dados
     * @return void
     */
    public function beginTransaction()
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * Confirmar alterações na transação aberta
     * @return void
     */
    public function commit()
    {
        return $this->getConnection()->commit();
    }

    /**
     * Reverter alterações na transação aberta
     * @return void
     */
    public function rollback()
    {
        return $this->getConnection()->rollBack();
    }

    /**
     * retorna o resultado de uma consulta (selecionar) de apenas uma linha
     * @param string $sql a string sql
     * @param array $params o array de parâmetros (array(":col1" => "val1",":col2" => "val2"))
     * @return mixed array de posição para o resultado da consulta
     */
    public function executeQueryOneRow($sql, $params = null)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteQueryOneRow():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }

            return null;
        }
    }

    /**
     * retorna o resultado de uma consulta (selecionar)
     * @param string $sql a string sql
     * @param array $params o array de parâmetros (array(":col1" => "val1",":col2" => "val2"))
     * @return array para o resultado da consulta
     */
    public function executeQuery($sql, $params = null)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteQuery():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }

            return null;
        }
    }

    /**
     * retorna se a consulta foi bem sucedida
     * @param string $sql a string sql
     * @param array $params o array de parâmetros (array(":col1" => "val1",":col2" => "val2"))
     * @return bool
     */
    public function executeNonQuery($sql, $params = null)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteNonQuery():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }

            return false;
        }
    }

    /**
     * retorna o número de linhas afetadas
     * @param string $sql a string sql
     * @param array $params o array de parâmetros (array(":col1" => "val1",":col2" => "val2"))
     * @return int
     */
    public function numberRows($sql, $params = null)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount();
        } catch (\PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteNonQuery():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
            }
            die();
            return -1;
        }
    }

    public function getDebugState()
    {
        return $this->debug;
    }
}
