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
    <?php 
       session_start();

       if (!isset($_SESSION['userid'])) {
       header("Location:index.html");
       exit();
       }
       
       include('connect.php');
       
       $userid = $_SESSION['userid'];
       $username = $_SESSION['username'];
       ?>
    <div class="container" style="width: 800px">
      <div class="heading">
        <h1 class="title">View logs</h1>
      </div>
      <div class="game-explanation" width="800px">
        <hr>
        <?php
           $log = $_REQUEST['log'];
           $filename = "upload/log/" . $log;
           $file = fopen($filename, "r");
           while (!feof($file))
               echo fgets($file) . "<br>";
           fclose($file);
         ?>
      </div>
    </div>
  </body>
</html>
