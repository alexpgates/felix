<?php
// This file uses enviromnet variables by default. If you don't have that set up, you can just set these variables right here.

// Set our AccountSid and AuthToken from twilio.com/user/account
$AccountSid = $_ENV['TWILIO_ACCOUNT_SID'];
$AuthToken = $_ENV['TWILIO_AUTH_TOKEN'];

/* Your Twilio Number or Outgoing Caller ID (Note: Don't use +1) */
$caller_id = $_ENV['TWILIO_PHONE_NUMBER'];

// Phone number of group administrator. This number will receive notifications when people join. (Note: USE +1.)
$group_admin_number = $_ENV['ADMIN_PHONE_NUMBER'];

// Name of the group administrator. This is used in the welcome text for new members
$group_admin_name = $_ENV['ADMIN_NAME'];

// MYSQL settings

if(array_key_exists('MYSQL_DATABASE_URL', $_ENV)) {
	$url = parse_url(getenv("MYSQL_DATABASE_URL"));
	$mysql_host = $url["host"];
	$mysql_user = $url["user"];
	$mysql_pass = $url["pass"];
	$mysql_database_name = substr($url["path"],1);
}
else if(array_key_exists('CLEARDB_DATABASE_URL', $_ENV)) {
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	$mysql_host = $url["host"];
	$mysql_user = $url["user"];
	$mysql_pass = $url["pass"];
	$mysql_database_name = substr($url["path"],1);
}
else {
	$mysql_host = '';
	$mysql_user = '';
	$mysql_pass = '';
	$mysql_database_name = '';
}


// no need to edit anything below this line
$link = mysqli_connect($mysql_host,$mysql_user,$mysql_pass,$mysql_database_name) or die("Error " . mysqli_error($link)); 
?>