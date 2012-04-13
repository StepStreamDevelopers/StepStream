<?php
  include 'jpgraph.php';
  include 'jpgraph_bar.php';
$my_conn = mysql_connect("statusnet", "statusnet", "PASSWORD") or die(mysql_error());
mysql_select_db("statusnet", $my_conn ) or die(mysql_error());

$profile_id = $_GET['profile_id'];

 $points_array = array();
    $labels_array = array();
	$query = "SELECT * FROM stepcount where profile_id = " . $profile_id; 
		 
	$result = mysql_query($query) or die(mysql_error());


	while($row = mysql_fetch_array($result)){
		 array_push($points_array,$row['points_earned']);
$dt = strtotime($row['step_date']); //make timestamp with datetime string
$dt_fmt = date("m-d-y", $dt); //echo the year of the datestamp just created 
	       array_push($labels_array,$dt_fmt);
	}


  $graph = new Graph(700, 400);
  $graph->SetScale('textint');
$graph->xaxis->SetTickLabels($labels_array);
  $plot = new BarPlot($points_array );


  $graph->xaxis->title->Set('(Date)');
  $graph->yaxis->title->Set('(Points)');
  $graph->Add($plot);
  $graph->Stroke();
?> 
