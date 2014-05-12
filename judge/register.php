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
