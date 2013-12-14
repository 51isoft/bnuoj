<?php
	include_once("conn.php");
	ob_implicit_flush();
	$uname=$_POST['user_id'];
	$lang= $_POST['language'];
	$src= $_POST['source'];
	$flag=$_POST['submit'];
	$lab = $_POST['lable'];
	$cid = $_POST['contest_id'];
	$ip=getenv('REMOTE_ADDR');
    $flag=true;
    list($ctype)=mysql_fetch_array(mysql_query("select type from contest where type='$cid'"));
    if ($ctype!=0) $flag=false;
    if ($nowuser!=$uname) $flag=false;
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		setcookie("username","");
		setcookie("password","");
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
	else if (!db_lable_in_contest($cid,$lab)||$lang==0) {
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
	$sql="select pid from contest_problem where cid='$cid' and lable='$lab'";
	$que = mysql_query($sql);
	$rr = @mysql_fetch_array($que);
	if ($rr==NULL) $flag=FALSE;
	else $pid=$rr[0];
	if ($flag!='Submit') {
		$flag=FALSE;
	}
	$query="select * from problem where pid=$pid";
	$result = mysql_query($query);
	if (!$result||mysql_num_rows($result)!=1) $flag=FALSE;
	if ($flag) {
		$flag=FALSE;
		echo "<br>Submitting...</br>";
		$query="select max(runid) from status";
		$result = mysql_fetch_array(mysql_query($query));
		$maxid = $result[0];
		$nowid =$maxid+1;
		$query="insert into status set runid=$nowid ,pid=$pid ,source='$src' ,contest_belong='$cid', result='Waiting', language='$lang', username='$uname', ipaddr='$ip' ";
		$result = mysql_query($query);
		$query="update problem set total_submit=total_submit+1 where pid=$pid ";
		$result = mysql_query($query);
		db_insert_submit_time($nowid);
		$query="update user set total_submit=total_submit+1 where username='$nowuser' ";
		$result = mysql_query($query);
		$host="localhost";
		$port=$server_port;
        //if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
        $port=$vserver_port;
		$fp = fsockopen($host,$port,$errno, $errstr);
		if (!$fp) {
			echo "<br>$errno ($srrstr) </br>\n";
		}
		else {
			$msg=$submitstring."\n".$nowid;
            //if (db_problem_isvirtual($pid)) {
                list($vname)=@mysql_fetch_array(mysql_query("select vname from problem where pid=$pid"));
                $msg=$msg."\n".$vname;
            //}
			if (fwrite($fp,$msg)===FALSE) {
				echo "<br>can not send msg</br>";
				exit;
			}
			fclose($fp);
		}
		if ($cid==0) echo "<script>window.location ='status.php';</script>";
		else echo "<script>window.location ='contest_status.php?cid=$cid';</script>";
		echo "</center>";
	}
	else{
		//but can not work with refresh
		echo "<br><center><h1>Invalid Submitting! Please check your submit.</h1></center><br>";
	}
	//sql result
	include("footer.php");
?>
