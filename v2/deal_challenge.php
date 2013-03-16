<?php
    include_once("conn.php");
    $pid = convert_str($_POST['pid']);
    $user = convert_str($_POST['username']);
    $cid = convert_str($_POST['cid']);
    $dtype=convert_str($_POST['chadata_type']);
    $ddetail=convert_str($_POST['chadata_detail']);
    $dlang=convert_str($_POST['chadata_lang']);
    
    if (trim($ddetail)==""||!db_user_match($nowuser,$nowpass)||!db_user_in_contest($cid,$nowuser)||!db_contest_challenging($cid)) {
        echo "Invalid Challenge";die();
    }
    if ($nowuser==$user) {
        echo "Cannot challenge yourself.";die();
    }
    if ($dtype==1&&$dlang==0) {
        echo "Please Select Language.";die();
    }
    $sql="select max(UNIX_TIMESTAMP(cha_time)) from challenge where username='$nowuser'";
    list($lt)=mysql_fetch_array(mysql_query($sql));
    if (time()-$lt<5) {
        echo "Too Fast!";die();
    }
    $query="select runid,result,source from status where contest_belong='$cid' and pid='$pid' and username='$user' order by runid desc";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $runid=$row[0];
    if ($runid=="") {
        echo "Invalid Challenge";die();
    }
    if ($row[1]=="Challenged") {
        echo "Already been challenged";die();
    }
    $sql="select count(*) from challenge where runid='".$row[0]."' and (cha_result='Pending' or cha_result like 'Testing%')";
    $res=mysql_query($sql);
    $chaed=false;
    list($sum)=mysql_fetch_array($res);
    if ($sum=="") $sum=0;
    $sql="insert into challenge (username,runid,data_type,data_detail,data_lang,cha_time,cha_result,cid) values ('$nowuser','$runid','$dtype','$ddetail','$dlang',now(),'Pending','$cid')";
    $res=mysql_query($sql);
    $query="select max(cha_id) from challenge";
    $result = mysql_fetch_array(mysql_query($query));
	$nowid = $result[0];
    echo "Challenge queued, ID: $nowid . $sum undealt challenge(s) in front. Result will popup, don't refresh.";

    $msg=$challenge_string."\n".$nowid;
    list($vname)=@mysql_fetch_array(mysql_query("select vname from problem,status where runid='$runid' and problem.pid=status.pid"));
    $msg=$msg."\n".$vname;
    $host="localhost";
    //if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
    $port=$vserver_port;
    $fp = fsockopen($host,$port,$errno, $errstr);
    fwrite($fp,$msg);
    fclose($fp);
?>
