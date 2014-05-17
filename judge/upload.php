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

if(!isset($_SESSION['userid'])){
    header("Location:login.html");
    exit();
}

include('connect.php');
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];

$allowedExts = array("c", "cpp");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "text/x-csrc")
     || ($_FILES["file"]["type"] == "text/x-c++src")
     || ($_FILES["file"]["type"] == "text/csrc")
     || ($_FILES["file"]["type"] == "text/c++src")
     || ($_FILES["file"]["type"] == "text/plain"))
    && ($_FILES["file"]["size"] < 50000)
    && in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br><br>";
        
        $root_path = "upload/src/" . $username . "/";
        
        if (is_dir($root_path) != TRUE)
            mkdir($root_path);
        if (is_dir("upload/bin/" . $username) != TRUE)
            mkdir("upload/bin/" . $username);

        if (file_exists($root_path . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists!<br><br>";
            echo "Note: We won't delete old files in order not to lose your data, so it is recommended that you name your program properly so that they wouldn't be a mess.";
            echo '<br><br><p><a href="board.php">Back</a></p>';
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                               $root_path . $_FILES["file"]["name"]);
            echo "Stored in: " . "src/" . $_FILES["file"]["name"];
            echo '<p><a href="board.php">Back</a></p>';
            $botname = $_FILES["file"]["name"];
            $pub = 0;
            if ($_POST['public']) {
                $pub = 1;
            }           
            $regtime = time();
            $sql = "INSERT INTO bot(uid,botname,public,regtime) VALUES ('$userid','$botname','$pub','$regtime')";
            if(!mysql_query($sql, $connect)) {
                echo 'Sorry',mysql_error(),'<br />';
                echo '<a href="javascript:history.back(-1);">Retry</a>';
            }
        }
    }
}
else
{
    echo "Invalid file";
}
?> 
</div>
</div>
</body>
</html>
