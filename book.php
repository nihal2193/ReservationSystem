<?php
session_start();
include('stations.php');
//print_r($arr);
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
	    	<div clas="margin_top">
	    		&nbsp&nbsp
	    	</div>
	    	<?php 
	    		print_r($_REQUEST);
				print_r($_SESSION);
				echo $_REQUEST['src'];
				//include('connectiondata.php');
				$link=pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD);
				echo "hhh$".$_REQUEST['src']."$";

				$sql1="select * from stations where stncode='".$_REQUEST['src']."'";
				$sql2="select * from stations where stncode='".$_REQUEST['dst']."'";

				echo $sql1."<br/>\n";
				echo $sql2."<br/>\n";

				$result1 = pg_query( $sql1);
				$result2 = pg_query($sql2);
				echo "t ".pg_num_rows($result1);
				echo "f ".pg_num_rows($result2);
				$res1=pg_fetch_assoc($result1);
				$res2=pg_fetch_assoc($result2);
				$fsrc=$res1['stnname']."(".$res1['stncode'].")";
				$fdst=$res2['stnname']."(".$res2['stncode'].")";
				echo "data= ".$fsrc." - ".$fdst;
			?>
			<form action="bookaction.php" method="post" class="form-signin" role="form" >
				<input type="hidden" name="src" <?php echo 'Value="'.$_REQUEST["src"].'"'; ?> >
				<input type="hidden" name="dst" <?php echo 'Value="'.$_REQUEST["dst"].'"'; ?> >
				<input type="hidden" name="date" <?php echo 'Value="'.$_REQUEST["date"].'"'; ?> >
				<input type="hidden" name="class" <?php echo 'Value="'.$_REQUEST["class"].'"'; ?> >
				<input type="hidden" name="tnum" <?php echo 'Value="'.$_REQUEST["tnum"].'"'; ?> >

				<table class="table table-bordered" border="2px" style="width:600px;font-size:14px;float:right" >
			<tr>	
				<td style="text-align:center"><b>Source</b></td>
				<td>
					<input type="text" class="form-control" style="text-align:center" <?php echo 'Value="'.$_REQUEST["src"].'"'; ?> readonly>
				</td>
			</tr>
			<tr>
				<td style="text-align:center"><b>Destination</b></td>
				<td>
					<input type="text" class="form-control" style="text-align:center" <?php echo 'Value="'.$_REQUEST["dst"].'"'; ?> readonly >
				</td>
			</tr>
			<tr>
				<td style="text-align:center"><b>Date of journey<b></td>
				<td>
					<input type="text" class="form-control" style="text-align:center" <?php echo 'Value="'.$_REQUEST["date"].'"'; ?> readonly>
				</td>
			</tr>
			</table>	
				<table class="table table-bordered" border="2px" style="border-color:#565211;width:600px;font-size:14px;border-collapse:collapse;align:center;float:right" >
				<tr>
					<th>No.</th>
					<th>Name</th>
					<th>Age</th>
					<th>Sex</th>
				</tr>
				<tr>
					<td>1.</td>
					<td><input type="text" class="form-control" placeholder="Name" name="name1" required></td>
					<td><input type="text" class="form-control" placeholder="Age" name="age1" required></td>
					<td>
						<select class="form-control" name="gender1">
				            <option  value="M">Male</option>
				            <option value="F">Female</option>
				        	<option value="O">Others</option>
				        </select>
					</td>	
				</tr>
				<tr>
					<td>2.</td>
					<td><input type="text" class="form-control" placeholder="Name" name="name2" ></td>
					<td><input type="text" class="form-control" placeholder="Age" name="age2" ></td>
					<td>
						<select class="form-control" name="gender2">
				            <option  value="M">Male</option>
				            <option value="F">Female</option>
				        	<option value="O">Others</option>
				        </select>
					</td>		
				</tr>
				<tr>
					<td>3.</td>
					<td><input type="text" class="form-control" placeholder="Name" name="name3" ></td>
					<td><input type="text" class="form-control" placeholder="Age" name="age3" ></td>
					<td>
						<select class="form-control" name="gender3">
				            <option  value="M">Male</option>
				            <option value="F">Female</option>
				        	<option value="O">Others</option>
				        </select>
					</td>		
				</tr>
				<tr>
				</tr>	
				</table>
				<button class="btn btn-lg btn-primary btn-block" type="submit" style="width:600px;float:right">Book</button>

			</form>	
	    </div>
	</body>
</html>
