<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/User.Class.php");

${basename(__FILE__, '.php')} = function () {
    try {
        if ($this->get_request_method() == "POST") {
            $user = new User();
            $result = $user->getUsersList();
            $data = $this->json($result);
            // $data = $this->json($data);
            $this->response($data, 200);
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
