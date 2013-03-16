<?php
	include_once("conn.php");
	ob_implicit_flush();
	$uname=convert_str($_POST['user_id']);
	$pid=convert_str($_POST['problem_id']);
	$lang=convert_str($_POST['language']);
    $src=convert_str($_POST['source']);
    $isshare=convert_str($_POST['isshare']);
    if ($isshare!="0") $isshare="1";
    setcookie("defaultshare",$isshare,time()+7*24*60*60);
	$ip=getenv('REMOTE_ADDR');
	$flag=$flag2=true;
    if (strlen($src)>256000) {
        echo "Source too long!";die();
        $flag=false;
    }
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)||$uname!=$nowuser) {
		setcookie("username","");
		setcookie("password","");
		echo "Invalid User."; die();
        $flag=false;
	}
	if (db_problem_hide($pid)&&!db_user_isroot($nowuser)) {
        echo "No Such Problem."; die();
		$flag=false;
	}
	if (!db_problem_exist($pid)) {
        echo "No Such Problem."; die();
		$flag=false;
    }
    if ($lang==0) {
        echo "Please Select Language."; die();
		$flag=false;
    }
	$query="select UNIX_TIMESTAMP(time_submit) from status where username='$uname' order by time_submit desc limit 0,1";
	$result = mysql_query($query);
	if (mysql_num_rows($result)==1) {
		$res=mysql_fetch_array($result);
		if (time()-$res[0]<5) $flag=$flag2=false;
	}
    if ($flag) {
        if ($lang<4&&$lang>0) setcookie("lastlang",$lang,time()+60*60*24*30);
		$flag=FALSE;
		$query="select max(runid) from status";
		$result = mysql_fetch_array(mysql_query($query));
		$maxid = $result[0];
		$nowid =$maxid+1;
		$query="insert into status set runid='$nowid' ,pid='$pid' ,source='$src' ,contest_belong='0', result='Waiting', language='$lang', username='$uname', ipaddr='$ip', isshared='$isshare' ";
		$result = mysql_query($query);
		$query="update problem set total_submit=total_submit+1 where pid='$pid' ";
		$result = mysql_query($query);
		db_insert_submit_time($nowid);
		$query="update user set total_submit=total_submit+1 where username='$nowuser' ";
		$result = mysql_query($query);
		$host="localhost";
        //if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
        $port=$vserver_port;
		$fp = fsockopen($host,$port,$errno, $errstr);
		if (!$fp) {
			echo "Submitted.";die();
		}
		else {
			$msg=$submitstring."\n".$nowid;
            //if (db_problem_isvirtual($pid)) {
                list($vname)=@mysql_fetch_array(mysql_query("select vname from problem where pid=$pid"));
                $msg=$msg."\n".$vname;
            //}
			if (fwrite($fp,$msg)===FALSE) {
				echo "Submitted.";
				exit;
			}
			fclose($fp);
		}
		echo "Submitted.";
	}
	else{
		//but can not work with refresh
		if ($flag2) echo "Invalid Submit.";
		else echo "Too Fast!";
	}
	//sql result
?>
