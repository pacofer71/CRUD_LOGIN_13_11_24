<?php

use App\Db\User;

require __DIR__ . "/../vendor/autoload.php";

session_start();
$email = false;
if (isset($_SESSION['login'])) {
  $username = $_SESSION['login'][0];
  $email = $_SESSION['login'][1];
  $perfil = $_SESSION['login'][2];
}
$usuarios = User::read();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
  <!-- CDN sweetalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- CDN tailwind css -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- CDN FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-4">


  <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Al-Andalus</span>
      </a>
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <?php
          if (!$email) {
            echo <<<TXT
        <li>
          <a href="register.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Register</a>
        </li>
        <li>
          <a href="login.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
        </li>
        TXT;
          } else {
            echo <<<TXT
            <li>
            <input type='text' value="$username" class="w-full italic p-2 rounded rounded-xl border-2 border-blue-500" readonly />
            </li>
            <li>
            <a href="update.php?un=$username" class="block p-2 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold"><i class='fas fa-edit'></i>Edit</a>
            </li>
            <li>
            <a href="logout.php" class="block p-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold">
            CERRAR SESSIÓN
            </a>
            </li>
        TXT;
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <h3 class="py-2 text-center text-xl">Listados de Usuarios</h3>


  <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            ID
          </th>
          <th scope="col" class="px-6 py-3">
            USERNAME
          </th>
          <th scope="col" class="px-6 py-3">
            EMAIL
          </th>
          <th scope="col" class="px-6 py-3">
            PERFIL
          </th>
          <?php
          if (isset($_SESSION['login']) && $perfil=='Admin') {
            echo <<<TXT
          <th scope="col" class="px-6 py-3">
            ACCIONES
          </th>
          TXT;
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($usuarios as $item) {
          $color = match (true) {
            $item->perfil == 'Admin' => 'text-red-500 font-bold',
            default  => 'text-blue-500 font-bold',
          };
          echo <<<TXT
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
          {$item->id}
          </th>
          <td class="px-6 py-4">
            {$item->username}
          </td>
          <td class="px-6 py-4">
            {$item->email}
          </td>
          <td class="px-6 py-4 $color">
            {$item->perfil}
          </td>
        TXT;
          if (isset($_SESSION['login']) && $perfil=='Admin') {
            echo <<<TXT
            <td class="px-6 py-4">
              <form method='POST' action='borrar.php'>
                <input type='hidden' name='id' value='{$item->id}' />
                <a href="update.php?un={$item->username}">
                <i class="fas fa-edit text-blue-500 hover:text-xl mr-2"></i>
                </a>
                <button type='submit' onclick="return confirm('¿Borrar Usuario?');">
                <i class='fas fa-trash text-red-600 hover:text-xl'></i>
                </button>
              </form>
          </td>
          TXT;
          }
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</body>

</html>