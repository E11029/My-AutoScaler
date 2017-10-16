<?php
include('session.php');
include('daily.html');
$connection = mysql_connect("localhost","root", "password");
            if (!$connection) {
                die(mysql_error());
            }
$db = mysql_select_db("company", $connection);
$sql="SELECT *  FROM login WHERE username='$login_session' AND role='superadmin'";
$results = mysql_query($sql,$connection);
$rows = mysql_num_rows($results);
 mysql_close($connection);
if($rows == 1){

echo '<div class="container">
    <ul class="nav nav-pills nav-justified">
    <li class="active"><a href="index.html">Home</a></li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
    <li><a href="#">view Accounts</a></li>
    <li><a href="createAc.php">create Account</a></li>
    <li><a href="reset.php">RESET Your ACCOUNT</a></li>
    <li><a href="logout.php">Logout</a></li>
 </ul></li>
  </ul>
</div>
';
}
else{
echo "<p><span><a class ='reset' href='reset.php'>RESET Your ACCOUNT</a>
</span></p>";
}
if(isset($_POST['submit'])){
if(!empty($_POST['control'])){
$myfile = fopen("val.txt", "r") or die("Unable to open file!");
$word=fread($myfile,filesize("val.txt"));
fclose($myfile);
//split into arrar use ","
$pieces = explode(",", $word);
if(strcmp("Memory",$_POST['control'])){
$pieces[0]=$_POST['crit'];
$pieces[1]=$_POST['warn'];
}
else{
$pieces[2]=$_POST['crit'];
$pieces[3]=$_POST['warn'];
}

$word= implode(",", $pieces);
$myfile = fopen("val.txt", "w") or die("Unable to open file!");
fwrite($myfile, $word);
fclose($myfile);

}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<title>Your Home Page</title>
<link href="control.css" rel="stylesheet" type="text/css">
</head>
<style>

</style>
<body>
<canvas id='c' style = 'position: absolute; left: 10%; top :10%;' >
</canvas>
<canvas id='d' style = 'position: absolute; left: 60%; top: 10%;' >
</canvas>
<canvas id='b' style = 'position: absolute; left: 0%; top: 40%;' >
</canvas>
<div id="control1">
<form action="" method="post">
<span > Select: <input list="control" name="control">
  <datalist id="control">
    <option value="Memory">
    <option value="CPU">
  </datalist>
  
  Critical: <input type="number" name="crit" value=80>
    Warning : <input type="number" name="warn" value=60></span>
  <input name="submit" type="submit" value="set">
</form>
</div>
<div id="profile">
<b id="welcome">Login user : <i><?php echo $login_session; ?></i></b>
<b id="logout"><a href="logout.php">Log Out</a></b>
</div> 
<script src="jquery.min.js">
</script>

<script>

(function() {

setInterval(initialize,10000);

$.get("test.txt", function(data1, status){
            window.$var = data1.split(",");
                  console.log(window.$var);});
$.get("val.txt", function(data2, status){
            window.$var1 = data2.split(",");
                  console.log(window.$var1);});

var
canvas = document.getElementById('c'),
   // Obtain a graphics context on the
   // canvas element for drawing.
   context = canvas.getContext('2d'),
  x = 20; //here this x use for minimum size of width or height;
 var
canvas1 = document.getElementById('d'),
   // Obtain a graphics context on the
   // canvas element for drawing.
   context1 = canvas1.getContext('2d'),
  x1 = 20;
var
canvas2 = document.getElementById('b'),
   // Obtain a graphics context on the
   // canvas element for drawing.
   context2 = canvas2.getContext('2d'),
  x2 = 20;

function initialize() {
// Register an event listener to
// call the resizeCanvas() function each time
// the window is resized.
//window.addEventListener('resize', resizeCanvas, false);
// Draw canvas border for the first time.
resizeCanvas();

 $.get("test.txt", function(data1, status){
            window.$var = data1.split(",");
                  console.log(window.$var);});
$.get("val.txt", function(data2, status){
            window.$var1 = data2.split(",");
                  console.log(window.$var1);});

window.addEventListener('resize', resizeCanvas, false);

}

function redraw() {

        x = canvas.width;
          if( x > canvas.height ) {

         x = canvas.height;

        }
        context.beginPath();

               context.arc(canvas.width / 2, canvas.height / 2, x/2 -2, 0, Math.PI * 2);
                context.strokeStyle = '#f54308';
               context.lineWidth =x/7;
                 context.stroke();
              context.beginPath();
              context.lineWidth =x/6;
             if (window.$var[0] < window.$var1[1]) {
                context.strokeStyle ='#aafb3c';
             }
             else if ( window.$var[0] <window.$var1[0] ){
                context.strokeStyle ='yellow';
             }
             else {
                context.strokeStyle = '#f54308';
             }

           //  context.arc(canvas.width / 2, canvas.height / 2,x/3 , 3 *Math.PI/2,window.$var[0]*2* Math.PI/100 - Math.PI/2 );
             if (window.$var[0] == 100 ) {
              context.arc(canvas.width / 2, canvas.height / 2,x/3 ,0,2*Math.PI);
             }else {
                context.arc(canvas.width / 2, canvas.height / 2,x/3 , 3 *Math.PI/2,window.$var[0]*2* Math.PI/100 - Math.PI/2 );
                }
              context.stroke();

             context.fillStyle ='black';
             var s = x/7;

             context.font = s +'px Georgia';

             context.fillText(window.$var[0] + "%",canvas.width/2 - s,canvas.height/2);

            }
function redraw1() {

        x1 = canvas1.width;
          if( x1 > canvas1.height ) {

         x1 = canvas1.height;

        }
        context1.beginPath();

               context1.arc(canvas.width / 2, canvas.height / 2, x/2 -2, 0, Math.PI * 2);
                context1.strokeStyle = '#f54308';
               context1.lineWidth =x/7;
                 context1.stroke();
              context1.beginPath();
              context1.lineWidth =x/6;
             if (window.$var[21] < window.$var1[3]) {
                context1.strokeStyle ='#aafb3c';
             }
             else if ( window.$var[21] <window.$var1[2] ){
                context1.strokeStyle ='yellow';
             }
             else {
                context1.strokeStyle = '#f54308';
             }

           //   context1.arc(canvas1.width / 2, canvas1.height / 2,x/3 , 3 *Math.PI/2,window.$var[21]*2* Math.PI/100 - Math.PI/2 );
              x1 = canvas1.width;
          if( x1 > canvas1.height ) {

         x1 = canvas1.height;

        }
if (window.$var[21] == 100 ) {
              context1.arc(canvas1.width / 2, canvas1.height / 2,x/3 ,0,2*Math.PI);
             }else {
                context1.arc(canvas1.width / 2, canvas1.height / 2,x/3 , 3 *Math.PI/2,window.$var[21]*2* Math.PI/100 - Math.PI/2 );

              }
              context1.stroke();

             context1.fillStyle ='black';
             var s = x1/7;
context1.font = s +'px Georgia';
             context1.fillText(window.$var[21] + "%",canvas1.width/2 - s,canvas1.height/2);


            }
function redraw2(){

  context2.beginPath();
  context2.moveTo(canvas2.width*10/100,canvas2.height*0/100); 
  context2.lineTo(canvas2.width*7/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*13/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*10/100,canvas2.height*0/100);
context2.fillStyle ='red';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[0] + "% >",canvas2.width*8/100,canvas2.height*90/100);
 context2.stroke();

 context2.beginPath();
  context2.moveTo(canvas2.width*20/100,canvas2.height*0/100);
  context2.lineTo(canvas2.width*17/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*23/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*20/100,canvas2.height*0/100);
context2.fillStyle ='yellow';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[1]  + "% >",canvas2.width*18/100,canvas2.height*90/100);

 context2.stroke();
context2.beginPath();
  context2.moveTo(canvas2.width*30/100,canvas2.height*0/100);
  context2.lineTo(canvas2.width*27/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*33/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*30/100,canvas2.height*0/100);
context2.fillStyle ='green';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[1]  + "% <",canvas2.width*28/100,canvas2.height*90/100);
 context2.stroke();
context2.beginPath();
  context2.moveTo(canvas2.width*60/100,canvas2.height*0/100);
  context2.lineTo(canvas2.width*57/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*63/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*60/100,canvas2.height*0/100);
context2.fillStyle ='red';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[2]  + "% >",canvas2.width*58/100,canvas2.height*90/100);
 context2.stroke();

 context2.beginPath();
  context2.moveTo(canvas2.width*70/100,canvas2.height*0/100);
  context2.lineTo(canvas2.width*67/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*73/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*70/100,canvas2.height*0/100);
context2.fillStyle ='yellow';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[3]  + "% >",canvas2.width*68/100,canvas2.height*90/100);
 context2.stroke();
context2.beginPath();
  context2.moveTo(canvas2.width*80/100,canvas2.height*0/100);
  context2.lineTo(canvas2.width*77/100,canvas2.height*60/100);
   context2.lineTo(canvas2.width*83/100,canvas2.height*60/100);
 context2.lineTo(canvas2.width*80/100,canvas2.height*0/100);
context2.fillStyle ='green';
 context2.fill();
context2.fillStyle ='black';
context2.font =  canvas2.height*20/100 +'px Georgia';
  context2.fillText(window.$var1[3]  + "% <",canvas2.width*78/100,canvas2.height*90/100);
 context2.stroke();

}
function resizeCanvas() {
canvas.width = window.innerWidth/4;
canvas.height = window.innerHeight/4;
redraw();
canvas1.width = window.innerWidth/4;
canvas1.height = window.innerHeight/4;
redraw1();
canvas2.width = window.innerWidth;
canvas2.height = window.innerHeight/10;
redraw2();

} })();

</script>

</body>
</html>
