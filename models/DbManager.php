<?php

const HOST = '127.0.0.1';
const PORT = '3306';
const DBNAME = 'db_etudiants';
const CHARSET = 'utf8';
const LOGIN = 'root';
const PASSWORD = '';
class DbManager
{



    private static ?\PDO $cnx = null;

    public static function connect()
    {
        if (self::$cnx == null) {
            try {
                $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DBNAME . ';charset=' . CHARSET;
                self::$cnx = new PDO($dsn, LOGIN, PASSWORD);
                self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $ex) {
                die("Erreur : " . $ex->getMessage());
            }
        }
        return self::$cnx;
    }

}