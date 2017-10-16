<?php

include('session.php');
if (isset($_POST['submit'])) {

$connection = mysql_connect("localhost","root", "password");
            if (!$connection) {
                die(mysql_error());
            }
$db = mysql_select_db("company", $connection);
if(!empty($_POST['text']) && !empty($_POST['control'])){
$control= stripslashes($_POST['control']);
$control = mysql_real_escape_string($control);
$text= stripslashes($_POST['text']);
$text = mysql_real_escape_string($text);
$sql="SELECT * FROM login";
if(strcmp($_POST['control'],"role") == 0){
$sql="SELECT * FROM login WHERE role='$text'";

}
elseif(strcmp($_POST['control'],"username")==0){
$sql="SELECT * FROM login WHERE username='$text'";

}

else{
$sql="SELECT * FROM login WHERE id='$text'";

}
$results = mysql_query($sql,$connection);

//$results = mysql_query("SELECT * FROM login WHERE '$_POST['control']'='$text'",$connection);
}
elseif(!empty($_POST['text']))
{
$text= stripslashes($_POST['text']);
$text = mysql_real_escape_string($text);
$results = mysql_query("SELECT * FROM login WHERE username='$text' OR role='$text' OR id='$text'",$connection);
}
else{
$results = mysql_query("SELECT * FROM login",$connection);

}
 mysql_close($connection);

}
if (isset($_POST['remove'])) {
$connection = mysql_connect("localhost","root", "password");
            if (!$connection) {
                die(mysql_error());
            }
    $x= $_POST['del'];
  if($x != 1){
    $sql="DELETE FROM login WHERE id='$x'";
	}
     $results = mysql_query($sql,$connection);
 mysql_close($connection);
}
if (isset($_POST['pass'])) {
$connection = mysql_connect("localhost","root", "password");
            if (!$connection) {
                die(mysql_error());
            }
    $x= $_POST['pass1'];
    $y= $_POST['id'];
     $sql="UPDATE login SET password='$x' WHERE id='$y'";
 $results = mysql_query($sql,$connection);
 mysql_close($connection);

}
if (isset($_POST['role'])) {
$connection = mysql_connect("localhost","root", "password");
            if (!$connection) {
                die(mysql_error());
            }
    $x= $_POST['role1'];
    $y= $_POST['id'];
     $sql="UPDATE login SET role='$x' WHERE id='$y'";
 $results = mysql_query($sql,$connection);
 mysql_close($connection);

}



?>


<!DOCTYPE html>
<html>
<head>
<title>Your Home Page</title>
<link href="view.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="table.css" type="text/css"/>
</head>
<body>
<div align="center">
<form action="" method="post">
<label>filter by:</label>
<input list="control" name="control">
  <datalist id="control">
    <option value="id">
    <option value="role">
    <option value="username">
  </datalist>
<label>search by:</label>
<input id="text" name="text" placeholder="your word" type="text">
<input name="submit" type="submit" value=" search ">
<a href="profile.php">back</a>
</div>
</form>
<div align="center" class="CSS_Table_Example">
 <table>
           
         
        
            <tr>  
                   <td>ID</td>
                   <td>USERNAME</td>
                   <td>ROLE</td>
		   <td>PASSWORd</th>
                   
	</tr>
   
            <?php while($row = mysql_fetch_array($results)): ?>
        <tr>
             
             
                   <td> <?php echo $row['id']; ?></td>
                   <td><?php echo $row['username']; ?></td> 
		<form action="" method="post"> 
		 <td><?php $role= $row['role'];  echo "<input id='role' name='role1' type='text' value='$role'>" ?>
                        <?php  echo "<input id='id'  name='role' type='submit' value='set'>"; ?></td>

                  <td> <?php $pass=$row['Password'];  echo "<input id='pass1' name='pass1' type='text' value='$pass'>";
			  echo "<input id='id' align='right' name='pass' type='submit' value=set>"; ?>
                         <?php $x1=$row['id'];  echo "<input name='id' type='hidden' value='$x1'>"; ?>

			</td>
		</form>			
                  <form action="" method="post">
                   <td>
                   <?php  echo "<input id='id' align='right' name='remove' type='submit' value=x>"; ?>
                   <?php $x1=$row['id'];  echo "<input name='del' type='hidden' value='$x1'>"; ?>
                   </td>      
                 </form>
               </tr>
            <?php endwhile; ?>
            
        </table>

</div>
    <form action="" methode="post">
  
  </form>
</body>
</html>
