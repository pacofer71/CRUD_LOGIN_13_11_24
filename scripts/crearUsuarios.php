<?php

use App\Db\User;

require __DIR__."/../vendor/autoload.php";
$cant=0;
do{
    $cant=(int) readline("Dame el numero de usuarios (5-50):");
    if($cant<5 || $cant>50){
        echo "\nError se esperaba un número entre 5 y 50!!!";
    }
}while($cant<5 || $cant>50);
User::crearUsersRandom($cant);
echo "\n Usuarios creados...";