<?php

class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'mydbpdo';
    private $dbtype = 'mysql';
    private $db_handler;
    private $error;
    private $statement;
    public function __construct()
    {
        $dsn = $this->dbtype.':host='.$this->host.';dbname='.$this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );
        try {
            $this->db_handler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
    public function prepare($query)
    {
        $this->statement = $this->db_handler->prepare($query);
    }
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
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
        $this->statement->bindValue($param, $value, $type);
    }
    public function execute()
    {
        $this->statement->execute();
    }
    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = '')
    {
        $query = "SELECT $fields FROM $table "
                 .($where ? " WHERE $where " : '')
                 .($limit ? " LIMIT $limit " : '')
                 .(($offset && $limit ? " OFFSET $offset " : ''))
                 .($order ? " ORDER BY $order " : '');
        $this->prepare($query);
    }
    public function insert($table, $data)
    {
        ksort($data);
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));
        $query = "INSERT INTO $table ($fieldNames) VALUES($fieldValues)";
        $this->prepare($query);
        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }
        $this->execute();
    }
    /**
     * Update data.
     */
    public function update($table, array $data, $where = '')
    {
        ksort($data);
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $query = "UPDATE $table SET $fieldDetails ".($where ? 'WHERE '.$where : '');
        $this->prepare($query);
        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }
        $this->execute();
    }
    /**
     * Delete Functionality.
     */
    public function delete($table, $where, $limit = 1)
    {
        $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
        $this->execute();
    }
    /**
     * Return data as an assoc array.
     */
    public function resultset()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Return single as an assoc array.
     */
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Return Objectset.
     */
    public function objectSet($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetchAll();
    }
    /**
     * Return single object.
     */
    public function singleObject($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetch();
    }
    /**
     * Return row count.
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    public function lastInsertId()
    {
        return $this->db_handler->lastInsertId();
    }
    public function beginTransaction($value = '')
    {
        return $this->db_handler->beginTransaction();
    }
    public function endTransaction()
    {
        return $this->db_handler->commit();
    }
    public function cancelTranscation()
    {
        return $this->db_handler->rollBack();
    }
    public function debugDumpParams()
    {
        return $this->statement->debugDumpParams();
    }
}