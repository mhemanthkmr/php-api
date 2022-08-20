<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/apis/lib/Database.class.php");
class Signup
{
    private $username;
    private $password;
    private $email;
    private $db;

    public function __construct($username,$password,$email)
    {
        $this->db = Database::getConnection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;

        $bytes = random_bytes(16);
        $token = bin2hex($bytes);

        $query = "INSERT INTO `Test`.`auth` (`id`, `username`, `password`, `email`, `token`, `active`) VALUES ('$username', '$password', '$email', '$token', '0');";
        if(!mysqli_query($this->db,$query))
        {
            print("signup success");
        }
        else
        {
            print("signup success");
        }
    }
}