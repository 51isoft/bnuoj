<?php
	include_once("conn.php");
	$reciever=$_POST['reciever'];
	$title=$_POST['title'];
	$content=$_POST['content'];
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else if (!db_user_exist($reciever)) {
		include("header.php");
		echo "<p class='warn'>No Such Reciever.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	/*else if ($title=="") {
		include("header.php");
		echo "<p class='warn'>No Title.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}*/
	else {
		include("header.php");
		if ($title=="") $title="No Title";
		$query="insert into mail set sender='$nowuser', reciever='$reciever', content='$content', title='$title', mail_time=now(), status=false";
		$res=mysql_query($query);
		if (!$res) echo "<p class='warn'>Failed.</p>";
		else echo "<p class='note'>Successfully sent.</p>";
	}
	echo "<center><a href='mail_index.php' class='bottom_link'>[Return List]</a><a href='javascript:history.back(1);' class='bottom_link'>[Go Back]</a></center>";
include("footer.php");
?>
