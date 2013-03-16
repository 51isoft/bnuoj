<?php
include("conn.php");
//var_dump($_GET);
//exit;
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    $pid = convert_str($_GET['pid']);
    $rac=convert_str($_GET['rac']);
    $type=convert_str($_GET['type']);
    if ($type==2) {
        $sql="select pid from contest_problem where cid='$cid' and lable='$pid' limit 0,1";
        $res=mysql_query($sql);
        if (mysql_num_rows($res)==0) {
            echo "No Such Problem.\n";
            exit;
        }
        list($pid)=mysql_fetch_array($res);
    }
    if($pid == ""){
        echo "Invalid Request.\n";
        exit;
    }
    else if($cid != ""){
        if ($rac==0) $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' and result!='Accepted' ";
        else $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' ";
    }
    else {
        $cid = 0;
        if ($rac==0) $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' and result!='Accepted' ";
        else $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' ";

    }
    $que_r = mysql_query($sql_r);
    if($que_r){
        $host="localhost";
        //if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
        $port=$vserver_port;
        $fp = fsockopen($host,$port,$errno, $errstr);
        if (!$fp) {
            echo "Judge Server Down.\n";
        }
        else {
            $msg=$rejudgestring."\n".$pid."\n".$cid."\n";
            if (fwrite($fp,$msg)===FALSE) {
                echo "Judge Server Down.\n";
                exit;
            }
            echo "Success.\n";
            fclose($fp);
        }
    }
}
?>
