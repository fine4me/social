
<?php
include_once ('./utils.php');
include_once ('./handlenotification.php');
$userdata = checkLogin();
function returnHeader()
{
    // Start the PHP code block for interpolation
    global $userdata;
    ob_start();
    ?>
    <header>
        <div class="top_nav">
            <div class="left_nav">
                <span>Zingalala</span>
            </div>
            <div class="right_nav">
                <div class="notification">
                    <a href="./notification.php">
                        <span class="notification_information">
                            <i class="fa-regular fa-bell"></i>
                            <span id="notification_count"><?php echo getUnReadCount() ? 0 : '';?></span>
                        </span>
                    </a>
                </div>
                <div style="display: flex" class="profile_info">
                    <span class="profilepic profilepicmini"></span>
                    <span style="margin-left: 5px" class="profile_username">
                        @<?php echo $userdata['username']; ?>
                    </span>
                </div>
                <span>
                    <a href="./logout.php">Logout</a>
                </span>
            </div>
        </div>
    </header>
    <?php
    // End the PHP code block and return the output
    return ob_get_clean();
}

