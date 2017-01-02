<?php
$db_conf=require "db/database_config.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    throw new Exception("Error connection with DB");
}
$login = $_POST['login'];
$login=htmlentities($login);
$login=htmlspecialchars($login);
$login=stripslashes($login);
$query = $mysqli->query("SELECT * FROM users WHERE user_login='".$login."'");
if($query->num_rows >0) $valid="false";
else $valid='true';
echo $valid;
