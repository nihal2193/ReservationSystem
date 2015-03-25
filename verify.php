<?php
print_r($_POST);
$username=$_POST['username']; 
$password=$_POST['password'];
	if($username != "" && $password != "")
	{
		include('connectiondata.php');
		$link=pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD);
		if (!$link)
		{
			echo "Error".ERROR_ON_CONNECT_FAILED;
		}
		else
		{
			//echo "opened database";
		}
		$password=md5($password);
		$result = pg_query($link, "select * from users where u_name='".$username."' and passwd='".$password."'");
		$num=pg_num_rows($result);
		echo $num;
		if ($num == 1)
		{
			header("Location:homepage.php");
			session_start();
			$_SESSION['username']=$username;
			$_SESSION['auth']=1;
		}
		else
		{
			header("Location:index1.php");
		}
	}
	else
	{
		header("Location:index1.php");
	}	

?>