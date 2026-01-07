<?php
session_start();
include 'connect.php';
$uname=$_POST['email'];
$pass=$_POST['password'];

$_SESSION['login']=0;
if($uname=="admin" && $pass=="admin"){
	$_SESSION['login']=1;
header("Location:attacks.php");
}
else{
	
header("Location:logout.php");
}

?>,,