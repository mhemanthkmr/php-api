<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
class Signup
{
    private $username;
    private $password;
    private $email;
    private $db;
    private $token;


    public function __construct($username, $password, $email)
    {
        $this->db = Database::getConnection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        if ($this->userExists()) {

            throw new Exception("User already exists");
        }
        $bytes = random_bytes(16);
        $this->token = $token = bin2hex($bytes); //to verify users over email.
        $password = $this->hashPassword();
        //Homework - make a proper flow to throw username already exists
        $query = "INSERT INTO `auth` (`username`, `password`, `email`, `active`, `token`) VALUES ('$username', '$password', '$email', 0, '$token');";
        if (!mysqli_query($this->db, $query)) {
            throw new Exception("Unable to signup, user account might already exist.");
        } else {

            $this->id = mysqli_insert_id($this->db);
            // // $this->sendVerificationMail();
            // // $f = new Folder();
            // session_start();
            // $_SESSION['username'] = $this->username;
            // $f->createNew('Default Folder');
            echo "SignUp Success";
        }
    }

    public function userExists()
    {
        $query = "SELECT * FROM auth  WHERE username = '$this->username';";
        $query_run = mysqli_query($this->db, $query);
        if (mysqli_num_rows($query_run) > 0) {
            // echo $query_run;
            return true;
        } else {
            // echo $query_run;
            return false;
        }
    }

    public function getInsertID()
    {
        return $this->id;
    }

    public function hashPassword()
    {

        $options = [
            'cost' => 12,
        ];
        return password_hash($this->password, PASSWORD_BCRYPT, $options);
    }
}
