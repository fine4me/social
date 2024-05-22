<?php
session_start();
include_once ('./utils.php');
disableSessionById($_SESSION['skey']);
session_destroy();
header('Location: index.php');
