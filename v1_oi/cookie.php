<?php
	if (!$_COOKIE["username"]||!$_COOKIE["password"]) {
		$nowuser="";
		$nowpass="";
	}
	else {
		$nowuser=$_COOKIE["username"];
		$nowpass=$_COOKIE["password"];
	}
?>
