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
                // Handle each notification...
                echo '<div class="notification-for-like">
                            <div class="like-profile">
                                <span class="profile-pic"></span>
                            </div>';
                if ($notification['type'] == 'like') {
                    echo '     <div class="like-information">
                                <!-- generate the whole line -->
                                    ' . $notification['content'] . '<i class="fa-regular fa-heart always-red"></i>
                                    <span class="like-time">
                                    ' . $notification['created_time'] . '
                                    </span>
                                </div>
                        </div>';
                } else if ($notification['type'] == 'request') {
                    echo '     <div class="like-information">
                                    ' . $notification['content'] . '<i class="fa-solid fa-user-group always-red" ></i>
                                    <span class="like-time">
                                    ' . $notification['created_time'] . '
                                    </span>
                                </div>
                        </div>';
                }
            }
            ?>
        </div>
</body>
<script src="./js/handle_notification.js"></script>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
</html>
