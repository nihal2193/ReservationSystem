<?php


function view_exists($view_name)
{

	

	$view_exists="select count(*) from pg_class where relname='".$view_name."' ";
		
	$view = pg_query($view_exists)	;
		
		if(pg_fetch_row($view)[0]>0)
		{
			$drop_view="drop  view ".$view_name. " ";
			pg_query($drop_view);
			//echo " view ".$view_name ." existed and dropped  <br /> \n";
		return true;
		}
		else 
		{
			//echo "view ".$view_name ." does not exist <br /> \n";
			return false;
		}
			

}


function drop_view ($view_name)
{
	$to_be_dropped="drop view ".$view_name. " ";
	
	//echo "view ".$view_name." dropped pg_fetch_row($view)[0]) <br /> \n";
	pg_query($to_be_dropped);


}

function distance($tnum,$src,$dst)
{
	$distance="select distinct d1-d2 as d from trains_stations,(select dist as d1 from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as dli,(select dist as d2 from trains_stations where stncode='".$src."' and tnum='".$tnum."') as amh ";

	return pg_fetch_row(pg_query($distance))[0];
}

function arrtime($tnum,$stncode)
{
	$arrtime="select distinct regexp_replace(arrtime,'\.',':') from trains_stations where tnum='".$tnum."' and stncode='".$stncode."'";
//	echo " arr time ".pg_fetch_row(pg_query($arrtime))[0]." ".$stncode."<br/>\n";
	$arrtime=pg_fetch_row(pg_query($arrtime))[0];
	$arr=explode(':', $arrtime);
	//echo "   ".strlen($arr[1])." ".$tnum."  ".$arrtime."  ";
	if(count($arr)>1 && strlen($arr[1])==1)
		{
		//echo $arrtime."0";
		return $arrtime."0";
		}
	else if(count($arr)==1)
		{
		//echo $arrtime.":00";
		return $arrtime.":00";
		}

	return $arrtime;
}

function deptime($tnum,$stncode)
{
	$deptime="select distinct regexp_replace(deptime,'\.',':') from trains_stations where tnum='".$tnum."' and stncode='".$stncode."'";
	//echo " dep time ".pg_fetch_row(pg_query($deptime))[0]." ".$stncode ."<br/>\n";
		
	$deptime=pg_fetch_row(pg_query($deptime))[0];
	$dep=explode(':', $deptime);
	//echo "   ".strlen($arr[1])." ".$tnum."  ".$arrtime."  ";
	// echo $tnum."<br/>\n";
	// print_r($dep);
	if(count($dep)>1 && strlen("".$dep[1]."")==1)
	{
		//echo $arrtime."0";
		 return $deptime."0";
	}
	else if(count($dep)==1)
	{
		//	echo $arrtime.":00";
		return $deptime.":00";
	}

	return $deptime;

}

function duration($tnum,$src,$dst)
{
	$dep_src=deptime($tnum,$src);
	$arr_dst=arrtime($tnum,$dst);
	$day_diff=pg_fetch_row(pg_query("select distinct ddst.d2-dsrc.d1 from trains_stations,(select day as d2 from trains_stations where tnum='".$tnum."' and stncode='".$dst."') as ddst, (select day as d1 from trains_stations where tnum='".$tnum."' and stncode='".$src."') as dsrc"))[0];
	//echo "day difference is ".$day_diff."<br/>\n";

	return pg_fetch_row(pg_query("select '".$arr_dst."'::time - '".$dep_src."'::time + ".$day_diff."* interval '(24 hours)'") )[0];

}


function halts($tnum,$src,$dst)
{
	return count(stns_bw($tnum,$src,$dst));
}




