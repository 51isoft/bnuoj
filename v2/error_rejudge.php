<?php
include("conn.php");
$runid = $_POST['runid'];
if ($runid=="") {
    echo "Unable to rejudge.";
    die();
}
$sql="select pid,result,contest_belong,jnum from status where runid='$runid'";
$que = mysql_query($sql);
if (!$que) {
    echo "Unable to rejudge.";
    die();
}

list($pid,$result,$cid,$jnum)=mysql_fetch_array($que);

if ((!db_user_match($nowuser,$nowpass)||!db_user_isroot($nowuser))&&$jnum>1) {
    echo "Unable to rejudge. Already tried ".($jnum+1)." times.";
    die();
}

if ($result!="Judge Error"&&$result!="Judge Error (Vjudge Failed)"&&$result!="") {
    echo "Unable to rejudge.";
    die();
}

$ispretest=true;

if ($cid=="0"||!db_contest_has_cha($cid)||db_contest_passed($cid)) $ispretest=false;

        $host="localhost";
        /*if (db_problem_isvirtual($pid))*/ $port=$vserver_port;
        /*else {
            echo "Unable to rejudge.";
            die();
        }*/
        list($vname)=@mysql_fetch_array(mysql_query("select vname from problem where pid=$pid"));
        $sql_r = "update status set result='Rejudging',jnum=jnum+1 where runid='$runid' ";
        $que_r = mysql_query($sql_r);
        $fp = fsockopen($host,$port,$errno, $errstr);
        if (!$fp) {
            echo "<br>$errno ($srrstr) </br>\n";
        }
        else {
            if (!$ispretest) $msg=$reerrorstring."\n".$runid."\n".$vname;
            else $msg=$pretest_string."\n".$runid."\n".$vname;
            if (fwrite($fp,$msg)===FALSE) {
                echo "<br>can not send msg</br>";
                exit;
            }
            fclose($fp);
        }

echo $runid." has been sent to Rejudge.";
?>
