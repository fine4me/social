<?php
include_once('./utils.php');
include_once('./post.php');
include_once ('./like.php');
include_once ('./header.php');

$userdata = checkLogin();
$postdata = listPosts();
date_default_timezone_set('Asia/Kathmandu');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zingalala</title>
    <link rel="stylesheet" href="./css/home.css">
</head>
<style>
    @keyframes fadeout {
        50%{
            top: 0;
        }
        100% {
            top: -10%;
            display: none;
        }
    }
</style>

<body>

    <?php
    echo returnHeader();
   echo flash();
    ?>
    <div class="newpostcontainer">
        <div class="create_post">
            <div class="left_post">
                <span class="profilepic"></span>
                <div class="name"><?php echo $userdata['first_name']; ?> <?php echo $userdata['last_name']; ?></div>
            </div>
            <div class="right_post">
                <span>New Post</span>
                <form action="post.php" method="POST">
                    <textarea rows="4" type="text" class="postform" name="postdata" placeholder="Describe yourself here..."></textarea><br>
                    <button class="create_post_button" name="postCreate" type="submit">Create Post</button>
                </form>
            </div>
        </div>
    </div>

    <?php

    while ($row = $postdata->fetch_assoc()) {
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
                . $row['first_name'] .' ' . $row['last_name'] .

            '
                </div>
                <div class="feed-postdate">
                    '.

            date('H:i l F', $row['timestamp'])
        .
            '
                </div>
            </div>
        </div>
        <div class="mid-feed">
            '.
            showlinks($row['post_content']).
            '
        </div>
        <div class="reaction-feed" like_post="'.$row['post_id'].'">
            <div like_post="'.$row['post_id'].'" class="like-react'
            . (hasLiked($userdata['user_id'], $row['post_id']) ? ' liked' : '').
            '" id="likepost-'. $row['post_id'] .'">
                '.
            '<span like_post="'.$row['post_id'].'" id="likeCount-' . $row['post_id'] . '">' .
            countLikes($row['post_id']) .
            '</span>'
            .' 
                <i like_post="'.$row['post_id'].'" class="fa-regular fa-heart like-reaction"></i>
                <span like_post="'.$row['post_id'].'">Like</span>
            </div>
            <div class="comments">
                9
                <i class="fa-regular fa-message"></i>
                Comments
            </div>
        </div>
    </div>
    ';
    } ?>



</body>
<style>
    .profilepic, .profilepicsmall {
        background-image: url("https://placehold.co/72x72.png");
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
    }
    .profilepic {
        background-image: url("https://placehold.co/32x32.png");
    }
    .liked{
        color: red;
    }
</style>
<script src="https://kit.fontawesome.com/b1c6e6c59e.js" crossorigin="anonymous"></script>
<script src="./js/handle_likes.js"></script>
<script src="./js/handle_notification.js"></script>
</html>