function find_trains($src,$dst,$date)
{
	$trains=array();

	$query_v3="select tnum from (select tnum,stncode,dist,rank() over (partition by tnum order by dist asc) from trains_stations where tnum in (select tnum from trains_stations where stncode='".$dst."' and tnum in (select tnum from trains_stations where stncode ='".$src."')) and (stncode='".$src."' or stncode='".$dst."') )sub_query where rank =1 and stncode='".$src."'";

	$rs_v3=pg_query($query_v3);


	echo '';
	echo '<html><body><div class="table-responsive"><table class="table" border="2px" style="float:right;width:1000px;font-size:14px;border-collapse:collapse;align:center" >';
	echo "<tr>";
	echo " <th>no</th>";
	echo " <th>name</th>";
	echo " <th>type</th>";
	echo " <th>Src</th>";
	echo " <th>Dep. time</th>";
	echo " <th>Dst</th>";
	echo " <th>Arr. time</th>";
	echo " <th>Duration</th>";
	echo " <th>Halts</th>";
	echo " <th>S</th>";
	echo " <th>M</th>";
	echo " <th>T</th>";
	echo " <th>W</th>";
	echo " <th>T</th>";
	echo " <th>F</th>";
	echo " <th>S</th>";
	echo " <th>1A</th>";
	echo " <th>FC</th>";
	echo " <th>2A</th>";
	echo " <th>3A</th>";
	echo " <th>CC</th>";
	echo " <th>SL</th>";
	echo " <th>2S</th>";
	echo " <th>Distance</th>";
	echo " <th>Avg. speed(kms/hr)</th>";
	echo "</tr>";
	


	$i=0;
	while($row = pg_fetch_row($rs_v3))
	{
		
		$tnum=$row[0];
		//day difference is the day/s after which the train reach at the given source station starting from its source at day 1.
		$train_source=pg_fetch_row(pg_query("select src from trains_info where tnum='".$row[0]."'"))[0];
		
	//	echo "select day-1 from trains_stations where tnum='".$tnum."' and stncode='".$src."'"."<br/>\n";
		$day_diff=pg_fetch_row(pg_query("select day-1 from trains_stations where tnum='".$tnum."' and stncode='".$src."'"))[0];
		
	//	echo $day_diff. "<br /> \n";

	//	echo $date ." before <br/>\n";

		$date1=pg_fetch_row(pg_query("select DATE '".$date."' - integer '".$day_diff."  '"))[0];

	//	echo $date1 ." after <br/>\n";

		$day=pg_fetch_row(pg_query("select to_char(DATE '".$date1."','DY') as day"))[0];

		//echo $day;
		//$trains_info="select * from trains_info where tnum='".$row[0]."' and trains_info.".$day."='Y'  ";
		
		$trains_info="select * from trains_info where tnum='".$row[0]."' ";
		
		$rs_trains_info=pg_query($trains_info);
				
		while($t_info = pg_fetch_row($rs_trains_info))
		{
	
			$trains[$i++]=$t_info[0];
			echo '<tr>';
			$count=count($t_info);
			$y=0;
	
			while($y<$count)
			{

				$c_row=current($t_info);			
			
				if($y>=17 && $y <=23 && $c_row!=0)
				{
					$c_row=seat_avail($tnum,$src,$dst,$y,$date);
					$x='x';
					$c_row=$c_row>0?$c_row:$x;
					if($c_row>0)
						echo '<td><a href="book.php?class='.$y.' && tnum='.$tnum.'  && src='.$src.' && dst='.$dst.' && date='.$date.'"> '.$c_row.' </a></td>';
					else
						echo '<td>'. $c_row .'</td>';
				}

				//3rd row contains zone and it is skipped
				else if($y!=3 )
				{

				if($y==4)
					echo '<td>' . $src .'</td>';
				
				else if($y==5)
					echo '<td>' . deptime($tnum,$src) .'</td>';

				else if($y==6)
					echo '<td>' . $dst .'</td>';

				else if($y==7)
					echo '<td>' . arrtime($tnum,$dst) .'</td>';

				else if($y==8)
					echo '<td>' . duration($tnum,$src,$dst) .'</td>';

				else if($y==9)
					echo '<td>' . halts($tnum,$src,$dst) .'</td>';

				else if($y==24)
					echo '<td>' . distance($tnum,$src,$dst) .'</td>';

				else
					echo '<td>' . $c_row .'</td>';

				}

				next($t_info);
				$y=$y+1;
			}

			echo '</tr>';
		}
	}
	echo '</table></body></html>';

	return $trains;
}

	

