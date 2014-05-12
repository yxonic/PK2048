<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Logs</title>
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
        <h1 class="title">Logs</h1>
      </div>
      <div class="game-explanation">
        <hr>
        <?php
           $logs = mysql_query("SELECT * FROM log WHERE masteruid=$userid");
           echo '<table border="0" frame="hsides" rules="all">';
           while ($row = mysql_fetch_array($logs)) {
               $bot1id = $row['bot1id'];
               $bot2id = $row['bot2id'];
               $bot1 = mysql_fetch_array(mysql_query("SELECT uid,botname FROM bot WHERE botid='$bot1id' LIMIT 1"));
               $bot2 = mysql_fetch_array(mysql_query("SELECT uid,botname FROM bot WHERE botid='$bot2id' LIMIT 1"));
               $u1id = $bot1['uid'];
               $u2id = $bot2['uid'];
               $usr1 = mysql_fetch_array(mysql_query("SELECT username,email FROM user WHERE uid='$u1id' LIMIT 1"));
               $usr2 = mysql_fetch_array(mysql_query("SELECT username,email FROM user WHERE uid='$u2id' LIMIT 1"));
               echo '<tr>';
               if ($u1id !== $userid)
                   echo '<td> <a href="mailto://' . $usr1['email'] . '">' . $usr1['username'] . '</a> </td>';
               else
                   echo '<td> ' . $username . ' </td>';
               echo '<td> ' . $bot1['botname'] . ' </td>';
               if ($u2id !== $userid)
                   echo '<td> <a href="mailto://' . $usr1['email'] . '">' . $usr1['username'] . '</a> </td>';
               else
                   echo '<td> ' . $username . ' </td>';
               echo '<td> ' . $bot2['botname'] . ' </td>';
               echo '<td> <a href="watch.php?log=' . $row['logfile'] . '">' . $row['logfile'] . '</a> </td>';
               echo '</tr>';
           }
           echo '</table>';
         ?>
      </div>
    </div>
  </body>
</html>
