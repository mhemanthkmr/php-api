<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Group.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/User.class.php");

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() == "POST" and isset($this->_request['otp']) and isset($this->_request['email']) and isset($this->_request['password'])) {
        $email = $this->_request['email'];
        $password = $this->_request['password'];
        $otp = $this->_request['otp'];
        $db = Database::getConnection();
        $query = "SELECT * FROM `auth` WHERE `email` = '$email';";
        $result = mysqli_query($db, $query);
        try {
            $row = mysqli_fetch_assoc($result);
            $username = $row['username'];
            $user = new User($username);
            if ($user->checkOTP($otp))
                if ($user->changePassword($password)) {
                    $data = [
                        "message" => "Password changed successfully",
                    ];
                    $data = $this->json($data);
                    $this->response($data, 200);
                } else {
                    $data = [
                        "error" => "Password change failed",
                    ];
                    $data = $this->json($data);
                    $this->response($data, 400);
                }
        } catch (Exception $e) {
            $data = [
                "error" => "Invalid OTP",
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
