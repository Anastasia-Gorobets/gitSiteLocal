<?php
$db_conf=require "db/database_config.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
echo '<div class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>';
    if (!isset($_COOKIE['id']) and !isset($_COOKIE['hash']))echo '<a class="navbar-brand" href="index.php">Project name</a>';
        echo '</div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">';
if (!isset($_COOKIE['id']) and !isset($_COOKIE['hash'])) echo '<li class="active" id="one"><a href="index.php">Home</a></li>';?>
<?php
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $query = $mysqli->query("SELECT user_login FROM users WHERE user_id=". $_COOKIE['id']);
    $data = $query->fetch_assoc();
    echo '<li><a href="logout.php">Logout ('.$data['user_login'].')</a></li>';
    echo '<li class="active"><a href="main.php">Main</a></li>';
}
?>
<?php echo '</ul>
        </div>
    </div>
</div>';



