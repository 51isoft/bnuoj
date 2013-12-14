<?php
	include_once("conn.php");
	ob_implicit_flush();
	$uname=$_POST['user_id'];
	$pid = $_POST['problem_id'];
	$lang= $_POST['language'];
	$src= $_POST['source'];
	$flag=$_POST['submit'];
	$ip=getenv('REMOTE_ADDR');
	$flag=$flag2=true;
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)||$uname!=$nowuser) {
		setcookie("username","");
		setcookie("password","");
		$flag=false;
	}
	if (db_problem_hide($pid)&&!db_user_isroot($nowuser)) {
		$flag=false;
	}
	if (!db_problem_exist($pid)||$lang==0) {
		$flag=false;
	}
	include("header.php");
	if ($flag!='Submit') {
		$flag=FALSE;
	}
	$query="select * from problem where pid=$pid";
	$query =change_in($query);
	$result = mysql_query($query);
	if (!$result||mysql_num_rows($result)!=1) $flag=FALSE;
	$query="select UNIX_TIMESTAMP(time_submit) from status where username='$uname' order by time_submit desc limit 0,1";
	$query =change_in($query);
	$result = mysql_query($query);
	if (mysql_num_rows($result)==1) {
		$res=mysql_fetch_array($result);
		if (time()-$res[0]<5) $flag=$flag2=false;
	}
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
		echo "<script>window.location ='status.php';</script>";
	}
	else{
		//but can not work with refresh
		if ($flag2) echo "<br><center><h1>Invalid Submitting! Please check your submit.</h1></center><br>";
		else echo "<br><center><h1>Invalid Submitting! Please refresh after 5 seconds.</h1></center><br>";
	}
	//sql result
	include("footer.php");
?>
