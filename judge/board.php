<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Game Board</title>
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
    <div class="container">
      <div class="heading">
        <h1 class="title">Game Board</h1>
      </div>
      <div class="game-explanation">
        <p> Welcome, <?php echo $username; ?>! Click here to <a href="login.php?action=logout">logout</a>.</p>
        <hr>
        <h1>Submit a bot!</h1>
        <form name="upload" action="upload.php" method="post"
              enctype="multipart/form-data">
          <input type="file" name="file" id="file"><br>
          <br>
          <p><input type="checkbox" name="public" value="1">upload as public bot</p>
          <input type="submit" name="submit" value="Submit">
        </form>
        <hr>
        <h1>Choose two bots to fight!</h1>
        <form name="match" method="post" action="match.php">
          <select name="bot1">
            <?php
               $own_bots = mysql_query("SELECT * FROM bot WHERE uid=$userid");
               $pub_bots = mysql_query("SELECT * FROM bot WHERE uid!=$userid AND public=1");
               
               while ($row = mysql_fetch_array($own_bots)) {
                   echo '<option value="', $row['botid'], '">', $row['botname'], "</option>";
               }
               while ($row = mysql_fetch_array($pub_bots)) {
                   $ureq = mysql_query("SELECT (username) FROM user WHERE uid=$row[uid] LIMIT 1");
                   $un = mysql_fetch_array($ureq)['username'];
                   echo '<option value="', $row['botid'], '">pub: ', $un, "-", $row['botname'], "</option>";
               }
               ?>
          </select>
          <select name="bot2">
            <?php 
               $own_bots = mysql_query("SELECT * FROM bot WHERE uid=$userid");
               $pub_bots = mysql_query("SELECT * FROM bot WHERE uid!=$userid AND public=1");
               
               while ($row = mysql_fetch_array($own_bots)) {
               echo '<option value="', $row['botid'], '">', $row['botname'], "</option>";
               }
               while ($row = mysql_fetch_array($pub_bots)) {
                   $ureq = mysql_query("SELECT (username) FROM user WHERE uid=$row[uid] LIMIT 1");
                   $un = mysql_fetch_array($ureq)['username'];
                   echo '<option value="', $row['botid'], '">pub: ', $un, "-", $row['botname'], "</option>";
               }
               ?>
          </select>
          <input type="submit" name="submit" value="Compete" action="compete.php"/>
          <a href="logs.php">View logs</a>
        </form>
      </div>
    </div>
  </body>
</html>
