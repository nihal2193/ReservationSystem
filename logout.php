<?php
session_start();
print_r($_SESSION);
//echo session_id();
session_unset();
session_destroy();
echo "<br/>Session destroyed";
echo "<a href='index1.php'>Home</a>";
?>