<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <link rel="stylesheet" href="css/site.css">
    <title>Sign in</title>
    <style>
    </style>
</head>
<body>
<div class="container">
    <?php
    $db_conf=require "db/database_config.php";
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