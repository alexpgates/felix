Group Texter!
============

##### by @alexpgates


Here's a quick and dirty way to manage SMS groups using PHP and Twilio. This is handy for events like summer camps or other events when you want to get a message out to organizers / counselors quickly.

This code is a few years old, but it has been updated to use the latest Twilio PHP library.

### Features

- Onboarding flow for new members
    - Users join by texting anything to the number, then following the directions.
- Members can leave the group by sending: <code>-stop</code>.
- Members can change their nicknames by sending: <code>-name</code> (new nickname).
- Administrators get alerted when members join.
- All messages are logged in the database.
- Messages can contain up to 1600 characters.

### Requirements

- <a href="https://github.com/twilio/twilio-php">Twilio PHP library</a>
- A <a href="http://twilio.com">Twilio</a> account with credits and a caller ID verified phone number.
- PHP
- MySQL database
- A tolerance for procedural PHP

### Setup

- Get your account set up at Twilio and verify your number.
- Point your SMS settings to the location of sms.php on your server.
- Execute database_setup.sql to set up the required tables.
- Fill in the necessary variables in <code>settings.php</code>.
- Drop the Twilio PHP Library in the directory root and make sure the path at the top of <code>sms.php</code> is correct.
- Send a text to your number and follow the directions to join the group.

### To Do

- This needs a better way for an admin to manage the users. Right now, you need to manage users directly in the database.
- Yeah, it uses the depracated <code>mysql_connect()</code>. It should be updated to <code>mysqli_connect()</code> or pdo.

### Screenshots

##### Join Flow

<img src="https://dl.dropboxusercontent.com/u/2227623/group-texter-images/join-flow.png" width="300px">

##### Use

<img src="https://dl.dropboxusercontent.com/u/2227623/group-texter-images/use.png" width="300px">