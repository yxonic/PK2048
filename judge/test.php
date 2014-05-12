        <?php 
           session_start();
echo "hello!";
           if (!isset($_SESSION['userid'])) {
              header("Location:login.html");
              exit();
           }
           
           include('connect.php');
           
           $userid = $_SESSION['userid'];
           $username = $_SESSION['username'];
           $own_bots = mysql_query("SELECT * FROM bot WHERE uid=$userid");
           $pub_bots = mysql_query("SELECT * FROM bot WHERE (NOT uid=$userid) AND public=true");
           
           while ($row = mysql_fetch_array($own_bots)) {
              echo '<option value="', $row['botid'], '">', $row['botname'], "</option>";
           }
           
         ?>
