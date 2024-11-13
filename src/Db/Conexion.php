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
        $dsn="mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
        $option=[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT=>true
        ];
        try{
            self::$conexion=new PDO($dsn, $usuario, $pass, $option);
        }catch(PDOException $ex){
            throw new PDOException("Error en la conexion: ".$ex->getMessage(), -1);
        }
    }

    protected static function cerrarConexion(){
        self::$conexion=null;
    }
}