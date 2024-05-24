<?php
include_once ('./post.php');
include_once ('./utils.php');
include_once ('./header.php');
include_once ('./handlenotification.php');
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
?>
<div class="backbutton">
    <a href="./home.php">
        <span><i class="fa-solid fa-backward"></i></span>
    </a>
</div>
<div class="search-friend">
    <form action="./search.php" method="GET" id="search">
    </form>
    <input name="q" type="text" placeholder="Find Friend" class="find-friend" form="search">
    <button form="search" type="submit" class="find-friend-button"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
</div>
    <div class="notification-container">
        <?php
        // Process notifications...
        foreach ($notifications as $notification) {
            // Initialize the output string
            $output = '<div class="notification-for-like">';
            $output .= '<div class="like-profile">';
            $output .= '<span class="profile-pic"></span>';
            $output .= '</div>';

            if ($notification['type'] != 'request') {
                $output .= '<div class="like-information">';
                $output .= $notification['content'] . '<i class="fa-regular fa-heart always-red"></i>';
                $output .= '<span class="like-time">';
                $output .= $notification['created_time'];
                $output .= '</span>';
                $output .= '</div>';
            } else if ($notification['type'] == 'request') {
                $userDetail = getUserDetails($notification['extra']);
                $username = $userDetail['username'];

                $output .= '<div class="like-information">';
                $output .= $notification['content'] . '<i class="fa-solid fa-user-group always-red"></i>';
                $output .= '<span class="like-time">';
                $output .= $notification['created_time'];
                $output .= '</span>';
                $output .= '<div class="user-end" onclick="hide(this)">';
                $output .= '<div class="add-user">';
                $output .= '<button class="adduser user-add-view" id="add-user-' . $notification['extra'] . '">Accept</button>';
                $output .= '</div>';
                $output .= '<div class="view-profile">';
                $output .= '<button class="userprofile user-add-view" id="view-user-' . $username . '">Profile</button>';
                $output .= '</div>';
                $output .= '</div>'; // Close user-end div
                $output .= '</div>'; // Close like-information div
            }

            $output .= '</div>'; // Close notification-for-like div

            // Echo the output
            echo $output;
        }
        ?>
</body>
<script src="./js/interactSearch.js"></script>
<script src="./js/handle_notification.js"></script>
<script src="./js/handle_likes.js"></script>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
</html>

<script>
    function hide(e) {
        console.log(e);
        e.parentElement.parentElement.classList.add('hidden');
    }
</script>