<?php
include_once ('./utils.php');
$userData = checkLogin();



function create_post($postdata, $userId, $time, $parentId, $image)
{
    if (isEmptyOrSpaces($postdata)) {
        setMessage('Post cannot be empty',1);
        return false;
    }
    global $conn;
    try {
        $statement = $conn->prepare("INSERT INTO posts (user_id, timestamp, parent_id, post_content, image_url) VALUES (?, ?, ?, ?, ?)");
        $statement->bind_param("iiiss", $userId, $time, $parentId, $postdata, $image);
        $statement->execute();
        $statement->close();
        return true;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['postCreate'])) {
        $postData = $_POST['postdata'];
        $userId = $userData['id'];
        $time = time();
        $parentId = 0;
        // TODO: $parentId should be hidden field in form so that the comments can be done cuz if you dont save it there the parent_id will always be 0
        $image = "";
        $success = create_post($postData, $userId, $time, $parentId, $image);
        //TODO : Add error handling with div animation
        if ($success) {
            setMessage('Post created successfully', 0);
        }
        header('Location: home.php');
    }
}
function listPosts()
{
    global $conn;
    $stmt = $conn->prepare("SELECT *, posts.id AS post_id FROM posts JOIN users ON posts.user_id = users.id ORDER BY timestamp DESC");
    $stmt->execute();
    return $stmt->get_result();
}

function isEmptyOrSpaces($str) {
    return empty(trim($str));
}

function  hasLiked($userId, $postId)
{
    global $conn, $userData;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function showlinks($postdata) {
    // Split the post data into words
    $properdata = preg_split('/(\s+)/', $postdata, -1, PREG_SPLIT_DELIM_CAPTURE);
    // Define the URL matching pattern
    $pattern = '/\b(?:[a-zA-Z]+:\/\/)?(?:www\.)?[a-zA-Z0-9-]+(?:\.[a-zA-Z]{2,})+(?:\/[^\s]*)?\b/';
    $finaldata = '';
    foreach ($properdata as $pd) {
        // Check if the word is a URL
        if (preg_match($pattern, $pd)) {
            // Extract the URL and ensure it has a proper scheme
            $url = preg_replace('/^[^a-zA-Z]+/', '', $pd);
            $url_parts = parse_url($url);
            if (!isset($url_parts['scheme'])) {
                $url = 'http://' . $url;
            }
            // Create the link and add it to the final data
            $finaldata .= ' <a class="feed-links" href="' . $url . '" target="_blank">' . substr($url, 0, 50) . '</a>';
        } else {
            // Add the non-URL word to the final data
            $finaldata .= ' ' . $pd;
        }
    }
    // Trim any leading whitespace
    return trim($finaldata);
}