function get_pnr()
{
	
	$pnr =pg_fetch_row(pg_query(" select to_char(current_timestamp,'YHH12MISSMS')"))[0];
	return $pnr;
}

function soj($tnum,$src)
{
	return pg_fetch_row(pg_query("select day from trains_stations where tnum='".$tnum."' and stncode='".$src."'" ))[0];
}

function eoj($tnum,$dst)
{
	return pg_fetch_row(pg_query("select day from trains_stations where tnum='".$tnum."' and stncode='".$dst."'" ))[0];
}

function get_cl($class)
{
	$c='sl';
	switch ($class)
	{
		case 17:
			$c='a1';
			break;
		
		case 18:
			$c='fc';
			break;
		
		case 19:
			$c='a2';
			break;
		
		case 20:
			$c='a3';
			break;
		
		case 21:
			$c='cc';
			break;
		
		case 22:
			$c='sl';
			break;
		
		case 23:
			$c='s2';
			break;
	}

	return $c;
}

function seat_avail($tnum,$src,$dst,$class,$date)
{

	$seat=100;
	//echo $class;
	$soj=soj($tnum,$src);
	$eoj=eoj($tnum,$dst);



	$dur=$eoj-$soj;
	$temp=$date;
	for($i=0;$i<=$dur;$i++)
	{
		// if($soj==$eoj)
		// {
			$date=pg_fetch_row(pg_query("select date '" .$temp."' + integer '". $i."'"))[0];
		// }

		// else
		// {
		// 	//start date has no change 
		// 	$date=$date+$i;
		// }

			$day=$soj+$i;
		switch ($class) 
		{
			case 17:
				$query="SELECT MIN(A1) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date '".$date."' AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;
			
			case 18:
				$query="SELECT MIN(fc) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date'".$date."'  AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;

			case 19:
				$query="SELECT MIN(a2) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date '".$date."' AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;

			case 20:
				$query="SELECT MIN(A3) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date'".$date."'  AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;

			case 21:
				$query="SELECT MIN(cc) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date'".$date."'  AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;

			case 22:
				echo "sleeper";
				$query="SELECT MIN(sl) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date'".$date."' AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;

			case 23:
				$query="SELECT MIN(s2) FROM NO_OF_SEATS WHERE TNUM='".$tnum."' AND DATE= date'".$date."' AND STNCODE IN(select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.day='".$day."' and ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
				break;
			default:
				$query="None";	
		}
		
		//echo "eoj ".$eoj." and soj ".$soj."   and tnum is ".$tnum."<br/>\n";
		//echo "class  ".get_cl($class)."<br/> \n ".$query;
		$new_seat=pg_fetch_row(pg_query($query))[0];	
		if($new_seat<$seat)
		{
		 	$seat=$new_seat;
		 	//echo $seat."in if";
		}
		//echo $seat;
	}
	return $seat;

}

function stns_bw($tnum,$src,$dst)
{

	$stns=array();

	$query_v3="select  ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc";

	$result=pg_query($query_v3);

	$i=1;
	while ($row= pg_fetch_row($result))
	{

				$stns[$i]=$row[0];
				//echo $stns[$i]."  ".$i." <br/>\n";
				$i=$i+1;
	}


	return $stns;
 }

function trans_id()
{
	return (uniqid());
}

