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
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        
        $root_path = "upload/src/" . $username . "/";
        
        if (is_dir($root_path) != TRUE)
            mkdir($root_path, 1777);
        if (is_dir("upload/bin/" . $username) != TRUE)
            mkdir("upload/bin/" . $username, 1777);

        if (file_exists($root_path . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                               $root_path . $_FILES["file"]["name"]);
            echo "Stored in: " . $root_path . $_FILES["file"]["name"];
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
