<?php
include_once ('./post.php');
include_once ('./utils.php');
include_once ('./header.php');
include_once ('./handlenotification.php');
?>

<?php
    function searchUsers($query)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ?");
        // Assuming you want to search for partial matches, you can add '%' around the query parameter
        $searchTerm = "%{$query}%";
        $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications -Alerts</title>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/alert.css">
    <link rel="stylesheet" href="./css/search.css">
</head>
<body>

<?php
$notifications = getNotifications();
echo returnHeader();
$users = searchUsers($_GET['q']);
?>
<div class="backbutton">
    <a href="./notification.php">
        <span><i class="fa-solid fa-backward"></i></span>
    </a>
</div>
<div class="container_profile">
    <div class="search-result">Search Results found <span><?php echo count($users); ?></span> </div>
    <div class="results-con">
       <?php


            foreach ($users as $user){
                $html = "";
                $html .= '<div class="search-username" id="user-' . $user['id'] . '">';
                $html .= '    <div class="user-left">';
                $html .= '        <span class="profile-pic"></span>';
                $html .= '    </div>';
                $html .= '    <div class="user-mid">';
                $html .= '        <div class="user-first-last-name">';
                $html .=                ucfirst( $user['first_name']) . ' ' . ucfirst($user['last_name']);
                $html .= '        </div>';
                $html .= '        <div class="username-info">';
                $html .=               '@' . $user['username'];
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '    <div class="user-end">';
                $html .= '        <div class="add-user">';
                $html .= '            <button class="adduser user-add-view" id="' .'add-user-'. $user['username'] . '">Add</button>';
                $html .= '        </div>';
                $html .= '        <div class="view-profile">';
                $html .= '            <button class="userprofile user-add-view" id="' .'view-user-'. $user['username'] . ' ">Profile</button>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
                echo $html;
            }

       ?>
    </div>
</div>
</body>
</html>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
<script src="./js/handle_likes.js"></script>
<script src="./js/handle_notification.js"></script>
<script src="./js/interactSearch.js"></script>
<?php

