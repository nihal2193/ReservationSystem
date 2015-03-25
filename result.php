<?php
session_start();
include('stations.php');
$src = $_GET['src'];
$src=explode(')',explode('(', $src)[1])[0];
$dst = $_GET['dst'];
$dst=explode(')',explode('(', $dst)[1])[0];
$date = $_GET['date'];



include ('views.php');
$conn=pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD);
		if (!$conn)
		{
			echo "Error".ERROR_ON_CONNECT_FAILED;
		}
		else
		{
			//echo "opened database";
			echo "<br /> \n";
		}
		//$password=md5($password);
		//$sql="select * from trains_info where tnum='12560'";
	//echo 'trains from ' .$src. "to ".$dst;

		//echo $date." date echoed <br/> \n";

		$trains=find_trains($src,$dst,$date);
		//echo "checking seat avail <br/>\n";
		//seat_avail('12225','CNB','AMH','20','4/19/2014');
		//echo "checking seat avail <br/>\n";
		// echo "hi"."<br/>\n";
		// echo trans_id()."<br/>\n";
		// echo get_pnr()."<br/>\n";
		// insert_no_of_seats();

		//deptime('12226','CNB');
		// arrtime('12226','CNB');
	
	//echo $date." date echoed <br/> \n";
	// duration($trains[1],$dst,$src);

	// //echo arrtime($trains[0],$dst);
		
	// //echo deptime($trains[0],$src);	

	// //print_r( $trains);
	// echo "distance between source and dest is ". distance($dst,$src,$trains[0])." <br/>\n";

	// for($i=0;$i<count($trains);$i++)
	// {	
	// 	//echo "route ".$trains[$i]."<br/> \n";
		
	// 	$stns=stns_bw($src,$dst,$trains[$i]);
	// 	$y=1;
	// 	while($y<count($stns))
	// 	{

	// 		echo " route ".$i." and Option ".$y."<br/> \n";

			
	// 		echo "distance between source and dest is ". distance($src,$stns[$y],$trains[$i])." <br/>\n";
	// 		echo "duration is ".duration ($trains[$i],$src,$stns[$y]);
	// 		echo 'trains from '.$src . "to ".$stns[$y]."<br/>\n";
	// 		find_trains($src,$stns[$y],$date);

	// 		echo "distance between source and dest is ". distance($stns[$y],$dst,$trains[$i])." <br/>\n";
	// 		echo "duration is ".duration ($trains[$i],$stns[$y],$dst)."<br/> \n";
	// 		echo 'trains from '.$stns[$y]. 'to '.$dst."<br/>\n";
	// 		find_trains($stns[$y],$dst,$date);
			
	// 		$y++;
			
	// 		//"select tnum from (select tnum,stncode,dist,rank() over (partition by tnum order by dist asc) from trains_stations where tnum in (select tnum from trains_stations where stncode='".$dst."' and tnum in (select tnum from trains_stations where stncode ='".$src."')) and (stncode='".$src."' or stncode='".$dst."') )sub_query where rank =1 and stncode='".$src."'";

	// 	}


		// echo "stns zero ".$stns[2];
		// find_trains($stns[$i],$dst,$date);

	// }	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <title>Landin Page of Project</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="signin.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript">
$(function(){
   $("#myAlert").bind('closed.bs.alert', function () {
      alert("Alert message box is closed.");
   });
});
 $(".alert").alert();
</script>
    <script type="text/javascript">
      var jArray= <?php echo json_encode($arr); ?>;
      var availableTags =jArray;
      $(function() {
        $( "#src" ).autocomplete({
          source: availableTags
        });
      });
      $(function() {
        $( "#dst" ).autocomplete({
          source: availableTags
        });
      });
    </script>
  </head>
  <body>
    <?php
        include('navbar.php');
     ?>    
    <div class="container">
    <div class="margin_top"></div>
    	<?php
    	// 	$trains=find_trains($src,$dst,$date);
    	 ?>

    </div> <!-- /container -->
  </body>
</html>