<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Signup.class.php");
${basename(__FILE__, '.php')} = function () {
    try {
        if ($this->get_request_method() == "POST") {
            if (isset($this->_request['pass']) and isset($this->_request['username']) and isset($this->_request['email'])) {
                $email = $this->_request['email'];
                $pass = $this->_request['pass'];
                $username = $this->_request['username'];
                $s = new Signup($username, $pass, $email);
                $data = [
                    "Message" => "User Created Successfully",
                    "Insert ID" => $s->getInsertID(),
                ];
                $data = $this->json($data);
                $this->response($data, 200);
            } else {
                $data = [
                    "Error" => "Not Acceptable",
                ];
                $data = $this->json($data);
                $this->response($data, 406);
            }
        } else {
            $data = [
                "Error" => "Bad Request",
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
    } catch (\Exception $e) {
        $data = [
            "msg" => $e->getMessage(),
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
