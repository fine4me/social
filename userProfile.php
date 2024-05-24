
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/userprofile.css"
</head>
<body>
<?php
include_once ('./utils.php');
include_once ('./header.php');
include_once ('./utils.php');
include_once ('./interactUser.php');
include_once ('./post.php');
if(!isset($_GET['id'])) {
     header('Location : index.php');
     exit();
 }
echo returnHeader();
$postsData = returnPostsFromUserID($_GET['id']);
$profileDetails = getUserDetails($_GET['id']);
$userdata = checkLogin();
?>
<div class="backbutton">
    <a href="./home.php">
        <span><i class="fa-solid fa-backward"></i></span>
    </a>
</div>
        <div class="profile-information">
            <span class="profile-pic-information">
                <span class="profile-3 ">  </span>
            </span>
            <div class="top-profile-container">
                <span><?php echo $profileDetails['first_name'] . $profileDetails['last_name'] ?></span>
            </div>
            <div class="no-of-friends"><span class="friends-number"><?php  echo returnNumberOfFriends($_GET['id']) ?> &nbsp;</span>Friends</div>
                <div class="user-profile-action">
                <div class="add-user">
                    <button class="adduser user-add-view" id="add-user-1">
                        <?php
                        if(relationStatus($userdata['user_id'], $_GET['id'])){
                            echo 'Friends';
                        }else{
                            echo 'Add Friend';
                        }
                        ?>
                    </button <?php if (relationStatus($userdata['user_id'], $_GET['id'])) {echo 'disabled'; } ?>>
                </div>
            </div>
        </div>
            <div class="feed-items">
                <?php
                foreach ($postsData as $post){
                    echo '
                     <div class="feed-items">
        <div class="top-feed">
            <div class="feed-left">
                <span class="profilepic profilepicsmall ">

                </span>
            </div>
            <div class="feed-right">
                <div class="feed-postername">
                    '
                        . ucfirst( $profileDetails['first_name']).' ' .ucfirst( $profileDetails['last_name']) .

                        '
                </div>
                <div class="feed-postdate">
                    '.

                        date('H:i l F', $post['timestamp'])
                        .
                        '
                </div>
            </div>
        </div>
                    ' .'
                    ' . '<div class="mid-feed">
            '.
                        showlinks($post['post_content']).
                        '
        </div> '.

                        '<div class="reaction-feed" like_post="'.$post['id'].'">
            <div like_post="'.$post['id'].'" class="like-react'
                        . (hasLiked($userdata['user_id'], $post['id']) ? ' liked' : '').
            '" id="likepost-'. $post['id'] .'">
                '.
            '<span like_post="'.$post['id'].'" id="likeCount-' . $post['id'] . '">' .
            countLikes($post['id']) .
            '</span>'
            .'
                <i like_post="'.$post['id'].'" class="fa-regular fa-heart like-reaction"></i>
                <span like_post="'.$post['id'].'">Like</span>
            </div>
            <div class="comments">
                    9
                <i class="fa-regular fa-message"></i>
                    Comments
            </div>
        </div>
                ';
                }
                ?>
        </div>
</body>
<style>
    .backbutton{
        margin-top: 1%;
        margin-left: 1%;
    }
</style>
<script src="./js/handle_likes.js"></script>
<script src="./js/handle_notification.js"></script>
<script src="./js/interactSearch.js"></script>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
</html>