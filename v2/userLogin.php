<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['email']) and isset($_POST['password'])) {
        $db = new DbOperations();

        if($db->userLoginByEmail($_POST['email'], $_POST['password'])) {
            $user = $db->getUserByEmail($_POST['email']);
            $response['error'] = false;
            $response['message'] = "Login Successful";
            $response['user']['id'] = $user['id'];  
            $response['user']['username'] = $user['username'];
            $response['user']['email'] = $user['email'];
        } else {
            $response['error'] = true;
            $response['message'] = "Invalid username and password";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
}

echo json_encode($response);