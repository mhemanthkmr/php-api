<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");

class Signup
{
    private $username;
    private $password;
    private $email;
    private $db;
    private $token;


    public function __construct($username, $password, $email)
    {
        $this->db = Database::getConnection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        if ($this->userExists()) {
            throw new Exception("User already exists");
        }
        if ($this->emailExists()) {
            throw new Exception("Email already exists");
        }
        $bytes = random_bytes(16);
        $this->token = $token = bin2hex($bytes); //to verify users over email.
        $password = $this->hashPassword();
        //Homework - make a proper flow to throw username already exists
        $query = "INSERT INTO `auth` (`username`, `password`, `email`, `active`, `token`) VALUES ('$username', '$password', '$email', 0, '$token');";
        if (!mysqli_query($this->db, $query)) {
            throw new Exception("Unable to signup, user account might already exist.");
        } else {

            $this->id = mysqli_insert_id($this->db);
            $this->sendVerificationMail();
            // // $f = new Folder();
            session_start();
            $_SESSION['username'] = $this->username;
            // $f->createNew('Default Folder');
            echo "SignUp Success";
        }
    }

    public function sendVerificationMail()
    {
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/env.json');
        $config = json_decode($config_json, true);
        $token = $this->token;
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['email_api_key']);
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
            'subject' => 'Verify your Account',
            'sender' => ['name' => "HemanthKumar M", 'email' => "noreply@mhemanthkmr.me"],
            // 'replyTo' => ['name' => '', 'email' => 'contact@sendinblue.com'],
            'to' => [['name' => $this->username, 'email' => $this->email]],
            'htmlContent' => '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
            'params' => ['bodyMessage' => $token]
        ]);

        try {
            $response = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
        // $email->setFrom("noreply@selfmade.ninja", "API Course by Selfmade");
        // $email->setSubject("Verify your account");
        // $email->addTo($this->email, $this->username);
        // $email->addContent("text/plain", "Please verify your account at: https://api1.selfmade.ninja/verify?token=$token");
        // $email->addContent(
        //     "text/html",
        //     "<strong>Please verify your account by <a href=\"https://api1.selfmade.ninja/verify?token=$token\">clicking here</a> or open this URL manually: <a href=\"https://api1.selfmade.ninja/verify?token=$token\">https://api1.selfmade.ninja/verify?token=$token</a></strong>"
        // );
        // $sendgrid = new \SendGrid($config['email_api_key']);
        // try {
        //     $response = $sendgrid->send($email);
        //     // print $response->statusCode() . "\n";
        //     // print_r($response->headers());
        //     // print $response->body() . "\n";
        // } catch (Exception $e) {
        //     echo 'Caught exception: ' . $e->getMessage() . "\n";
        // }
    }


    public function userExists()
    {
        $query = "SELECT * FROM auth  WHERE username = '$this->username';";
        $query_run = mysqli_query($this->db, $query);
        if (mysqli_num_rows($query_run) > 0) {
            // echo $query_run;
            return true;
        } else {
            // echo $query_run;
            return false;
        }
    }

    public function emailExists()
    {
        $query = "SELECT * FROM auth  WHERE email = '$this->email';";
        $query_run = mysqli_query($this->db, $query);
        if (mysqli_num_rows($query_run) > 0) {
            // echo $query_run;
            return true;
        } else {
            // echo $query_run;
            return false;
        }
    }

    public function getInsertID()
    {
        return $this->id;
    }

    public function hashPassword()
    {

        $options = [
            'cost' => 12,
        ];
        return password_hash($this->password, PASSWORD_BCRYPT, $options);
    }
}

// $s = new Signup("mhemanthkmr", "12345678", "mhemanthkmrcse@gmail.com");
