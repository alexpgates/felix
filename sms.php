<?php
require 'vendor/autoload.php'; // if using Composer
// require '/path/to/Twilio.php'; // if you aren't using Composer
require 'settings.php';

// grab the number of the number that is texting in
$from = mysqli_real_escape_string($link, $_REQUEST['From']);
$body = mysqli_real_escape_string($link, trim($_REQUEST['Body']));

// Check if the number exists in the database
$query = "SELECT * FROM numbers WHERE number='$from'" or die("MySQL Error:" . mysqli_error($link));
$result = mysqli_query($link, $query); 
// Check result
if (!$result) {
	$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	$message .= 'Whole query: ' . $query;
	die($message);
}
$foundcount = mysqli_num_rows($result);

if($foundcount != 1){
	$add = "INSERT INTO numbers (
	number) 
	VALUES (
	'$from')" or die("MySQL Error:" . mysqli_error($link));
	if (!mysqli_query($link, $add)){
		die('Error: ' . mysqli_error($link));
	}else{
		$client = new Services_Twilio($AccountSid, $AuthToken);
		$client->account->messages->sendMessage($caller_id, $from, 'Hello! It looks like you\'re new! To be added to the group, please reply to this message using only your name.');
		die();
	}
}

// this number already exists
while ($row = mysqli_fetch_array($result)) {
	$name = $row['name'];
	$users_id = $row['id'];
}

if($name == ''){
	// This is a new user. Lets add their name.
	$name_to_set = mysqli_real_escape_string($link, $body);

	$update =  "UPDATE numbers SET name='$name_to_set' WHERE id='$users_id' LIMIT 1" or die("MySQL Error:" . mysqli_error($link));
			if (!mysqli_query($link, $update)){
			  die('Error: ' . mysqli_error($link));
			}else{
				$client = new Services_Twilio($AccountSid, $AuthToken);
				$client->account->messages->sendMessage($caller_id, $from, 'Hi '.$body.'! You have been added to the list! A few things to remember:
1) Anything you send to this number will be forwarded to everyone in the group.
2) To leave the group any time, reply with: -stop
3) Have fun, and be nice!
4) Please text '.$group_admin_name.' at '.$group_admin_number.' if you have any problems or questions!');

		// get current user count
		$query = "SELECT * FROM numbers" or die("MySQL Error:" . mysqli_error($link));
		$result = mysqli_query($link, $query);
		// Check result
		if (!$result) {
			$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		$total_users = mysqli_num_rows($result);

		// Let the admin know there is a new user
		$client = new Services_Twilio($AccountSid, $AuthToken);
		$client->account->messages->sendMessage($caller_id, $group_admin_number, 'FYI '.$body.' has joined the group with the number '.$from.'. Total users: '.$total_users.'.');
		}

	// thats all for now.
	die();
}

// anything special happening?
$word_array = explode(' ', $body);

if($word_array[0] == '-name'){
	// the user wants to change their name
	
	// get a list of forbidden names
	$fquery = "SELECT name FROM numbers" or die("MySQL Error:" . mysqli_error($link));
	$fresult = mysqli_query($link, $fquery);
	// Check result
	if (!$fresult) {
		$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
		$message .= 'Whole query: ' . $fquery;
		die($message);
	}
	$forbidden_names = array();
	while ($frow = mysqli_fetch_array($fresult)) {
		$forbidden_names[] = $frow['name'];
	}
	
	
	// get their current name
	$oquery = "SELECT name FROM numbers WHERE number='$from'" or die("MySQL Error:" . mysqli_error($link));
	$oresult = mysqli_query($link, $oquery);
	// Check result
	if (!$oresult) {
		$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
		$message .= 'Whole query: ' . $oquery;
		die($message);
	}
	while ($orow = mysqli_fetch_array($oresult)) {
		$old_name = $orow['name'];
	}
	
	// first get what they want their name to be - it could be multiple words.
	// kick off the first -name
	$new_name_array = array_shift($word_array);
	$new_name = '';
	foreach($word_array as $n){
		$new_name .= $n.' ';
	}
	// remove any whitespace around the name
	$new_name = trim($new_name);
	
	if(in_array($new_name, $forbidden_names)){
		$client = new Services_Twilio($AccountSid, $AuthToken);
		$client->account->messages->sendMessage($caller_id, $from, 'Sorry, someone is already using the name: '.$new_name);
		die();
	}
	
	// don't let them be sneaky and change their name to the group administrator's name
	if(strtolower($new_name) == $group_admin_name){
		if($from != $group_admin_number){
			$client = new Services_Twilio($AccountSid, $AuthToken);
			$client->account->messages->sendMessage($caller_id, $from, "HA! You are not fooling anyone, chump!");
			die();
		}
	}
	
	// now we update the database with the new name
	$update =  "UPDATE numbers SET name='$new_name' WHERE number='$from' LIMIT 1" or die("MySQL Error:" . mysqli_error($link));
	if (!mysqli_query($link, $update)){
	  die('Error: ' . mysqli_error($link));
	}else{
		// now we get the numbers of everyone in the database that isn't the sender
		$t_query = "SELECT * FROM numbers" or die("MySQL Error:" . mysqli_error($link));
		$t_result = mysqli_query($link, $t_query);
		// Check result
		if (!$t_result) {
			$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
			$message .= 'Whole query: ' . $t_query;
			die($message);
		}
		while ($t_row = mysqli_fetch_array($t_result)) {
			$number = $t_row['number'];
			// send the sms to this number:
			$client = new Services_Twilio($AccountSid, $AuthToken);
			$client->account->messages->sendMessage($caller_id, $number, 'FYI '.$old_name.' is now known as: '.$new_name);
		}
	}
	
	// that's all we want to do now
	die();
}

if($word_array[0] == '-stop'){
	$query = "DELETE FROM numbers WHERE number = '$from' LIMIT 1" or die("MySQL Error:" . mysqli_error($link));;
	if (!mysqli_query($link, $query)){
	  die('Error: ' . mysqli_error($link));
	}
	$client = new Services_Twilio($AccountSid, $AuthToken);
	$client->account->messages->sendMessage($caller_id, $from, 'You have successfully unsubscribed! So long! : )');

	// that's all we want to do now
	die();
}

$message = $name.': '.$body;

// now we get the numbers of everyone in the database that isn't the sender
$t_query = "SELECT * FROM numbers WHERE number != '$from'" or die("MySQL Error:" . mysqli_error($link));
$t_result = mysqli_query($link, $t_query);
// Check result
if (!$t_result) {
	$message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	$message .= 'Whole query: ' . $t_query;
	die($message);
}
while ($t_row = mysqli_fetch_array($t_result)) {
	$number = $t_row['number'];
	// send the sms to this number:
	$client = new Services_Twilio($AccountSid, $AuthToken);
	$client->account->messages->sendMessage($caller_id, $number, stripslashes($name).': '.stripslashes($body));
}

// log this in the database

$add = "INSERT INTO messages (
message) 
VALUES (
'$message')" or die("MySQL Error:" . mysqli_error($link));
if (!mysqli_query($link, $add)){
  die('Error: ' . mysqli_error($link));
}
?>