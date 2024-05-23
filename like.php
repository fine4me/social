<?php
include_once ('./utils.php');

$user_data = checkLogin();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];
        try {
            $liked_state_current = toggle_like($postId);
            if ($liked_state_current) {
                echo 'liked';
            } else {
                echo 'unliked';
            }
            http_response_code(200);
            exit();
        } catch (Exception $e) {
            echo $e->getMessage();
            http_response_code(500);
            die();
        }
    }
}

function toggle_like($postId)
{
    global $conn, $user_data;
    $userId = $user_data['user_id'];
    $hasliked = false;
    $postauthorstmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $postauthorstmt->bind_param("i", $postId);
    $postauthorstmt->execute();
    $result = $postauthorstmt->get_result();
    $post_author_id = $result->fetch_assoc()['user_id'];
    $postauthorstmt->close();
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $hasliked = true;
    }
    $stmt->close();
    if ($hasliked) {
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");

        $stmt_delete_notif = $conn->prepare("DELETE FROM notifications WHERE user_id = ? AND post_id = ? AND type = 'like'");
        $stmt_delete_notif->bind_param("ii", $post_author_id, $postId);
        $stmt_delete_notif->execute();
        $stmt_delete_notif->close();

        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $stmt->close();
        return false;
    } else {
        $liketime = time();
        $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id, like_timestamp) VALUES (?, ?, ?)");
        $txtc = "Your post has been liked by " . $user_data['username'];
        $type = "like";
        CreateNotification($post_author_id, $postId,$type, $txtc, $user_data['user_id']);
        $stmt->bind_param("iii", $userId, $postId, $liketime);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}

function countLikes ($postId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(post_id) AS total_likes FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['total_likes'];
}

function CreateNotification($user_id, $postId, $type,$cont, $extref){
    global $conn;
    $liketime = time();
    $txtc = $cont;
    $stmt_delete_notif = $conn->prepare("INSERT INTO notifications (user_id, post_id, type, timestamp, content, extref) VALUES (?, ?, ? , ?, ?,?)");
    $stmt_delete_notif->bind_param("iisisi", $user_id, $postId, $type, $liketime, $cont, $extref);
    $stmt_delete_notif->execute();
    $stmt_delete_notif->close();
}

function returnFriendInfo($user_id)
{
    global $conn;
    $status = 1;
    $stmt = $conn->prepare("
        SELECT * FROM relations WHERE (req_from = ? OR req_to = ?) AND status = ?;
    ");
    $stmt->bind_param("iii", $user_id, $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}
