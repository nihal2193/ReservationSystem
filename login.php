<?php
$host = "host=127.0.0.1";
$port = "port=5432";
$dbname= "dbname=irctc";
 $credentials = "user=postgres password=12345";
$username=$_GET['username']; 
echo "<br/>";
$password=$_GET['password'];
echo $password;
echo "<br/>";
echo $username;
$password=md5($password);
//echo md5($password);
$link=pg_connect("$host  $port $dbname $credentials ");
$result = pg_query($link, "select * from users where u_name='".$username."' and passwd='".$password."'");
$num=pg_num_rows($result);
echo $num;
?>