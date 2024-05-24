<?php
include_once('./dbadapter.php');
function generateRandomString($length) {
    // Define the characters to be used in the random string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    // Initialize the random string
    $randomString = '';
    
    // Build the random string
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

function getUserAgent() {
    /**
     * Check if the User-Agent header is present and return it, or a default value if not present.
     *
     * @return string The User-Agent header or a default value.
     */
    // Check if the User-Agent header is present
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        return $_SERVER['HTTP_USER_AGENT'];
    } else {
        return 'Unknown/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.9999.99 Safari/537.36';
    }
}

function checkLogin($loggedinredirect="", $unloggedin_noredirect=false){
    global $conn;
    if (session_status() == PHP_SESSION_NONE) {
        // Session is not started, so you can start it
        session_start();
    }
    // verify session
    if (isset($_SESSION['skey'])){
        $stmt = $conn->prepare("SELECT * FROM sessions JOIN users ON sessions.user_id = users.id WHERE session_key = ?");
        $stmt->bind_param("s", $_SESSION['skey']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            if($row['is_active'] == 0){
                header("Location: index.php");
                exit();
            } else {
                if ($loggedinredirect == "") {
                    return $row;
                }
                header("Location: $loggedinredirect");
                exit();
            }
        }
    }else{
        if (!$unloggedin_noredirect) {
            header("Location: index.php");
            exit();
        }
    }
    // If login valid and loggedinredirect is not "" then set location header to it
}


function disableSessionById($sessionKey){
    global $conn;
    $stmt = $conn->prepare("UPDATE sessions SET is_active = 0 WHERE session_key = ?");
    $stmt->bind_param("s", $sessionKey);
    $stmt->execute();
    $stmt->close();
}

function setMessage($message,$errorlevel = 0)
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session is not started, so you can start it
        session_start();
    }
    $_SESSION['lasterror'] = (string)$errorlevel.":".$message;
}

function getLastError()
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session is not started, so you can start it
        session_start();
    }
    if (isset($_SESSION['lasterror'])){
        $message = $_SESSION['lasterror'];
        unset($_SESSION['lasterror']);
        return $message;
    }
    return false;
}

function flash()
{
    $lastError = getLastError();
    if ($lastError) {
        $errorParts = explode(':', $lastError, 2);
        $errorCritical = isset($errorParts[0]) ? $errorParts[0] : '';
        $errorMessage = isset($errorParts[1]) ? $errorParts[1] : 'Unknown error';
        if($errorCritical == 0){
            $color = "lawngreen";
        }elseif ($errorCritical == 1){
            $color = "orange";
        }else{
            $color = "red";
        }
        return '<div'. ' style="position: absolute; top: 0; left: 0; height: 5vh; background-color:'. $color .'; width: 100vw; display: flex; justify-content: center; align-items: center; border-radius: 10px; animation: fadeout 5s forwards;">' . htmlspecialchars($errorMessage) . '</div>';
    }
}

function returnNumberOfFriends($userID){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(id) as total FROM relations WHERE (req_from = ? OR req_to = ?) AND status = 1;");
    $stmt->bind_param("ii", $userID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['total'];
}


function returnPostsFromUserID($userID)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY timestamp DESC;");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}