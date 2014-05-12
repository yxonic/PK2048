<?php

$connect = mysql_connect("localhost", "root", "qwe456&*(");

if (!$connect){
    die("ERROR: Unable to connect DB server! " . mysql_error());
}

mysql_select_db("pk2048", $connect);

?>
