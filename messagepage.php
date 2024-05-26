
<?php
include_once('./utils.php');
include_once('./post.php');
include_once ('./like.php');
include_once ('./header.php');

$userdata = checkLogin();

date_default_timezone_set('Asia/Kathmandu');
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/search.css">
    <link rel="stylesheet" href="./userProfile.php">
</head>
<body>
<?php
echo returnHeader();
?>
<div class="backbutton">
    <a href="./notification.php">
        <span><i class="fa-solid fa-backward"></i></span>
    </a>
</div>
<div class="search-friend-container">
<?php
    // loop through return of returnFriendInfo($userdata['user_id']); with $row as each item
$is_empty = true;
 foreach (returnFriendInfo($userdata['user_id']) as $row) {
     $is_empty = false;
     if (is_array($row)) {
         if ($userdata['user_id'] == $row['req_from']) {
             $friendId = $row['req_to'];
         } else {
             $friendId = $row['req_from'];
         }
         $friendInfo = getUserDetails($friendId);
         echo '
     <div class="message-container">
         <div class="profile">
             <span class="profile-pic">' . $friendInfo['pfp_url'] . '</span>
         </div>
         <div class="username">' . $friendInfo['first_name'] . '  ' . $friendInfo['last_name'] . '</div>
         <div class="message"><i class="fa-regular fa-message message-icon"></i></div>
     </div>';
     }
 }
 if ($is_empty) {
     echo '<div class="message-container its-empty">Start Adding Some Friends</div>';
 }
 ?>
</div>
</body>
<style>
    .message-container{
        margin-top: 1%;
        padding: 3%;
        width: 100vw;
        height: 10vh;
        background-color: lightgray;
        display: flex;
    }
    .profile{
        width: 20%;
        display: flex;
        justify-content: start;
        align-items: center;
    }
    .profile-pic{
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: gray;
    }
    .username{
        flex: 1;
        display: flex;
        justify-content: start;
        align-items: center;
        padding-left: 5%;
        font-size: 1.2rem;
    }
    .message{
        width: 20%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .message-icon{
        color: white;
        justify-content: center;
        align-items: center;
    }
    .backbutton{
        margin-top: 2%;
        margin-left: 3%;
    }
    .search-friend-container{
        width: 100vw;
        display: flex;
        flex-direction: column;
        gap: 2%;
    }
    .its-empty{
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
    }
</style>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
</html>