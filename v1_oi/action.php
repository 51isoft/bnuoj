<?php
	include_once("conn.php");
	ob_implicit_flush();
	$debug = true;
	$uname=$_POST['user_id'];
	$pid = $_POST['problem_id'];
	$lang= $_POST['language'];
	$src= $_POST['source'];
	$flag=$_POST['submit'];
	$ip=getenv('REMOTE_ADDR');
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		setcookie("username","");
		setcookie("password","");
		$flag=false;
		if ($debug) 
			echo "user pass not match";
	}
	if (db_problem_hide($pid)) {
		$flag=false;
		if ($debug)
			echo "problem hided";
	}
	if (!db_problem_exist($pid)) {
		$flag=false;
		if ($debug)
			echo "problem not exist";
	}
	include("header.php");
/*	if ($flag=='Submit') {
		$flag=TRUE;
	}
	else {
		$flag=FALSE;
	} */
	$query="select * from problem where pid=$pid";
	$query =change_in($query);
	$result = mysql_query($query);
	if (!$result||mysql_num_rows($result)!=1) $flag=FALSE;
	if ($flag) {
		$flag=FALSE;
		echo "<br>Submitting...</br>";
		$query="select max(runid) from status";
		$result = mysql_fetch_array(mysql_query($query));
		$maxid = $result[0];
		$nowid =$maxid+1;
		$query="insert into status set runid=$nowid ,pid=$pid ,source='$src' ,contest_belong='0', result='Waiting', language='$lang', username='$uname', ipaddr='$ip' ";
		$result = mysql_query($query);
		$query="update problem set total_submit=total_submit+1 where pid=$pid ";
		$result = mysql_query($query);
		db_insert_submit_time($nowid);
		$query="update user set total_submit=total_submit+1 where username='$nowuser' ";
		$result = mysql_query($query);
		$host="localhost";
		if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
		$fp = fsockopen($host,$port,$errno, $errstr);
		if (!$fp) {
			echo "<br>$errno ($srrstr) </br>\n";
		}
		else {
			$msg=$submitstring."\n".$nowid;
			if (fwrite($fp,$msg)===FALSE) {
				echo "<br>can not send msg</br>";
				exit;
			}
			fclose($fp);
		}
		echo "<script>window.location ='status.php';</script>";
	}
	else{
		//but can not work with refresh
		echo "<br><center><h1>Invalid Submitting! Please check your submit.</h1></center><br>";
	}
	//sql result
	include("footer.php");
?>
