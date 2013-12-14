<?php 
echo "<center>";
include("header.php"); ?>
<?php
	$con = $_POST['content'];
	$title = $_POST[title];
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		echo "<p class='warn'>Please login first.</p>";
	}
	else if(/*$con == "" ||*/ $title ==""){
		echo "<p class='warn'>No Title</p>";
	}
	else{
		$pid = $_GET[pid];
		$fid = $_GET[id];
		$rid = $_GET[rid];
		$uname = $nowuser;

		$sql ="INSERT INTO discuss (`id` ,`fid` ,`rid` ,`time` ,`title` ,`content` ,`uname` ,`pid`)VALUES (NULL ,'$fid',  '$rid', NOW( ) ,  '$title',  '$con',  '$uname',  '$pid')";
		$que = mysql_query($sql);
		if($que){
			echo "<span class='note'>Success!</span><br>";
		}
		$sql = "update time_bbs set time=NOW() where rid = $rid";
		$que = mysql_query($sql);
		if($que){
			echo "<a href=discuss.php class='bottom_link'>Return</a><br/>";
		}
	}
?>
<?php
echo "</center>";
 include("footer.php"); ?>
