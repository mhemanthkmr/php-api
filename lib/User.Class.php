<?php


require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");

class User
{
    private $db;
    private $arr = array();
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getUsersList()
    {
        $query = "SELECT * FROM auth";
        $result = mysqli_query($this->db, $query);
        if (!$result) {
            throw new Exception("Something Wrong");
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($this->arr, $row);
            }
            return $this->arr;
        }
    }

    public function getUserData($username)
    {
        $query = "SELECT * FROM auth WHERE username = '$username';";
        // echo $query;
        $result = mysqli_query($this->db, $query);
        if (!$result) {
            throw new Exception("Database Problem");
        } else {
            return mysqli_fetch_assoc($result);
        }
    }
}
