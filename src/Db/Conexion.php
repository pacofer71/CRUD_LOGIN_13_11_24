<?php
namespace App\Db;

use \PDO;
use \PDOException;

class Conexion{
    private static ?PDO $conexion=null;

    protected static function getConexion(): PDO{
        if(self::$conexion===null){
            self::setConexion();
        }
        return self::$conexion;
    }
    private static function setConexion(){
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();
        $usuario=$_ENV['USUARIO'];
        $pass=$_ENV['PASS'];
        $db=$_ENV['DB'];
        $host=$_ENV['HOST'];
        $port=$_ENV['PORT'];
    }

    protected static function cerrarConexion(){
        self::$conexion=null;
    }
}