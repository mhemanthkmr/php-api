<?php
class User
{
    private $db;
    private $user;
    private $username;
    private $name;
    private $email;


    public function __construct($username)
    {
        $this->username = $username;
        $this->db = Database::getConnection();
        $query = "SELECT * FROM auth WHERE username='$this->username' OR email='$this->username'";
        //echo $query;
        $result = mysqli_query($this->db, $query);
        if (mysqli_num_rows($result) == 1) {
            $this->user = mysqli_fetch_assoc($result);
        } else {
            throw new Exception("User not found");
        }
    }

    public function getUsername()
    {
        return $this->user['username'];
    }

    public function getPasswordHash()
    {
        return $this->user['password'];
    }

    public function getName()
    {
        return $this->user['name'];
    }
    public function getEmail()
    {
        return $this->user['email'];
    }

    public function isActive()
    {
        return $this->user['active'];
    }

    public function sendOTP()
    {
        $otp = rand(100000, 999999);
        $query = "UPDATE `auth` SET `otp` = '$otp' WHERE `username` = '$this->username';";
        if (mysqli_query($this->db, $query)) {
            $this->name = $this->getName();
            $this->email = $this->getEmail();
            $this->sendOTPMail($otp, $this->email, $this->name);
        } else {
            throw new Exception("Unable to send OTP");
        }
    }

    public function changePassword($password)
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE `auth` SET `password` = '$password' WHERE `username` = '$this->username';";
        if (mysqli_query($this->db, $query)) {
            return true;
        } else {
            throw new Exception("Unable to change password");
        }
    }

    public function checkOTP($otp)
    {
        $query = "SELECT * FROM `auth` WHERE `username` = '$this->username' AND `otp` = '$otp';";
        $result = mysqli_query($this->db, $query);
        if (mysqli_num_rows($result) == 1) {
            return true;
        } else {
            throw new Exception("Invalid OTP");
        }
    }

    public function sendOTPMail($otp, $email, $name)
    {
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/env.json');
        $config = json_decode($config_json, true);
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['email_api_key']);
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
            'subject' => "OTP for password reset",
            'sender' => ['name' => "Ethic Electronics", 'email' => "admin@ethicelectronics.com"],
            'to' => [['name' => $name, 'email' => $email]],
            'htmlContent' => "<h2>You have OTP with the Ethic Electronics</h2>
        <h5>For</h5>
        <br/><br/>
        <h1>$otp</h1> ",
            // 'params' => ['bodyMessage' => $message]
        ]);
        try {
            $response = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
    }
}
