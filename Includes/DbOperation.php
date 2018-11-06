<?php

/**
 * Created by Angelo Biaggi
 * Date: 04/18/18
 * This file will handle all the database operations
 */
class DbOperation
{
    private $conn;
 
    //Constructor
    function __construct()
    {
        require_once dirname(__FILE__) . '/Constant.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    //Function to create a new user
    public function createUser($username, $pass, $email, $name)
    {
        if (!$this->isUserExist($username, $email)) {
            $password = md5($pass);
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $email, $name);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_NOT_CREATED;
            }
        } else {
            return USER_ALREADY_EXISTS;
        }
    }
 
 
    private function isUserExist($username, $email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}