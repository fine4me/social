<?php
include_once ('./utils.php');
global $conn;
$user_data = checkLogin();

// Get timestamp for event type 'last_opened_notif' from events table

$last_opened_timestamp = getEventTimestamp();
function getNotifications() :array
{
    global $conn, $user_data, $last_opened_timestamp;

    $stmt = $conn->prepare("
        SELECT * FROM notifications WHERE user_id = ? ORDER BY timestamp DESC LIMIT 20;
    ");

    $stmt->bind_param("i", $user_data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $notifications = array(); // Initialize notifications array

    foreach ($rows as $row) {
        // Populate profile picture URL
        $profile_pic = $row['thumbnail_url'] ?? ''; // Use ?? to handle cases where pfp_url is not set

        // Construct notification array
        $notification = array(
            'content' => $row['content'],
            'image' => $profile_pic,
            'created_time' => getTimeAgo($row['timestamp']),
            'type' => $row['type'],
            'extra' => $row['extref'],
            'is_seen' => $row['timestamp'] <= $last_opened_timestamp,
        );
        // Add notification to notifications array
        $notifications[] = $notification;
    }
    // check if type 'last_opened_notif' type with user id of current user exists in events table
    setEventTable($user_data);
    return $notifications; // Return notifications array
}


function getTimeAgo($timestamp) {
    date_default_timezone_set('Asia/Kathmandu');
    $current_time = time();
    $time_difference_seconds = $current_time - $timestamp;
    $minutes = floor($time_difference_seconds / 60);
    $hours = floor($minutes / 60);
    $days = floor($hours / 24);

    if ($days > 0) {
        return $days . " days ago";
    } elseif ($hours > 0) {
        return $hours . " hours ago";
    } elseif ($minutes > 0) {
        return $minutes . " minutes ago";
    } else {
        return "Just now";
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['checking_notif'])) {
        try {
            echo getUnReadCount();
            http_response_code(200);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            die();
        }
    }
}
function getUnReadCount()
{
    //make connection
    global $conn, $user_data, $last_opened_timestamp;
    $stmt = $conn->prepare("
        SELECT COUNT(id) as total FROM notifications WHERE user_id = ? AND timestamp > ?;
    ");
    $stmt->bind_param("ii", $user_data['user_id'], $last_opened_timestamp);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['total'];
}

function setEventTable($user_data) {
    global $conn;
    $query = "SELECT * FROM events WHERE type = 'last_opened_notif' AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $timestamp = time();
    if ($result->num_rows > 0) {
        $query = "UPDATE events SET timestamp = $timestamp WHERE type = 'last_opened_notif' AND user_id = ?";
        $query = $conn->prepare($query);
        $query->bind_param("i", $user_data['user_id']);
        $query->execute();
        $query->close();
    } else {
        $query = "INSERT INTO events (type, user_id, timestamp) VALUES ('last_opened_notif', ?, $timestamp)";
        $query = $conn->prepare($query);
        $query->bind_param("i", $user_data['user_id']);
        $query->execute();
        $query->close();
    }
}

function getEventTimestamp()
{
    global $conn, $user_data, $last_opened_timestamp;
    $query = "SELECT * FROM events WHERE type = 'last_opened_notif' AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $last_opened_timestamp = 0;
    if (isset($row['timestamp'])) {
        $last_opened_timestamp = $row['timestamp'];
    }
    $stmt->close();
    return $last_opened_timestamp;
}

function getUserDetails($userId){
    global $conn, $user_id;
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}