<?php
//print_r($_POST);
	if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['contactnum']) && isset($_POST['address']) && isset($_POST['gender']) && isset($_POST['dob']) && isset($_POST['hint']))
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$contactnum=$_POST['contactnum'];
		$address=$_POST['address'];
		$gender=$_POST['gender'];
		$dob=$_POST['dob'];
		$hint=$_POST['hint'];
		include('connectiondata.php');
		$link=pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD);
		if (!$link)
		{
			echo "Error".ERROR_ON_CONNECT_FAILED;
		}
		else
		{
			echo "opened database";
		}
		$password=md5($password);
		$sql="INSERT INTO users(u_name, passwd, email, contact, address, gender, dob, hint) VALUES ('".$username."', '".$password."', '".$email."', '".$contactnum."', '".$address."', '".$gender."', '".$dob."', '".$hint."')";
		$result = pg_query($sql);
		if($result)
		{
			$msg="success";
			header("Location:index1.php?message=$msg");
		}
		else
		{
			$msg="fail";
			header("Location:index1.php?message=$msg");
		}

	}


?>