<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/api/apis/lib/Database.class.php");
class Signup
{
    private $username;
    private $password;
    private $email;
    private $db;

    public function __construct($username, $password, $email)
    {
        $this->db = Database::getConnection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
}
