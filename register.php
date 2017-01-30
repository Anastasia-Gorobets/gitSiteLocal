<?php
$db_conf=require "db/database_config.php";
require "classes/userInputsValidation.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    throw new Exception("Error connection with DB");
}
$err=array();
if(isset($_POST['submit']))
{
    //data processing
    $login=userInputsValidation::dataProcessing($_POST['login']);
    $password=userInputsValidation::dataProcessing($_POST['password']);
    if(!preg_match("/^[a-zA-Z0-9]+$/",$login))
    {
        $err[] = "1";
    }
    if(strlen($login) < 3 or strlen($login) > 30)
    {
        $err[] = "1";
    }
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $err[] = "1";
    }
    if (strlen($password) < 3 or strlen($password) > 15) {
        $err[] = "1";
    }
    $query = $mysqli->query("SELECT * FROM users WHERE user_login='.$login.");
    if($query->num_rows >0){
        $err[]="1";
    }
    if(count($err) == 0)
    {
        $password = md5(md5(trim($password)));
        $mysqli->query("INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
        header("Location: signin.php"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'headerInc.php'?>
    <link rel="stylesheet" href="css/signin.css">
    <title>Sign up</title>
    <style>
        .error {
            color: #b94a48;
        }
        .navbar-header{
            margin-top: 0;
        }
    </style>
    <script>
        $(document).ready(function () {
            jQuery.validator.addMethod("lettersonly", function (value, element) {
                return this.optional(element) || /^[a-z0-9_-]+$/i.test(value);
            }, "Please use only a-z0-9_-");
            $('#form-signin').validate({
                rules: {
                    login: {
                        minlength: 3,
                        maxlength: 15,
                        required: true,
                        lettersonly: true,
                        remote: {
                            url: "check-username.php",
                            type: "post"
                        }
                    },
                    password: {
                        minlength: 3,
                        maxlength: 15,
                        required: true,
                        lettersonly: true
                    }
                },
                messages: {
                    login:{
                        remote: "This username is already taken! Try another."
                        }
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <?php
    include "header.php";
    ?>
    <form class="form-signin" method="post" id="form-signin">
        <h2 class="form-signin-heading">Please sign up</h2>
        <div class="control-group">
            <label class="control-label" for="login">Login:</label>
            <div class="controls">
                <input size="50" name="login" id="login" value="" type="text" class="form-control" placeholder="Login">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">Password:</label>
            <div class="controls">
                <input size="50" name="password" id="password" value="" type="password" class="form-control" placeholder="Password">
            </div>
        </div>
        <button name="submit" id="submit" value="" type="submit" class="btn btn-large btn-primary btn-block">Sign up</button>
    </form>
</div>
</body>
</html>







