<?php
include('connectiondata.php');
    $link=pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD);
if (!$link)
    {
      echo "Error".ERROR_ON_CONNECT_FAILED;
    }
    else
    {
      //echo "opened database<br/>";
    }
$sql="select * from stations";
$result = pg_query($link,$sql);
$size=pg_num_rows($result);
$arr=array();
for($i=0;$i<$size;$i++)
{
  $output=pg_fetch_assoc($result);
  $arr[$i]=$output['stnname']."(".$output['stncode'].")";
}
?>