//updating when the ticket boking or cancellation is done after update in tickets table
function update_no_of_seats($tnum,$src,$dst,$class,$date,$num_seats,$confirmed)
{

	$soj=soj($tnum,$src);
	$eoj=eoj($tnum,$dst);

	$dur=$eoj-$soj;

	$stns=stns_bw($tnum,$src,$dst);
	print_r($stns);

	$stns_keys=array_keys($stns);
	//echo "before date".$date;
	$temp=$date;
	//echo "after date".$temp;
	if($confirmed)
	{
		 $y=count($stns);
		
		for($j=0;$j<$y;$j++)
		{

			//$date=pg_fetch_row(pg_query("select date '" .$temp."' + integer '". $j."'"))[0];
			
			//$soj=$soj+$j;
		//	echo $stns[$stns_keys[$j]]."stations <br/>\n". "day ".$soj." <br/>\n";
			
			$query_day="select day from trains_stations where stncode='".$stns[$stns_keys[$j]]."' and tnum='".$tnum."'";
		//	echo "<br/> ".$query_day."<br/>";
			$day=pg_fetch_row(pg_query($query_day))[0];
			
		//	echo $day."<br/>\n";
			$day_differ=$day-$soj;
		//	echo "difference";
		//	echo $day_differ;
			$date=pg_fetch_row(pg_query("select date '" .$temp."' + integer '". $day_differ."'"))[0];
			
		//	echo $date;
			// while($row=pg_fetch_row($stns) )
			// {
			// 	$stn=$row[0];
				$query_seats="select ".get_cl($class)." from no_of_seats where tnum='".$tnum ."'and date='".$date."' and stncode='".$stns[$stns_keys[$j]]."'";
		//		echo $query_seats;
				$cur_seat=pg_fetch_row(pg_query($query_seats))[0];
				
				$cur_seat=$cur_seat-$num_seats;
				$update="update no_of_seats set ".get_cl($class)." = '".$cur_seat."' where tnum='".$tnum."' and stncode='".$stns[$stns_keys[$j]]."' and date='".$date."'";
		//		echo "updating <br/>\n";
		//		echo $update;
				pg_query($update);
			
			// }
		}
	}
//else cancelled due to some reasons

}

function create_view_pnr($pnr,$src,$dst) //this view is created only for the confirmed tickets and on the insertion of pnr in the tickets table
{	
	$tnum=pg_fetch_row(pg_query("select tnum from tickets_trains where pnr='".$pnr."'"))[0];
	$view="create view v_".$pnr."  as (select  '".$pnr."' as pnr,ts.stncode from trains_stations as ts,(select dist as d from trains_stations where stncode='".$src."' and tnum='".$tnum."') as damh,(select dist as d from trains_stations where stncode='".$dst."' and tnum='".$tnum."') as ddli where  ts.dist>=damh.d and ts.dist<=ddli.d and ts.tnum='".$tnum."' order by dist asc)";
	pg_query($view);
}

function drop_view_pnr($pnr)    //view v_pnr is dropped whenever an entry in the tickets gets cancelled
{
	$view='v_'.$pnr;
	drop_view($view);
}

