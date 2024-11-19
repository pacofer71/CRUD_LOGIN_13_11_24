<?php
namespace App\Utils;

use App\Db\User;

class Utilidades{
    public static function sanearCadena(string $cad): string{
        return htmlspecialchars(trim($cad));
    }

    public static function longitudCadenaValida(string $nom, string $valor, int $lMin, int $lMax): bool{
        if(strlen($valor)<$lMin || strlen($valor)>$lMax){
            $_SESSION["err_$nom"] ="*** Error el campo $nom debe tener entre $lMin y $lMax caracteres.";
            return false;
        }
        return true;
    }
    public static function emailValido(string $e):bool{
        if(!filter_var($e, FILTER_VALIDATE_EMAIL)){
            $_SESSION['err_email']="*** Error, se esperaba un email válido.";
            return false;
        }
        return true;
    }
    public static function perfilValido(string $perfil): bool{
        if(!in_array($perfil, Datos::getPerfiles())){
            $_SESSION['err_perfil']="*** Error perfil inválido o no selecciono ninguno.";
            return false;
        }
        return true;
    }

    public static function loginValido(string $email, string $password): bool{
        $datos=User::loginValido($email, $password);
        if(!is_array($datos)){
            $_SESSION['err_login']="*** Error, email o password incorrectos.";
            return false;
        }
        $_SESSION['login']=$datos;
        return true;
    }

    public static function isCampoDuplicado(string $nCampo, string $vCampo,int $id=null): bool{
        if(User::existeValor($nCampo, $vCampo, $id)){
            $_SESSION["err_$nCampo"]="*** Error, $vCampo YA está registrado.";
            return true;
        }
        return false;
    }


    public static function pintarError(string $nombre): void{
        if(isset($_SESSION[$nombre])){
            echo "<p class='mt-2 text-red-500 italic text-sm'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }
}