<?php

use App\Db\User;

session_start();
$id=filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if(!$id || $id<=0){
    header("Location:inicio.php");
    exit;
}
if(!isset($_SESSION['login']) || $_SESSION['login'][2]!='Admin'){
    header("Location:inicio.php");
    exit;
}

require __DIR__."/../vendor/autoload.php";
//si me estoy borrando a mi mismo quiero cerrarme la session
$usuario=User::getUserById($id)[0];
User::delete($id);
$_SESSION['mensaje']="Usuario Borrado";
if($usuario->username==$_SESSION['login'][0]){
    header("Location:logout.php");
    exit;
}
header("Location:inicio.php");