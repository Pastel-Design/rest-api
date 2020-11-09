<?php

namespace models;

use PDO;
use PDOException as PDOException;

/**
 * Manager DbManager
 *
 * @package models
 */
class DbManager
{
    /**
     * @var PDO $connection
     */
    public static PDO $connection;

    /**
     * @var array $settings
     */
    private static array $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     */
    public static function connect($host, $user, $password, $database)
    {
        if (!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$settings
            );
        }
    }

    /**
     * Request single row from databse
     *
     * @param string $sql
     * @param array  $params
     *
     * @return array|void
     */
    public static function requestSingle(string $sql, $params = array())
    {
        $result = self::$connection->prepare($sql);
        $result->execute($params);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Requests single row from database, fetches data by class
     *
     * @param string $sql
     * @param string $class
     * @param array  $params
     *
     * @return object|void
     */
    public static function requestSingleClass(string $sql, string $class, $params = array())
    {
        $class = "\\\classes\\" . $class . "Class";
        $result = self::$connection->prepare($sql);
        $result->execute($params);
        $result->setFetchMode(PDO::FETCH_CLASS, $class);
        return $result->fetch();
    }

    /**
     * Requests single row from database with indexes
     *
     * @param string $sql
     * @param array  $params
     *
     * @return array|void
     */
    public static function requestSingleWOAssoc(string $sql, $params = array())
    {
        $result = self::$connection->prepare($sql);
        $result->execute($params);
        return $result->fetch();
    }

    /**
     * Requests multiple rows from database
     *
     * @param string $sql
     * @param array  $params
     *
     * @return array|void
     */
    public static function requestMultiple(string $sql, $params = array())
    {
        $result = self::$connection->prepare($sql);
        $result->execute($params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns single first cell from database
     *
     * @param string $sql
     * @param array  $params
     *
     * @return mixed|void
     */
    public static function requestUnit(string $sql, $params = array())
    {
        $result = self::requestSingleWOAssoc($sql, $params);
        return ($result == null ? null : $result[0]);
    }

    /**
     * Requests insertion in database, returns either the request came trough or not
     *
     * @param string $sql
     * @param array  $params
     *
     * @return boolean
     */
    public static function requestInsert(string $sql, $params = array())
    {
        $result = self::$connection->prepare($sql);
        return $result->execute($params);
    }

    /**
     * Returns count of rows affected  by request
     *
     * @param string $sql
     * @param array  $params
     *
     * @return int|false
     * @throws PDOException
     */
    public static function requestAffect(string $sql, $params = array())
    {
        $result = self::$connection->prepare($sql);
        $result->execute($params);
        return $result->rowCount();
    }
}
