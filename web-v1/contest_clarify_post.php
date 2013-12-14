<?php
	include_once("conn.php");
	$question=$_POST['question'];
	$cid=$_GET['cid'];
	$flag=$_POST['Submit'];
	$question=$_POST['question'];
	$question = change_in($question);
	if ($cid=="") $cid="0";
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		include("cheader.php");
		echo "<center>";
		include("cmenu.php");
		$flag=false;
	}
	else if ($cid=="0"||!db_contest_running($cid)) {
		include("cheader.php");
		echo "<center>";
		include("cmenu.php");
		$flag=false;
	}
	else if (db_contest_private($cid)&&!db_user_in_contest($cid,$nowuser)) {
		include("cheader.php");
		echo "<center>";
		include("cmenu.php");
		$flag=false;
	}
	else {
		include("cheader.php");
		echo "<center>";
		include("cmenu.php");
	}
	if ($flag=='Post') {
		$flag=TRUE;
	}
	else {
		$flag=FALSE;
	}
	if ($flag) {
		$flag=FALSE;
		echo "<br>Submitting...</br>";
		$query="insert into contest_clarify set cid='$cid',question='$question',username='$nowuser',ispublic=0";
		$sql_add_pro = change_in($sql_add_pro);
		$result = mysql_query($query);
		echo "<script>window.location='contest_clarify.php?cid=$cid';</script>";
	}
	else{
		//but can not work with refresh
		echo "<br><center><h1>Invalid Submitting! Please check your submit.</h1></center><br>";
	}
	//sql result
	include("footer.php");
?>
