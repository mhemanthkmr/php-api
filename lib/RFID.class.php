<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/lib/User.class.php");

class RFID
{
    private $db;
    private $uid;
    private $name;
    private $status;
    private $timestamp;

    public function __construct($uid)
    {
        $this->db = Database::getConnection();
        $this->uid = $uid;
        $this->name = $name;
        $this->status = $status;
        $this->timestamp = $timestamp;
        
    }
}