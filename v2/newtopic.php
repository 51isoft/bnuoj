<?php
    include_once("conn.php");
	$con = convert_str($_POST['content']);
	$title = convert_str($_POST['title']);
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		echo "Not logged in.";
	}
	else if($title ==""){
		echo "No Title.";
	}
	else{
		$pid = convert_str($_GET['pid']);
        if ($pid!=""&&!db_problem_exist($pid)) {
            echo "No Such Problem!";die();
        }
		$fid = convert_str($_GET['id']);
		$uname = $nowuser;
		$sql = "select max(id) from discuss";
		$que = mysql_query($sql);
		$res = mysql_fetch_array($que);
		$num = $res[0]+1;
		$sql ="INSERT INTO discuss (`id` ,`fid` ,`rid` ,`time` ,`title` ,`content` ,`uname` ,`pid`)VALUES (NULL ,'0',  '$num', NOW( ) ,  '$title',  '$con',  '$uname',  '$pid')";
		$que = mysql_query($sql);
		if(!$que){
			echo "Failed!";die();
		}
		$sql = "insert into time_bbs (`rid` ,`time`,`pid`) values ('$num', NOW(),'$pid')";
		$que = mysql_query($sql);
		if($que){
			echo "Success!";
		}
        else echo "Failed!";
	}
?>