//given a station tnum class and date return an array containing all the vacant seat nos
function get_vacant_seats($tnum,$stncode,$class,$date)
{
	$seats=array();

	for($i=0;$i<100;$i++)
	{
		$seats[$i]=$i+1;
	}

	$seats_once=false;
	//echo "getting vacant seats"." ncd <br/>\n";
	//echo $tnum." ncd <br/>\n";
	//echo $stncode." ncd <br/>\n";
	//echo $class." ncd <br/>\n";
	//echo $date." ncd <br/>\n";

	$pnr_list_ncd="select t.pnr from tickets as t,tickets_trains as tt where tt.tnum='".$tnum."' and t.pnr=tt.pnr and t.class='".$class."' and t.doj='".$date."'";

	// echo $pnr_list_ncd." ncd <br/>\n";

	$list1=pg_query($pnr_list_ncd);

	while($pnr_arr_ncd=pg_fetch_row($list1))
	{	
		$pnr_ncd=$pnr_arr_ncd[0];
	//	echo $pnr_ncd." ncd <br/>\n";
		$pnr_list_stn="select pnr from v_".$pnr_ncd." where (select exists (select  stncode from v_".$pnr_ncd." ) where stncode='".$stncode."')";
		//echo $pnr_list_stn;
		$list2=pg_query($pnr_list_stn);
		
		while($pnr_arr_stn=pg_fetch_row($list2))
		{
			$pnr_stn=$pnr_arr_stn[0];
	//		echo $pnr_stn." stn <br/>\n";
			$query_seats="select seat_no from generate_series(1,100) as seat_no where seat_no not in (select seat_no from passengers where pid in (select pid from tickets_passengers where pnr ='".$pnr_stn."')) ";
			
			// echo $query_seats;

			$temp=array();
			$seat_nos=pg_query($query_seats);
			while($s=pg_fetch_row($seat_nos))
			{
	//			echo $s[0]."<br/>\n";

				array_push($temp,$s[0]);
			}
			// if(!$seats_once)
			// {
			// 	$seats=$temp;
			// 	print_r($temp);
			// 	$seats_once=true;
			// }

		//	echo "before <br/>\n";
			$seats=array_intersect($seats,$temp);
			// print_r($seats);
		//	echo "after <br/>\n";
		}
		
	}
	// print_r($seats);
	//echo "count length".count($seats);
	// if(count($seats)==0)
	// {
	// 	for($i=1;$i<=100;$i++)
	// 	{
	// 		$seats[$i]=$i;
	// 	}
	// 	return $seats;
	// }

	return $seats;
}

function get_seat_nos($tnum,$bs,$ds,$class,$date)
{	
	//echo $tnum."<br/>";
	//echo $bs."<br/>";
	//echo $ds."<br/>";
	//echo $class."<br/>";
	//echo $date."<br/>";

	$seat_nos=array();
	$stns=stns_bw($tnum,$bs,$ds);
	//print_r($stns);

	$y=count($stns);
	
	//echo "count (stns ) ".$y."  stn0 "  .$stns[1];

	$seat_nos=get_vacant_seats($tnum,$stns[1],$class,$date);
	//echo "get_seat_nos <br/>\n";
	//print_r($seat_nos);

	for($i=2;$i<=$y;$i++)
	{
		$temp=get_vacant_seats($tnum,$stns[$i],$class,$date);
	//	echo $i."<br/>\n";
		// print_r($temp);
		$seat_nos=array_intersect($seat_nos, $temp);
	}
	
		// echo "seat_nos intersection <br/>\n";
		// print_r($seat_nos);
	return $seat_nos;
}


// function insert_no_of_seats()
// {

	
// 	//trains and seats upto tenth may added.
// 	$date='5/10/2014';

// 	$day=pg_fetch_row(pg_query("select to_char(DATE '".$date."','DY') as day"))[0];

// 	$query="insert into no_of_seats select ts.tnum, ts.stncode,('".$date."'::date + ts.day - 1 ) as date , ti.a1,ti.fc,ti.a2,ti.a3,ti.cc,ti.sl,ti.s2 from trains_stations as ts,trains_info as ti where ts.tnum in(select tnum from trains_info where ti.".$day."='Y') and ti.tnum=ts.tnum order by tnum,dist"; 

// 	  $row=pg_query($query);
// 	//  echo " rows affected<br/>\n". pg_num_rows($row);
// 	//  $n=pg_num_rows($row);
// 	//  $arr=array();
// 	// 	for($i=0;$i<$n;$i++)
// 	// 	{
// 	// 		$row1=pg_fetch_assoc($row);
// 	// 		//arr[$i]=$row1[]
// 	// 		print_r($row1);
// 	// 	}
// 	// while($rows=pg_fetch_($row) && $count<2 )
// 	// {
// 	// 	echo " printing arr size<br/>\n";
// 	// 	print count($rows);
// 	// 	$count++;
// 	// 	echo " count".$count." <br /> \n";
// 	// 	//next($rows);
		
// 	// }
	
	
// }	


