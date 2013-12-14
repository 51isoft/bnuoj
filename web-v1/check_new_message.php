<?php include("header.php"); 
echo "<center>";
?>
<?php
	$con = $_POST['content'];
	$title = $_POST[title];
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		echo "<p class='warn'>Please login first.</p>";
	}
	else if(/*$con == "" ||*/ $title ==""){
		echo "<p class='warn'>No Title.</p>";
	}
	else{
		$pid = $_GET[pid];
		$fid = $_GET[id];
		$uname = $nowuser;
		$sql = "select count(*) from discuss";
		$que = mysql_query($sql);
		$res = mysql_fetch_array($que);
		$num = $res[0]+1;
		$sql ="INSERT INTO discuss (`id` ,`fid` ,`rid` ,`time` ,`title` ,`content` ,`uname` ,`pid`)VALUES (NULL ,'0',  '$num', NOW( ) ,  '$title',  '$con',  '$uname',  '$pid')";
		$que = mysql_query($sql);
		if($que){
			echo "<span class='note'>Success!</span><br>";
		}
		$sql = "insert into time_bbs (`rid` ,`time`,`pid`) values ('$num', NOW(),'$pid')";
		$que = mysql_query($sql);
		if($que){
			echo "<a href=discuss.php class='bottom_link'>Return</a><br/>";
		}
	}
?>
<?php 
echo "</center>";
include("footer.php"); ?>
