<?php
  include 'jpgraph.php';
  include 'jpgraph_bar.php';

if (!defined('STATUSNET')) {
    // This check helps protect against security problems;
    // your code file can't be executed directly from the web.
    exit(1);
}

$my_conn = mysql_connect("127.0.0.1/statusnet", "root", "celloman") or die(mysql_error());
mysql_select_db("statusnet", $my_conn ) or die(mysql_error());

$profile_id = $_GET['profile_id'];

 $points_array = array();
    $labels_array = array();
	$query = "SELECT * FROM weekly_points where profile_id = " . $profile_id; 
		 
	$result = mysql_query($query) or die(mysql_error());


	while($row = mysql_fetch_array($result)){
		 array_push($points_array,$row['cumulative_points']);
	       array_push($labels_array,$row['week_desc']);
	}


  $graph = new Graph(600,350);
  $graph->SetScale('textint');
  $plot = new BarPlot($points_array );

$graph->xaxis->title->Set('(Week)');
$graph->yaxis->title->Set('(Points)');
  $graph->Add($plot);
  $graph->Stroke();
?> 
