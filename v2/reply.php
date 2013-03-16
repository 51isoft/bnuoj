<?php 
    include_once("conn.php");
	$con = convert_str($_POST['content']);
	$title = convert_str($_POST['title']);
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		echo "Not Logged in.";
	}
	else if(/*$con == "" ||*/ $title ==""){
		echo "No Title!";
	}
	else{
		$pid = convert_str($_GET['pid']);
		$fid = convert_str($_GET['id']);
		$rid = convert_str($_GET['rid']);
		$uname = $nowuser;
		$sql ="INSERT INTO discuss (`id` ,`fid` ,`rid` ,`time` ,`title` ,`content` ,`uname` ,`pid`)VALUES (NULL ,'$fid',  '$rid', NOW( ) ,  '$title',  '$con',  '$uname',  '$pid')";
		$que = mysql_query($sql);
		if(!$que){
			echo "Failed!";die();
		}
		$sql = "update time_bbs set time=NOW() where rid = $rid";
		$que = mysql_query($sql);
		if($que){
			echo "Success!";
		}
        else echo "Failed.";
	}
?>
