<?php

namespace Jauhar\System;


use PDO;
use PDOException;


class Database
{


    private $dbh;
    private $stmt;

    public function __construct()
    {

        $dsn = 'mysql:hosts=' . $_ENV['DB_HOST']  . ';dbname=' . $_ENV['DB_NAME'];
        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ];
        try {
            $this->dbh  = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $option);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($params, $val, $type = null)
    {
        if ($type == null) {
            switch (true) {
                case is_int($val):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($val):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($val):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($params, $val, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function result_array()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function row_array()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function affected_rows()
    {
        return $this->stmt->rowCount();
    }
}
