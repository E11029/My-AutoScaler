<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password1'])  || empty($_POST['role']) || empty($_POST['password2'])) {
$error = "Username or Password1 or password1  is invalid";
}
else if( $_POST['password2'] != $_POST['password1'])
{

$error ="passwords not match !";

}
else
{
// Define $username and $password
$username=$_POST['username'];
$role =$_POST['role'];
$password1=$_POST['password1'];
$password2=$_POST['password2'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "password");
$username = mysql_real_escape_string($username);
$role = mysql_real_escape_string($role);
$password1 = mysql_real_escape_string($password1);
//$password2 = mysql_real_escape_string($password2);
// Selecting Database
$db = mysql_select_db("MY_AUTO_SCALER", $connection);
//echo $db
// SQL query to fetch information of registerd users and finds user match.
$query= mysql_query("INSERT INTO login (username,password,role) VALUES ('{$username}','{$password1}','{$role}')", $connection);
//$rows = mysql_num_rows($query);
if ($query) {
echo ok; // Initializing Session
header("location: profile.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>

