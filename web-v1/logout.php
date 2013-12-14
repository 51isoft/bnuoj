<?php
	$lastpage=$_SERVER['HTTP_REFERER'];
	include("conn.php");
	setcookie('username',"");
	setcookie('password',"");
	echo "<script language='javascript'>";
	echo "window.location='$lastpage';";
	echo "</script>";
?>
