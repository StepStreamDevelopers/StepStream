<?php
  include 'jpgraph/jpgraph.php';
  include 'jpgraph/jpgraph_bar.php';
  require_once 'PEAR.php';
  $param = array();
  $paramsFile = fopen('config.php','r');

  while(!feof($paramsFile))
  {
	$buffer = fgets($paramsFile);
	list($name,$value) = split('=',trim($buffer));

        if (strlen(strstr($name,"server"))>0) {
	 $value = str_replace("'" ,"", $value);
	 $value = str_replace(" " ,"", $value);
         $server_name = str_replace(";" ,"", $value) ;

       }
	if (strlen(strstr($name,"database"))>0) {
		$newval = str_replace("'" ,"", $value);
		$temp1 = explode("//" , $newval);

		$temp2 = explode(":" , $temp1[1]);
		$username = str_replace(";" ,"",$temp2[0]);

                $int_pos = strrpos($temp2[1], '@');
                $password = substr($temp2[1],0,$int_pos);

 
		$temp4 = explode("/" , substr($temp2[1],$int_pos + 1));
                $servername = str_replace(";" ,"",$temp4[0]);  
		$dbname = str_replace(";" ,"",$temp4[1]); 


   }

  }
	fclose ($paramsFile);


	$my_conn = mysql_connect($servername,$username, $password) or die(mysql_error());
	mysql_select_db($dbname, $my_conn ) or die(mysql_error());

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
	
	if ($dt != null) {
	
	  $graph = new Graph(500, 300);
          $graph->SetScale('textint');
	  $graph->xaxis->SetTickLabels($labels_array);
	  $plot = new BarPlot($points_array );


	  $graph->xaxis->title->Set('(Date)');
	  $graph->yaxis->title->Set('(Points)');
	  $graph->Add($plot);
	  $graph->Stroke(); 
	}
	else {
	echo '<p class="entry-content">no steps yet... but this is where you will see them!</p>';
	}
?> 
