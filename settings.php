<?php

// You must fill in these variables.

// Set our AccountSid and AuthToken from twilio.com/user/account
$AccountSid = '';
$AuthToken = '';

/* Your Twilio Number or Outgoing Caller ID (Note: Don't use +1) */
$caller_id = '5554445555';

// Phone number of group administrator. This number will receive notifications when people join. (Note: USE +1.)
$group_admin_number = '+13334445555';

// Name of the group administrator. This is used in the welcome text for new members
$group_admin_name = '';


// MYSQL settings
$mysql_host = '';

$mysql_user = '';

$mysql_pass = '';

$mysql_database_name = '';


// no need to edit anything below this line


$link = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
$db_selected = mysql_select_db($mysql_database_name, $link);
if (!$db_selected) {
    die ('Error selecting database_name : ' . mysql_error());
}
?>