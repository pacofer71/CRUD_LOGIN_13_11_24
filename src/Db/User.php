<?php
namespace App\Db;

use App\Utils\Datos;
use \PDO;
use \PDOException;

class User extends Conexion{
    private int $id;
    private string $username;
    private string $email;
    private string $pass;
    private string $perfil;

    public function create(): void{
        $q="insert into users(username, email, pass, perfil) values(:u, :e, :pass, :p)";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([
                ':u'=>$this->username,
                ':e'=>$this->email,
                ':p'=>$this->perfil,
                ':pass'=>$this->pass
            ]);
        }catch(PDOException $ex){
            throw new PDOException("Error en crear: ".$ex->getMessage());
        }finally{
            parent::cerrarConexion();
        }
    }
    
    public static function crearUsersRandom(int $cant){
        $faker = \Faker\Factory::create('es_ES');
        for($i=0; $i<$cant; $i++){
            $username=$faker->unique()->userName();
            $email=$username."@".$faker->freeEmailDomain();
            $pass='secret0';
            $perfil=$faker->randomElement(Datos::getPerfiles());
            
            (new User)
            ->setUsername($username)
            ->setEmail($email)
            ->setPass($pass)
            ->setPerfil($perfil)
            ->create();

        }
    }
    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of pass
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * Set the value of pass
     */
    public function setPass(string $pass): self
    {
        $password=password_hash($pass, PASSWORD_BCRYPT);
        $this->pass = $password;
        return $this;
    }

    /**
     * Get the value of perfil
     */
    public function getPerfil(): string
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     */
    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }
}