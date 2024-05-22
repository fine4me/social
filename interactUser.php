<?php
include_once './utils.php';
global $userData;
$userData = checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the username and operation from the POST data
    $userid = (int)$_POST['username'] ?? '';
    $operation = $_POST['operation'] ?? '';
    if($operation == 'add') {
        if (createNewRelation($userData['user_id'], $userid)) {
            echo 'ok';
        } else {
            http_response_code(500);
            echo 'error';
        }
        exit();
    } else if($operation == 'unlike') {
        echo 'unliked';
    }
} else {
    echo "Invalid request method.";
}



function createNewRelation($user1, $user2)
{
    if ($user1 == $user2) {
        return true;
    }
    if ($user2 == null || $user1 == null) {
        return false;
    }
    global $conn;
    $check_if_relation_exists = "SELECT * from relations WHERE ((req_from = ? AND req_to = ?) OR (req_from = ? AND req_to = ?)) LIMIT 1";
    $stmt = $conn->prepare($check_if_relation_exists);
    $stmt->bind_param('ssss', $user1, $user2, $user2, $user1);
    $stmt->execute();
    $result = $stmt->get_result();
    $check_row = $result->fetch_assoc();
    $stmt->close();
    if ($result->num_rows > 0) {
        // Relation exists - If status is 0 turn it to 1 else ignore
        if ($check_row['status'] == 0) {
            $stmt = $conn->prepare("UPDATE relations SET status = 1 WHERE (('req_from' = ? AND 'req_to' = ?) OR ('req_from' = ? AND 'req_to' = ?))");
            $stmt->bind_param('ss', $user1, $user2);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    } else {
        // Create new relation
        $stmt = $conn->prepare("INSERT INTO relations (req_from, req_to,timestamp) VALUES ( ?, ?, ?)");
        $time = time();
        $stmt->bind_param('iii', $user1, $user2, $time);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}

function relationStatus($user1, $user2)
{
    global $conn;
    $check_if_relation_exists = "SELECT * from relations WHERE (('req_from' = ? AND 'req_to' = ?) OR ('req_from' = ? AND 'req_to' = ?)) LIMIT 1";
    $stmt = $conn->prepare($check_if_relation_exists);
    $stmt->bind_param('ssss', $user1, $user2, $user2, $user1);
    $stmt->execute();
    $result = $stmt->get_result();
    $check_row = $result->fetch_assoc();
    $stmt->close();
    if ($result->num_rows > 0) {
        // Return, [is_accepted, req_sent_by, req_sent_to]
        return [$check_row['accepted'], $check_row['req_from'], $check_row['req_to']];
    } else {
        return false;
    }
}