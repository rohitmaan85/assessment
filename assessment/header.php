<?php
session_start();
if(!empty($_SESSION['userName'])) {
	$username	= $_SESSION['userName'];
}else{
	$requestedURL	=	$_SERVER['PHP_SELF'];
	header("location: login.php");
}
$version					=	"4.1.2";
$releaseDate				=	"05-04-2016";
$_SESSION['versionName'] 	=	$version;
?>
