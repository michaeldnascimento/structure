<?php

namespace App\Core;

use \PDO as PDO;
use \PDOException as PDOException;

class DB
{

    private $driver;
    private $host;
    private $port;
    private $tns;
    private $dbname;
    private $user;
    private $pass;
    private $charset;

    public $conn;
    public $stmt;

    public function __construct(Array $data)
    {
        if (!is_null($data))
        {
            $this->driver = (isset($data['driver']) && !empty($data['driver'])) ? $data['driver'] : '';
            $this->host = (isset($data['host']) && !empty($data['host'])) ? $data['host'] : '';
            $this->port = (isset($data['port']) && !empty($data['port'])) ? $data['port'] : '';
            $this->tns = (isset($data['tns']) && !empty($data['tns'])) ? $data['tns'] : '';
            $this->dbname = (isset($data['dbname']) && !empty($data['dbname'])) ? $data['dbname'] : '';
            $this->user = (isset($data['user']) && !empty($data['user'])) ? $data['user'] : '';
            $this->pass = (isset($data['pass']) && !empty($data['pass'])) ? $data['pass'] : '';
            $this->charset = (isset($data['charset']) && !empty($data['charset'])) ? $data['charset'] : "utf8";

            $this->openConnection();
        }
    }

    public function openConnection()
    {
        try
        {
            switch ($this->driver)
            {
                case 'mysql':
                case 'dblib':
                    $dsn = "$this->driver:host=$this->host;dbname=$this->dbname;charset=$this->charset";

                    break;
                case 'oci':
                    $dsn = "$this->driver:dbname=$this->tns;charset=$this->charset";

                    break;
                case 'pgsql':
                    $dsn = "$this->driver:host=$this->host;port=$this->port;dbname=$this->dbname;";

                    break;

                default:
                    break;
            }

            $this->conn = new PDO($dsn, $this->user, $this->pass);

            if (!empty($this->tns))
            {
                $this->setQuery("alter session set NLS_DATE_FORMAT='DD/MM/YYYY HH24:MI:SS'");
                $this->execute();
            }

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e)
        {
            echo "<b>Mensagem de erro:</b> " . $e->getMessage() . '<br>';
            echo "<b>Nome do arquivo:</b> " . $e->getFile() . " <b>Linha:</b> " . $e->getLine() . '<br>';
        }
    }

    public function closeConnection()
    {
        $this->conn = null;
        $this->stmt = null;
    }

    public function setQuery($query)
    {
        $this->stmt = $this->conn->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type))
        {
            switch (true)
            {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultSet($tipo = PDO::FETCH_ASSOC)
    {
        $this->execute();
        return $this->stmt->fetchAll($tipo);
    }

    public function single($tipo = PDO::FETCH_ASSOC)
    {
        $this->execute();
        return $this->stmt->fetch($tipo);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->conn->commit();
    }

    public function cancelTransaction()
    {
        return $this->conn->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

}
