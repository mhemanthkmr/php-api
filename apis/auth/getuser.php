<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once("/var/www/api/api/lib/User.Class.php");
${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() == "POST") {
        if (isset($_POST['username'])) {
            $u = new User();
            $username = $_POST['username'];
            $result = $u->getUserData($username);
            $data = $this->json($result);
            $this->response($data, 200);
        } else {
            $data = [
                "Error" => "Bad Request",
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
    }
};
