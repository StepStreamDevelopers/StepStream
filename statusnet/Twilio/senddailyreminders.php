#!/usr/bin/php
<?php
/* Send an SMS using Twilio. You can run this file 3 different ways:
*
* - Save it as sendnotifications.php and at the command line, run
* php sendnotifications.php
*
* - Upload it to a web host and load mywebhost.com/sendnotifications.php
* in a web browser.
* - Download a local server like WAMP, MAMP or XAMPP. Point the web root
* directory to the folder containing this file, and load
* localhost:8888/sendnotifications.php in a web browser.
*/
// Include the PHP Twilio library. You need to download the library from
// twilio.com/docs/libraries, and move it into the folder containing this
// file.
require "Services/Twilio.php";
// set our AccountSid and AuthToken - from www.twilio.com/user/account
$AccountSid = "ACf66cabe7755b437995ce7dfeae2241d9";
$AuthToken = "856592ef1ed7bb43c110afd137960f28";
// instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);
// make an associative array of people we know, indexed by phone number. Feel
// free to change/add your own phone number and name here.
  $param = array();
  $paramsFile = fopen('../config.php','r');

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



 	$points_array = array();
    	$labels_array = array();
	$query = "SELECT * FROM user where dailyreminder=1" ; 
	
	$result = mysql_query($query) or die(mysql_error());


	while($row = mysql_fetch_array($result)){

echo $row['phone_num'];
$sms = $client->account->sms_messages->create(
// the number we are sending from, must be a valid Twilio number
"678-929-6385",
// the number we are sending to - Any phone number
$row['phone_num'],
// the sms body
"How many steps did you get today? -stepstream.us"
); 


	}
// iterate over all our friends. $number is a phone number above, and $name
// is the name next to it


?>
