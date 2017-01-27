<?php
require "classes/userInputsValidation.php";
//generate random code
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}
$db_conf=require "db/database_config.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    throw new Exception("Error connection with DB");
}
$err=0;
if(isset($_POST['submit'])) {
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
        $err = "1";
    }
    if (strlen($password) < 3 or strlen($password) > 15) {
        $err = "1";
    }
  if (!$err) {
        $query = $mysqli->query("SELECT  user_id, user_password FROM users  WHERE user_login='".$login."'");
        $data = $query->fetch_assoc();
       if ($data['user_password'] === md5(md5($password))) {
             $hash = md5(generateCode(10));
             if(!$mysqli->query("UPDATE users SET user_hash='".$hash.".' WHERE user_id='".$data['user_id']."'")){
                 throw new Exception("Error with update info in DB");
             }
             setcookie("id", $data['user_id'], time()+60*60*24*30);
             setcookie("hash", $hash, time()+60*60*24*30);
             header("Location: main.php"); exit();
        } else {
           include "nosuccess.php";
           exit();
        }
    } else {
        include "nosuccess.php";
         exit();
     }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php require_once 'headerInc.php'?>
    <link rel="stylesheet" href="css/signin.css">
    <title>Sign in</title>
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
                            lettersonly: true
                        },
                        password: {
                            minlength: 3,
                            maxlength: 15,
                            required: true,
                            lettersonly: true
                        }
                    },
                    highlight: function (element) {
                        $(element).closest('.control-group').addClass('has-error');
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
    <form class="form-signin" method="post" action="" id="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
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
        <button name="submit" id="submit" value="" type="submit" class="btn btn-large btn-primary btn-block">Sign in</button>
    </form>
</div>
</body>
</html>




