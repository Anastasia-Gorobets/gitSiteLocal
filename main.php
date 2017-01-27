<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'headerInc.php'?>
    <title>Main</title>
    <style>
    </style>
</head>
<body>
<div class="container">
    <?php
    $db_conf = require "db/database_config.php";
    $mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
    {
        $query = $mysqli->query("SELECT *  FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
        $userdata = $query->fetch_assoc();
        if (($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id'])) {
            setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
            setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");
            header("Location: deny_access.php");
        }
        else
        {
            include "header.php";
            echo '
        <div class="jumbotron">
        <h1>Welcome!</h1>
        <p>You can select the desired function for you.</p>
        <a class="btn btn-default btn-lg" href="empl.php" role="button">Employees</a>
        <a class="btn btn-default btn-lg" href="admin.php" role="button">Admin page (only for admin)</a>
    </div>
        ';
        }
    }
    else
    {
        header("Location: deny_access.php");
    }?>
</div>
</body>
</html>