<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");

${basename(__FILE__, '.php')} = function () {
    try {
        if ($this->get_request_method() == "GET" and isset($this->_request['uid'])) {
            $db = Database::getConnection();
            $uid = $this->_request['uid'];
            $query = "SELECT * FROM `rfid` WHERE `uid` = '$uid';";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            if($row['uid'] == $uid){
                $data = [
                    "uid" => $row['uid'],
                    "name" => $row['name'],
                    "status" => $row['status'],
                    "timestamp" => $row['timestamp'],
                ];
                $data = $this->json($data);
                $this->response($data, 200);
            } else {
                $data = [
                    "Error" => "Not Found",
                ];
                $data = $this->json($data);
                $this->response($data, 404);
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
