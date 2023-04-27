<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Group.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/User.class.php");

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() == "POST" and isset($this->_request['email'])) {
        $email = $this->_request['email'];
        $db = Database::getConnection();
        $query = "SELECT * FROM `auth` WHERE `email` = '$email';";
        $result = mysqli_query($db, $query);
        try {
            $row = mysqli_fetch_assoc($result);
            $username = $row['username'];
            $user = new User($username);
            $user->sendOTP();
            $data = [
                "message" => "OTP email sent to $email",
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        } catch (Exception $e) {
            $data = [
                "error" => "No such user",
            ];
            $data = $this->json($data);
            $this->response($data, 404);
        }
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
