<?php
	include_once("conn.php");
	$question=convert_str($_POST['question']);
	$cid=convert_str($_GET['cid']);
	$question = change_in($question);
	if ($cid=="") $cid="0";
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		echo "Not Logged In";die();
	}
	else if ($cid=="0"||!db_contest_running($cid)) {
		echo "Contest Not Running";die();
	}
	else if (db_contest_private($cid)&&!db_user_in_contest($cid,$nowuser)) {
		echo "Not in Contest";die();
	}
	$query="insert into contest_clarify set cid='$cid',question='$question',username='$nowuser',ispublic=0";
	$result = mysql_query($query);
	echo "Success!";
?>
