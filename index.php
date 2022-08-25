<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/Rest.api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Signup.class.php");

class API extends REST
{

    public $data = "";

    private $current_call;
    private $name = "Hemanth";
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
        $func = strtolower(trim(str_replace("api/", "", $_REQUEST['rquest'])));
        // print($func);
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            if (isset($_GET['namespace'])) {
                $dir = $_SERVER['DOCUMENT_ROOT'] . '/api/apis/' . $_GET['namespace'];
                $file = $dir . '/' . $func . '.php';
                if (file_exists($file)) {
                    include $file;
                    $this->current_call = Closure::bind(${$func}, $this, get_class());
                    $this->response($this->$func(), 200);
                } else {
                    $this->response($this->json(['error' => 'method_not_found']), 404);
                }

                /** 
                 * Use the following snippet if you want to include multiple files
                 */
                // $methods = scandir($dir);
                // //var_dump($methods);
                // foreach($methods as $m){
                //     if($m == "." or $m == ".."){
                //         continue;
                //     }
                //     $basem = basename($m, '.php');
                //     //echo "Trying to call $basem() for $func()\n";
                //     if($basem == $func){
                //         include $dir."/".$m;
                //         $this->current_call = Closure::bind(${$basem}, $this, get_class());
                //         $this->$basem();
                //     }
                // }
            } else {
                //we can even process functions without namespace here.
                $this->response($this->json(['error' => 'method_not_found']), 404);
            }
        }
    }

    public function __call($method, $args)
    {
        if (is_callable($this->current_call)) {
            return call_user_func_array($this->current_call, $args);
        } else {
            $this->response($this->json(['error' => 'methood_not_callable']), 404);
        }
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
        print_r($_REQUEST);
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

    // private function userexist()
    // {
    //     $username = $this->_request['username'];
    //     $email = $this->_request['email'];
    //     $pass = $this->_request['pass'];
    //     $s = new Signup($email, $pass, $username);
    //     $s->userExists();
    // }
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
