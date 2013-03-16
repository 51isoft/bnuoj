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
		echo "<p class='warn'>没有这个接收者的ID.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	/*else if ($title=="") {
		include("header.php");
		echo "<p class='warn'>No Title.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}*/
	else {
		include("header.php");
		if ($title=="") $title="无标题";
		$query="insert into mail set sender='$nowuser', reciever='$reciever', content='$content', title='$title', mail_time=now(), status=false";
		$res=mysql_query($query);
		if (!$res) echo "<p class='warn'>发送失败.</p>";
		else echo "<p class='note'>发送成功 </p>";
	}
	echo "<center><a href='mail_index.php' class='bottom_link'>[返回收件箱]</a><a href='javascript:history.back(1);' class='bottom_link'>[后退]</a></center>";
include("footer.php");
?>
