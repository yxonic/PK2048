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
if (!isset($_POST['submit'])) {
    exit('ERROR: Invalid access.');
}

$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['repass'];
$email = $_POST['email'];

if(!preg_match('/^[\w\x80-\xff]{3,15}$/', $username)){
    exit('User name not valid! <a href="javascript:history.back(-1);">Return</a>.');
}

if(strlen($password) < 6){
    exit('Password is too short. <a href="javascript:history.back(-1);">Return</a>.');
}

if ($password !== $confirm) {
   exit('Password not correct.');
}

if(!preg_match('/\w+@(mail.)?ustc\.edu\.cn$/', $email)){
    exit('Only USTC student email address is allowed. <a href="javascript:history.back(-1);">Return</a>.');
}

include('connect.php');

$check_query = mysql_query("SELECT uid FROM user WHERE username='$username' LIMIT 1");

if (mysql_fetch_array($check_query)) {
    echo 'ERROR: ',$username,' already exists. <a href="javascript:history.back(-1);">Return</a>.';
    exit;
}

$password = MD5($password);
$regdate = time();

$sql = "INSERT INTO user(username, password, email, regdate) VALUES ('$username', '$password', '$email', $regdate)";
if (mysql_query($sql, $connect)) {
    exit('Ready! Log in <a href="index.html">here</a>.');
} else {
    echo 'ERROR: ', mysql_error(), '<br />';
    echo '<a href="javascript:history.back(-1);">Return</a>.';
}
?>
</div>
</div>
</body>
</html>
