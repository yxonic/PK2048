<?php
session_start();

if ($_GET['action'] == "logout") {
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    echo 'Go back to <a href="index.html">home</a>.';
    exit;
}

if (!isset($_POST['submit'])) {
    exit('ERROR: Invalid access.');
}

$username = htmlspecialchars($_POST['username']);
$password = MD5($_POST['password']);

include('connect.php');

$check_query = mysql_query("select uid from user where
 username='$username' and password='$password' limit 1");

if ($result = mysql_fetch_array($check_query)) {
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $result['uid'];

    echo $username,' Welcome! Click <a href="board.php">here</a> to get
    inside.<br />';

    echo '<a href="login.php?action=logout">Logout</a><br />';
    exit;
} else {
    exit('Failed! Go <a href="javascript:history.back(-1);">Back</a>.');
}

?>
