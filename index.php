<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Signup.class.php");

class API extends REST
{

    public $data = "";


    public function __construct()
    {
        parent::__construct();                // Init parent contructor
    }


    /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
    public function processApi()
    {
        $func = strtolower(trim(str_replace("api/apis/", "", $_REQUEST['rquest'])));
        print($func);
        if ((int)method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 400);                // If the method not exist with in this class, response would be "Page not found".
    }

    /*************API SPACE START*******************/

    private function about()
    {
        if ($this->get_request_method() != "POST") {
            $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
            $error = $this->json($error);
            $this->response($error, 406);
        }
        $data = array('version' => isset($this->_request['version']) ? $this->_request['version'] : '1.1', 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
        $data = $this->json($data);
        $this->response($data, 200);
    }

    private function hello()
    {
        print_r($GLOBALS);
    }

    private function db()
    {
        print_r(Database::getConnection());
    }

    private function verify()
    {
        $user = $this->_request['user'];
        $password =  $this->_request['pass'];

        $flag = 0;
        if ($user == "admin") {
            if ($password == "adminpass123") {
                $flag = 1;
            }
        }

        if ($flag == 1) {
            $data = [
                "status" => "verified"
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        } else {
            $data = [
                "status" => "unauthorized"
            ];
            $data = $this->json($data);
            $this->response($data, 403);
        }
    }

    private function test()
    {
        $data = $this->json(getallheaders());
        $this->response($data, 200);
    }

    function generate_hash()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    private function signup()
    {
        if ($this->get_request_method() == "POST") {
            if (isset($this->_request['pass']) and isset($this->_request['username']) and isset($this->_request['email'])) {
                $email = $this->_request['email'];
                $pass = $this->_request['pass'];
                $username = $this->_request['username'];
                $s = new Signup($email, $pass, $username);
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
    }

    private function gen_Hash()
    {
        if (isset($this->_request['pass'])) {
            $s = new Signup("", $this->_request['pass'], "");
            $hash = $s->hashPassword();
            $data = [
                "hash" => $hash,
                "val" => $this->_request['pass'],
                "verify" => password_verify($this->_request['pass'], $hash)
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        }
    }


    /*************API SPACE END*********************/

    /*
            Encode array into JSON
        */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
}

// Initiiate Library

$api = new API;
$api->processApi();
