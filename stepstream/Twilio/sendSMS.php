<?php

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


$phone_number = $_GET['phone_number'];
$messageBody = $_GET['messageBody'];

$sms = $client->account->sms_messages->create(
// the number we are sending from, must be a valid Twilio number
"678-929-6385",
// the number we are sending to - Any phone number
$phone_number,
// the sms body
$messageBody); 






?>

?>