function trigger_function_tu($uname,$trans_id,$pnr)
 {
	$tickets_users="create or replace function tickets_users() returns trigger as $"."tu_table"."$".
					"begin ".
					"insert into tickets_users values ('".$uname."','".$trans_id."',".$pnr.");". //nihal2193 is the current username
					"return new; ".
					"end ".
					"$"."tu_table"."$ language plpgsql; " ;

	pg_query($tickets_users);
	//echo $tickets_users;
	//echo $create_trigger;
}

function trigger_create()
{
	$create_trigger="CREATE TRIGGER trig_nihal AFTER INSERT ON tickets ".
					"FOR EACH ROW EXECUTE PROCEDURE tickets_users();";

	// $create_trigger;
	//pg_query($create_trigger);
}

//CREATE TRIGGER trig_nihal AFTER INSERT ON tickets 
//FOR EACH ROW EXECUTE PROCEDURE tickets_users()

function trigger_function_tt($tnum)   //trigger on tickets table
{
	$tnum=trim($tnum);
	$tickets_trains="create or replace function tickets_trains() returns trigger as $"."tu_table"."$".
					"begin ".
					"insert into tickets_trains values (new.pnr,'".$tnum."');". 
					"return new; ".
					"end ".
					"$"."tu_table"."$ language plpgsql; " ;
	pg_query($tickets_trains);				
}

function trigger_function_tp($pnr)   //trigger on passengers table
{
	//$tnum=trim($tnum);
	$tickets_passengers="create or replace function tickets_passengers() returns trigger as $"."tu_table"."$".
					"begin ".
					"insert into tickets_passengers values ('".$pnr."',new.pid);". 
					"return new; ".
					"end ".
					"$"."tu_table"."$ language plpgsql; " ;
	//echo $tickets_passengers;				
	pg_query($tickets_passengers);		

}

//CREATE TRIGGER trig_tp AFTER INSERT ON passengers 
//FOR EACH ROW EXECUTE PROCEDURE tickets_passengers()


//0 value for ticket is confirmed
//1 cancelled by user money refunded
//2 train cancelled
//3 date passed
//4 waiting ticket
// function trigger_passengers($trigger)
//  {
// 	$tickets_passengers="create or replace function tickets_passengers() returns trigger as $"."tp_table"."$".
// 					"begin ".
// 					"insert into passengers values (name,age,gender,seat_no);". //nihal2193 is the current username
// 					"return new; ".
// 					"end ".
// 					"$"."tu_table"."$ language plpgsql; " ;

// 	$create_trigger="CREATE TRIGGER ".$trigger." AFTER INSERT ON tickets ".
// 					"FOR EACH ROW EXECUTE PROCEDURE tickets_users();";

// 	pg_query($tickets_users);
// 	pg_query($create_trigger);
// 	//echo $tickets_users;
// 	//echo $create_trigger;
// }

function trigger_function_p($name,$age,$gender,$seat_nos,$num_seats)
{

	print_r($name);
//	echo "name ".$name[0]." age ".$age[0]." gender ".$gender[1]. " seat_nos ".$seat_nos[1];
	$passengers="create or replace function passengers() returns trigger as $"."p_table"."$".
					"begin ";
					
	//dont know why the index is like this
	$arr_keys=array_keys($seat_nos);
	// echo "arr keys";
	// print_r($arr_keys);
	// echo "after";		
	$key=$arr_keys[0];	
	$temp="";				
	for($i=0;$i<$num_seats;$i++)
	{
		$temp=$temp."insert into passengers(name,age,gender,seat_no) values ('".$name[$i]."','".$age[$i]."','".$gender[$i]."','".$seat_nos[$arr_keys[$i]]."');"; 					
	}				
				
	$passengers=$passengers.$temp."return new; "."end "."$"."p_table"."$ language plpgsql; " ;
	//echo $passengers;				
	pg_query($passengers);		

}

//CREATE TRIGGER trig_p AFTER INSERT ON tickets 
//FOR EACH ROW EXECUTE PROCEDURE passengers()



function drop_trigger($tbl,$trigger)
{
	$drop="DROP trigger ".$trigger."  ON ".$tbl;
	pg_query($drop);
}

?>