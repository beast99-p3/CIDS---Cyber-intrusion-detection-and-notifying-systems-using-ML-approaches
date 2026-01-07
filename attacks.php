<!DOCTYPE html>
<html>
<head>
	<title>Attacks</title>
	<style type="text/css">
		table, th, td {
  border: 1px solid darkcyan;
}
	</style>
</head>
<body style="color: cyan;background: white;">
<center>
	<h1 style="font-size: 50px;">Cyber attacks</h1>
	<?php
	session_start();
	if(!isset($_SESSION['login']) || $_SESSION['login']!=1)
		{header("Location:logout.php");}
	echo "(Date:  " . date("Y/m/d") . ")<br>";

	?>
	<hr style="background: cyan;">
	<br>
<span style="color: black;font-size: 30px;">	
<table>
		<tr>
			<td>Attack Types</td>
			<td>Packets</td>
		</tr>
	
	<?php
$file = fopen("data.txt","r");
$_SESSION['login']=1;
while(! feof($file))
  {

  	$ss=fgets($file);
  	if($ss==''){
  		break;
  	}
  	$stt=explode(" ", $ss);
  echo "<tr><td>".$stt[0]. "</td><td>".$stt[1]."</td></tr>";
  }

fclose($file);
?>
</table>
	<br>
	<h1>Attack Statistics:</h1>
	<img src="plot1.png" height="400px" width="1000px">
</center>
</body>
</html>