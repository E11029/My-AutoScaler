<?php
include('login.php'); // Includes Login Script

if(isset($_SESSION['login_user'])){
header("location: profile.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Form in PHP with Session</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<style>
a{
 background: #bbb;
    background: -moz-linear-gradient(top, #efefef 0%, #bbb 100%);
    background: -o-linear-gradient(top, #efefef 0%, #bbb 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #efefef), color-stop(100%, #bbb));
    background: -webkit-linear-gradient(top, #efefef 0%, #bbb 100%);
    background: linear-gradient(top, #efefef 0%, #bbb 100%);
    border: 1px solid #aaa;
    color: yellow;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    display: inline-block;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    padding: 10px 25px 10px 10px;
    position: relative;
    text-decoration: none;
    margin-top : 200px ;
}
</style>
<body>
<div id="main">
<h1>AUTO_SCALE Dashboard</h1>
<div id="login">
<h2>Login Form</h2>
<form action="" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
<label>Password :</label>
<input id="password" name="password" placeholder="**********" type="password">
<input name="submit" type="submit" value=" Login ">
<span><?php echo $error; ?></span>
</form>
</div>
</div>
<a href = "index.html"> back to moniting </a>
</body>

</html>
