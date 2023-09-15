<?php

class DbManager
{

    const HOST = '127.0.0.1';
    const PORT = '3306';
    const DBNAME = 'db_etudiants';
    const CHARSET = 'utf8';
    const LOGIN = 'root';
    const PASSWORD = '';

    private static $cnx = null;
    private static $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DBNAME . ';charset=' . CHARSET;

    public static function connect()
    {
        if (self::$cnx == null)
            try {
                self::$cnx = new PDO(self::$dsn, self::LOGIN, self::PASSWORD);
            } catch (Exception $ex) {
                die("Erreur : " . $ex->getMessage());
            }
    }

}