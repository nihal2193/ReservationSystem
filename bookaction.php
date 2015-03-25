<?php
session_start();

include('stations.php');
include('views.php');
//print_r($_POST);
$src=trim($_POST['src']);
$dst=trim($_POST['dst']);
$date=trim($_POST['date']);
$class=trim($_POST['class']);
$tnum=trim($_POST['tnum']);
$name1=trim($_POST['name1']);
$age1=trim($_POST['age1']);
$gender1=trim($_POST['gender1']);
$name2=trim($_POST['name2']);
$age2=trim($_POST['age2']);
$gender2=trim($_POST['gender2']);
$name3=trim($_POST['name3']);
$age3=trim($_POST['age3']);
$gender3=trim($_POST['gender3']);

$num_seats=0;

if($name1!="")
	$num_seats++;
$name=array();
$age=array();
$gender=array();
array_push($name, $name1);
array_push($age, $age1);
array_push($gender, $gender1);

if($name2!="")
	$num_seats++;
array_push($name, $name2);
array_push($age, $age2);
array_push($gender, $gender2);


if($name3!="")
	$num_seats++;
array_push($name, $name3);
array_push($age, $age3);
array_push($gender, $gender3);

// echo "arrays <br/>\n";
// print_r($name);
// print_r($age);
// print_r($gender);
// echo $num_seats."arrays <br/>\n";

	//echo "number of tickets or passengers ".$num_seats ."<br/>\n";
$uname='nihal2193';
//$class=get_cl($class);
$pnr=get_pnr();

$seat_nos=get_seat_nos($tnum,$src,$dst,get_cl($class),$date);  // create a function for seat nos
// echo "seat nos bookaction <br/>";
// print_r($seat_nos);

$trans_id=trans_id();
trigger_function_tu($uname,$trans_id,$pnr);
trigger_function_tt($tnum);
trigger_function_p($name,$age,$gender,$seat_nos,$num_seats);
trigger_function_tp($pnr);


//get vacant seats
//get_vacant_seats('12225','CNB',get_cl(17),'4/25/2014');


$current_date=date("m/d/Y");


// echo  $current_date."<br/>\n";

//trigger_create();

// trigger_tickets_user($trigger)

$query ="insert into tickets values ('".$pnr."', '".$current_date."' ,'".$date."', '".$src."', '".$dst."','500','0','".get_cl($class)."','".$num_seats."')";

// echo $query;
if((seat_avail($tnum,$src,$dst,$class,$date)-$num_seats)>=0)
	{
		$success=pg_query($query);
		if($success)
		{
			create_view_pnr($pnr,$src,$dst);
			update_no_of_seats($tnum,$src,$dst,$class,$date,$num_seats,true);
		}
	}
// //insert values for passengers
// for($i=0;$i<$num_seats;$i++)
// {
// 	$pass_query="insert into passengers(name,age,gender,seat_no) values(" .$name.$i+1.",".$age.$i+1.",".$gender.$i+1.",".$i+1.")";
// 	pg_query($pass_query);
	
// }


// $array=array();
// $array=$_POST;
// $count=count($_POST);
// echo $count;
// print_r($array);

// echo "pnr is <br/>\n".get_pnr()."<br/>\n";
// while($count-->0)
// {
// 	// echo $array[$count]."<br/>\n";
// }



//echo ($_POST['name2']=="");

//print_r( $_SESSION);

//insert into tickets 
//trigger to insert into booked_seat with argument = no of tuples to be inserted.

//-----------------------------------------------------------------------------------------------------------------------------//
//									tests in php 																				//
//-----------------------------------------------------------------------------------------------------------------------------//

// $a=array(1,2,3,4);
// $b=array(2,3,5);
// print_r(array_intersect($a, $b));


//-----------------------------------------------------------------------------------------------------------------------------//

?>


