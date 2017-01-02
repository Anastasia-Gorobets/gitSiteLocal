<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/site.css">
</head>
<body>
<div class="container">
    <?php
    include "header.php";
    ?>
    <div class="jumbotron">
        <h1>Welcome!</h1>
        <p>You are on the main page. This project is targeted for companies who want to easily manage information about their employees.</p>
        <a class="btn btn-default btn-lg" href="register.php" role="button">Registration</a>
        <a class="btn btn-default btn-lg" href="signin.php" role="button">Sign in</a>
    </div>
</div>
</body>
</html>