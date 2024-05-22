<?php
include_once './utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the username and operation from the POST data
    $username = $_POST['username'] ?? '';
    $operation = $_POST['operation'] ?? '';

    // For demonstration purposes, let's just echo the variables
    echo "Username: " . htmlspecialchars($username);
    echo "Operation: " . htmlspecialchars($operation) ;
    if($operation == 'add') {
        echo 'liked';
        //run function to add
    } else if($operation == 'unlike') {
        echo 'unliked';
    }
} else {
    echo "Invalid request method.";
}


