<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>View logs</title>
    <link href="style/main.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="meta/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, target-densitydpi=160dpi, initial-scale=1.0, maximum-scale=1, user-scalable=no, minimal-ui">
  </head>
  <body>
<div class="container">
<div class="game-explanation">
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

    echo 'Welcome, ',  $username, 
      '! Click <a href="board.php">here</a> to get inside. ';
    exit;
} else {
    exit('Failed! Go <a href="javascript:history.back(-1);">Back</a>.');
}

?>
</div>
</div>
</body>
</html>
