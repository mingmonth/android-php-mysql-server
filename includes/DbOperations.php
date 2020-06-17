<?php

    class DbOperations {
        
        private $con;

        function __construct() {

            require_once dirname(__FILE__).'/DbConnect.php';

            $db = new DbConnect();

            $this->con = $db->connect();

        }

        /* CRUD -> C -> CREATE */
        public function createUser($username, $pass, $email, &$errmsg) {
            if($this->isUserExist($username, $email)) {
                $errmsg = "It seems you are already registered, please choose a different email and username";
                return false;
            } else {
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

        public function userLogin($username, $pass) {
            $password = md5($pass);            
            $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

        public function getUserByUsername($username) {
            $stmt = $this->con->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        private function isUserExist($username, $email) {
            $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();  // mysqli 버퍼에 담긴 값을 php 변수에 복사한다.
            return $stmt->num_rows > 0;
        }
    }