<?php
	include_once("conn.php");
	$reciever=mysql_real_escape_string($_POST['reciever']);
	$title=mysql_real_escape_string($_POST['title']);
	$content=mysql_real_escape_string($_POST['content']);
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		echo "Please Login!";
	}
	else if (!db_user_exist($reciever)) {
		echo "No Such Reciever.";
	}
	else {
		if ($title=="") $title="No Title";
		$query="insert into mail set sender='$nowuser', reciever='$reciever', content='$content', title='$title', mail_time=now(), status=false";
		$res=mysql_query($query);
		if (!$res) echo "Failed.";
		else echo "Success.";
	}
?>
