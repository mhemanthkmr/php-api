<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Auth.class.php");

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() == "POST") {
        if (isset($_POST['username'])) {
            $db = Database::getConnection();
            $username = $_POST['username'];
            $query = "DELETE FROM `session` WHERE `username`='$username'";
            if (mysqli_query($db, $query)) {
                $data = [
                    "message" => "Logged out successfully",
                ];
                $data = $this->json($data);
                $this->response($data, 200);
            } else {
                $data = [
                    "Error" => "Internal Server Error",
                ];
                $data = $this->json($data);
                $this->response($data, 500);
            }
        } else {
            $data = [
                "Error" => "Bad Request",
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
    }
};
