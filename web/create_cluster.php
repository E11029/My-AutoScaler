<?php
//session_start(); // Starting Session
//$error=''; // Variable To Store Error Message
//$output = shell_exec('./check.sh');
//echo "<pre>'$output'</pre>";
if (isset($_POST['submit'])) {
echo 'hii';
if (empty($_POST['clustername']) || empty($_POST['sub_name']) || empty($_POST['Num_servers_base']) || empty($_POST['max_servers']) || empty($_POST['cpu_C']) || empty($_POST['cpu_W']) || empty($_POST['cpu_F']) || empty($_POST['Mem_C']) || empty($_POST['Mem_W']) || empty($_POST['Mem_F']) || empty($_POST['template_name']) ) {
$error = "Input error";
echo $error;
}
else
{

// Define $username and $password
$clustername=$_POST['clustername'];
$sub_name=$_POST['sub_name'];
$Num_servers_base=$_POST['Num_servers_base'];
$max_servers=$_POST['max_servers'];
$cpu_C=$_POST['cpu_C'];
$cpu_W=$_POST['cpu_W'];
$cpu_F=$_POST['cpu_F'];
$Mem_C=$_POST['Mem_C'];
$Mem_W=$_POST['Mem_W'];
$Mem_F=$_POST['Mem_F'];
$template_name=$_POST['template_name'];
//$monitor=$_POST['monitor'];
if(empty($_POST['monitor'])){
$monitor=0;

}
else{
$monitor=1;
}
echo $clustername,$sub_name,$Num_servers_base,$max_servers,$cpu_C,$cpu_W,$cpu_F,$Mem_C,$Mem_W,$Mem_F,$template_name,$monitor;
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "password");
// To protect MySQL injection for Security purpose
$clustername = stripslashes($clustername);
$sub_name = stripslashes($sub_name);
$Num_servers_base = stripslashes($Num_servers_base);
$max_servers = stripslashes($max_servers);
$cpu_C = stripslashes($cpu_C);
$cpu_W = stripslashes($cpu_W);
$Mem_C = stripslashes($Mem_C);
$Mem_W = stripslashes($Mem_W);
$Mem_F = stripslashes($Mem_F);
$template_name = stripslashes($template_name);
//$monitor = stripslashes($monitor);

$clustername = mysql_real_escape_string($clustername);
$sub_name = mysql_real_escape_string($sub_name);
$Num_servers_base = mysql_real_escape_string($Num_servers_base);
$max_servers = mysql_real_escape_string($max_servers);
$cpu_C = mysql_real_escape_string($cpu_C);
$cpu_W = mysql_real_escape_string($cpu_W);
$cpu_F = mysql_real_escape_string($cpu_F);
$Mem_C = mysql_real_escape_string($Mem_C);
$Mem_W = mysql_real_escape_string($Mem_W);
$Mem_F = mysql_real_escape_string($Mem_F);
$template_name = mysql_real_escape_string($template_name);
//$monitor = mysql_real_escape_string($monitor);

echo $clustername,$sub_name,$Num_servers_base,$max_servers,$cpu_C,$cpu_W,$cpu_F,$Mem_C,$Mem_W,$Mem_F,$template_name,$monitor;


// Selecting Database
$db = mysql_select_db("MY_AUTO_SCALER", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select clustername from cluster where clustername='$clustername';", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$error= "cluster already there";
mysql_close($connection);}
else{
echo 'ok';
$query = mysql_query("insert into cluster (clustername,sub_name,template_name,Num_servers,Num_servers_base,max_servers,cpu_W,cpu_C ,cpu_F,Mem_W,Mem_C,Mem_F,monitor,Created_date) 
VALUES('$clustername','$sub_name','$template_name','$Num_servers_base','$Num_servers_base','$max_servers','$cpu_W','$cpu_C','$cpu_F','$Mem_W','$Mem_C','$Mem_F','$monitor',now());", $connection);
//$rows = mysql_num_rows($query);
$query = mysql_query("select clustername from cluster where clustername='$clustername';", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$str="$Num_servers_base $clustername $sub_name $template_name";
$query = mysql_query("insert into queue(time,details,scriptcount) VALUES(now(),'$str',1)", $connection);
mysql_close($connection);
// execute script
} else {
$error = "connection error";
}
mysql_close($connection); // Closing Connection
}
}
}
?>
<html>
<head>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
  
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<body>
 

<form style="margin-top:5%;" action="" method="post">
  <div class="well" style="margin-left:10%; margin-right:10%;">
   
	<div class="form-group has-success">
  <label class="form-control-label" for="inputSuccess1">Cluster Name</label>
	<input type="text" class="form-control form-control-success" id="inputSuccess1" name="clustername">
	</div>
	<div class="form-group">
      <label for="TextInput">Sub Name</label>
      <input type="text" id="TextInput" class="form-control" name="sub_name" placeholder="input">
    </div>
    <div class="form-group">
      <label for="Template">Template</label>
      <select id="Template" class="form-control" name="template_name">
        <option><?php echo "optimusapp00"; ?></option>
      </select>
    </div>
	
    
	<div class="well" >
	<div class="form-inline">
	<div class="row">
	<div class="col-md-6 ">
	<div class="well" style="background-color:#668cff;">
      <div class="form-group has-danger"><label>cpu Threshold
	  <br>
	  <label> Critical:
        <input type="number" name="cpu_C" min="0" max="100" class="form-control form-control-danger"> 
		</label>
		<label>Warning:
        <input type="number" name="cpu_W" min="0" max="100">
		</label>
		<label>Fine:
        <input type="number" name="cpu_F" min="0" max="100">
		</label>
      </label></div>
	  </div>
	  </div>
	  <div class="col-md-6">
	  <div class="well" style="background-color: #668cff;">
	   <label>Mem Threshold
	   <br>
	   <label> Critical:
        <input type="number" name="Mem_C" min="0" max="100"> 
		</label>
	   <label>Warning:
        <input type="number" name="Mem_W" min="0" max="100">
		</label>
        <label>Fine:
        <input type="number" name="Mem_F" min="0" max="100">
		</label>		
      </label>
	  </div>
	  </div>
	  
	  
	 </div> 
	  </div>
		</div><div class="well" style="background-color: #668cff;">
		<div class="col-md-4 col-md-offset-1">
	  <label>base server count :
        <input type="number" name="Num_servers_base" min="0" max="100"> 
      </label></div>
	  <div class="col-md-4 col-md-offset-1">
	  <label>Max server count  :
        <input type="number" name="max_servers" min="0" max="100"> 
      </label>
	  </div>
	  <br></div>
	<div class="checkbox">
      <label>
        <input type="checkbox" name="monitor" value="true" > Enable monitoring
      </label>
    </div>
    <button type="submit" class="btn btn-primary" name="submit">Create New</button>
	</div>
	
	
  </fieldset>
</form>
</body>
</html>
