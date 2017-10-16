<?php
include('session.php');
include('adduser.php');

?>
<!DOCTYPE html>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<title> Create New Account </title>
</head>
<body>
<div id="main">
<h1>CREATE NEW ACCOUNT</h1>
<div id="login">
<form action="" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
<label>User_role :</label>
<input id="role" name="role" placeholder="rolename" type="text">
<label>Enter the Password :</label>
<input id="password1" name="password1" placeholder="**********" type="password">
<label>confirm the Password   :</label>
<input id="password2" name="password2" placeholder="**********" type="password">

<input name="submit" type="submit" value=" Create ">
<span><?php echo $error; ?></span>
</form>
</div>
</div>



</body>

</html>




