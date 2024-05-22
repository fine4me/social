<?php

include_once('./utils.php');
checkLogin('home.php', $unloggedin_noredirect=true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zingalala</title>
    <link rel="stylesheet" href="./css/style.css">
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
echo flash();
?>
    <div class="container">
        <div class="contain-logo">
            <div class="logoimg"></div>
        </div>
        <div class="form-container">
            <div class="login">
                <div class="login-heading">
                </div>
                <div class="login-form">
                </div>
                <div class="signup-form">    
                </div>
            </div>
        </div>
    </div>
</body>
<script src="./js/index.js"></script>
</html>