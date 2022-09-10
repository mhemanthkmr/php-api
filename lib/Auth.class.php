<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");


class Auth
{
    private $db;
    private $isTokenAuth = false;

    public function __construct($username, $password)
    {
        $this->db = Database::getConnection();
        if ($password == null) {
            //token based auth
            $this->token = $username;
            $this->isTokenAuth == true;
            // we have to validate the token
        } else {
            //password based auth
        }

        if ($this->isTokenAuth) {
        }
        return;
    }
}
