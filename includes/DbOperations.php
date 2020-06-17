<?php

    class DbOperations {
        
        private $con;

        function __construct() {

            require_once dirname(__FILE__).'/DbConnect.php';

            $db = new DbConnect();

            $this->con = $db->connect();

        }

        /* CRUD -> C -> CREATE */
        function createUser($username, $pass, $email, &$errmsg) {
            $password = md5($pass);
            $stmt = $this->con->prepare("INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (NULL, ?, ?, ?);");
            $stmt->bind_param("sss", $username, $password, $email);

            if($stmt->execute()) {
                $errmsg = "User registered successfully";
                return true;
            } else {
                $errmsg = $stmt->error;
                return false;
            }
        }
    }