<?php
$db_name = "zingalala";
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Create db tables
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(64) UNIQUE,
    `email` VARCHAR(64) UNIQUE,
    `password` VARCHAR(256),
    `create_ts` INT,
    `is_email_verified` TINYINT(1) DEFAULT 0,
    `first_name` VARCHAR(64) DEFAULT 'John',
    `last_name` VARCHAR(64) DEFAULT 'Doe',
    `pfp_url` VARCHAR(64) UNIQUE
);
");
// Create session table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `session_key` VARCHAR(64) UNIQUE,
    `is_active` TINYINT(1),
    `create_time` INT,
    `ua` VARCHAR(256),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
");

// Create post table with userid as foreign key, postcontent, posteddon timestamp, parentid, and imageurl

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `post_content` VARCHAR(2048),
    `timestamp` INT,
    `parent_id` INT DEFAULT 0 ,
    `image_url` VARCHAR(2048),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);
");


mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS `likes` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT,
        `post_id` INT,
        `like_timestamp` INT,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
        FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)
    );
");

mysqli_query($conn,
    "
        CREATE TABLE IF NOT EXISTS `notifications` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT,
            `type` VARCHAR(64),
            `post_id` INT,
            `timestamp` INT,
            `content` VARCHAR(1024),
            `thumbnail_url` VARCHAR(2048),
            `extref` VARCHAR(1024),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
            FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)
            );
    "
);

mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS `events` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `type` VARCHAR(64),
        `user_id` INT,
        `timestamp` INT,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    );
");

mysqli_query($conn,"
    CREATE TABLE IF NOT EXISTS `relations`(
      `id` INT AUTO_INCREMENT PRIMARY KEY,
       `req_from` INT,
       `req_to` INT,
       `status` TINYINT(1) DEFAULT 0,
        `timestamp` INT,
        FOREIGN KEY (`req_from`) REFERENCES `users`(`id`),
        FOREIGN KEY (`req_to`) REFERENCES `users`(`id`)
    );
");