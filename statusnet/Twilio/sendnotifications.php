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

$error_flag = $_GET['error_flag'];
$step_count = $_GET['step_count'];
$step_date = $_GET['step_date'];
$phone_number = $_GET['phone_number'];
$phone_num_invalid = $_GET['phone_num_invalid'];
if($phone_num_invalid == "1")
{
  $message_string = "Whoops! We don't recognize this phone number. Log in to stephealth.org and set this as your number in SMS settings.";
}
else
if($error_flag == "1")
       $message_string = "Whoops! Try it this way: If you took $step_count steps on $step_date, send '0402 10000' -stephealth.org";
else
{
       
       $message_string = "Got it." .$step_count. " steps on " . $step_date. " Visit stephealth.org to see how your friends are doing!";
}

echo $message_string;
// iterate over all our friends. $number is a phone number above, and $name
// is the name next to it

/*$sms = $client->account->sms_messages->create(
// the number we are sending from, must be a valid Twilio number
"415-599-2671",
// the number we are sending to - Any phone number
"6788605254",
// the sms body
$message_string */




?>
