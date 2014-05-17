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
if (!isset($_SESSION['userid'])) {
    header("Location:index.html");
    exit();
}

include('connect.php');
$uid = $_SESSION['userid'];
$username = $_SESSION['username'];
$bot1id = $_POST['bot1'];
$bot2id = $_POST['bot2'];
$bot1_query = mysql_fetch_array(mysql_query("SELECT uid,botname FROM bot WHERE botid='$bot1id' LIMIT 1"));
$bot1name = $bot1_query['botname'];
$usr1id = $bot1_query['uid'];
$usr1name = mysql_fetch_array(mysql_query("SELECT (username) FROM user WHERE uid='$usr1id' LIMIT 1"))['username'];
$bot2_query = mysql_fetch_array(mysql_query("SELECT uid,botname FROM bot WHERE botid='$bot2id' LIMIT 1"));
$bot2name = $bot2_query['botname'];
$usr2id = $bot2_query['uid'];
$usr2name = mysql_fetch_array(mysql_query("SELECT (username) FROM user WHERE uid='$usr2id' LIMIT 1"))['username'];

$arg1 = $usr1name . '/' . $bot1name . ' ';
$arg2 = $usr2name . '/' . $bot2name . ' ';

$log = exec('sudo upload/judge/pk2048.py upload/ ' . $arg1 . $arg2, $res,$code);
if ($code != 0) {
    echo $log;
    echo '<p><a href="board.php">Back</a></p>';
} else {
    echo 'Printed to <a href="watch.php?log=' . $log . '">' . $log . '</a>';
    mysql_query("INSERT INTO log(logfile,masteruid,bot1id,bot2id) VALUES ('$log','$uid','$bot1id','$bot2id')");
}
?>
</div>
</div>
</body>
</html>
