<?php
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
	$query = "SELECT * FROM stepcount where profile_id = " . $profile_id
	. " ORDER BY step_date";  
	
	$result = mysql_query($query) or die(mysql_error());

?>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Google Visualization API Sample
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Steps'],

<?php
	while($row = mysql_fetch_array($result)){
		 $daysteps = $row['step_count'];
       $dt = strtotime($row['step_date']); //make timestamp with datetime string
       $dt_fmt = date("m-d", $dt); //echo the year of the datestamp just created 
	print("['".$dt_fmt."', ".$daysteps."],");
	}
?> 

        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:"Your Steps",
                  width:760, height:480,
                  hAxis: {title: "Day"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 600px; height: 400px;"></div>
  </body>
</html>
​