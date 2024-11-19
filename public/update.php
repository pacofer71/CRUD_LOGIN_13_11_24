<?php
    session_start();
    if(!isset($_SESSION['login'])){ //si no me he logeado nos vamos
        header("Location:login.php");
        die();
    }
    if(!isset($_GET['un'])){ //si no mando un usuario para editar nos vamos
        header("Location:login.php");
        die();
    }
    $un=$_GET['un'];
    $perfil=$_SESSION['login'][2];
    $username=$_SESSION['login'][0];
    
    if($perfil=='Normal' && $username!=$un){ //si el perfil es normal y no coincide el usuario logeado con el username nos vamos
        header("Location:login.php");
        die();
    }
    $meEstoyEditandoAMiMismo=($un==$username);
   
    require __DIR__ . "/../vendor/autoload.php";
    use App\Utils\Utilidades;
    use \App\Db\User;
use App\Utils\Datos;

    $usuario=User::read($un)[0];

    if (isset($_POST['email'])) {
        $username=Utilidades::sanearCadena($_POST['username']);
        $email = Utilidades::sanearCadena($_POST['email']);
        $pass = Utilidades::sanearCadena($_POST['pass']);
        if($perfil=='Admin'){
            $perfilNuevo=Utilidades::sanearCadena($_POST['perfil']);
        }
        
        $errores = false;
        
        if(!Utilidades::longitudCadenaValida('username', $username, 3, 20)){
            $errores=true;
        }else{
            if(Utilidades::isCampoDuplicado('username', $username, $usuario->id)){
                $erores=true;
            }
        }

        if ((strlen($pass)!=0) && !Utilidades::longitudCadenaValida('pass', $pass, 5, 12)) {
            $errores = true;
        }
        if (!Utilidades::emailValido($email)) {
            $errores = true;
        }else{
            if(Utilidades::isCampoDuplicado('email', $email, $usuario->id)){
                $errores=true;
            }
        }
        if($perfil=='Admin'){
            if(!Utilidades::perfilValido($perfilNuevo)){
                $errores=true;
            }
        }
        if($errores){
            //die("Hay errores");
            header("Location: update.php?un=$un");
            die();
        }
        //Vamos a actualizar el usuario
        $perfilActualizado=($perfil=='Admin') ? $perfilNuevo : 'Normal';
        if(strlen($pass)!=0){
            //queremos actualizar todos los campos password incluida
            User::update($usuario->id, $username, $email, $perfilActualizado, $pass);
        }else{
            //no queremos cambiar la contraseña
            User::update($usuario->id, $username, $email, $perfilActualizado);
        }
        if($meEstoyEditandoAMiMismo){
            $_SESSION['login'][0]=$username;
            $_SESSION['login'][1]=$email;
            $_SESSION['login'][2]=$perfilActualizado;
        }
       
        $_SESSION['mensaje']="Usuario editado.";
        header("Location:inicio.php");
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Editar Usuario (<?= $un ?>)
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="<?php echo $_SERVER['PHP_SELF']."?un=$un" ?>" method="POST">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your username</label>
                            <input type="text" name="username" value="<?=$usuario->username ?>"
                             id="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your username..." />
                            <?php
                            Utilidades::pintarError('err_username');
                            ?>
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input type="email" name="email" value="<?=$usuario->email ?>" 
                            id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tu email..." />
                            <?php
                            Utilidades::pintarError('err_email');
                            ?>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="pass" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <?php
                            Utilidades::pintarError('err_pass');
                            ?>
                        </div>
                        <?php
                            if($perfil=='Admin'){
                                $perfiles=Datos::getPerfiles();
                                echo "<div class='mb-4'>";
                                echo "<label for='perfil' class='block mb-2 text-sm font-medium text-gray-900 dark:text-white'>Elige un perfil</label>";
                                echo "<select name='perfil' class='bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>";
                                foreach($perfiles as $item){
                                    $cadena=($usuario->perfil==$item) ? "selected" : "";
                                    echo "<option $cadena>$item</option>";
                                }
                                echo "</select>";
                                echo "</div>";
                            }
                        ?>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-primary-800">
                            EDITAR
                        </button>
                        <a href="inicio.php"
                            class="block w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-primary-800">
                            HOME
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>