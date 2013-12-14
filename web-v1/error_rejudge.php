<?php
include("conn.php");
$runid = $_POST['runid'];
if ($runid=="") {
    echo "Unable to rejudge.";
    die();
}
$sql="select pid,result from status where runid='$runid'";
$que = mysql_query($sql);
if (!$que) {
    echo "Unable to rejudge.";
    die();
}

list($pid,$result)=mysql_fetch_array($que);

if ($result!="Judge Error"&&$result!="Judge Error (Vjudge Failed)") {
    echo "Unable to rejudge.";
    die();
}



        $host="localhost";
        if (db_problem_isvirtual($pid)) $port=$vserver_port;
        else {
            echo "Unable to rejudge.";
            die();
        }
        list($vname)=@mysql_fetch_array(mysql_query("select vname from problem where pid=$pid"));
        $sql_r = "update status set result='Rejudging' where runid='$runid' ";
        $que_r = mysql_query($sql_r);
        $fp = fsockopen($host,$port,$errno, $errstr);
        if (!$fp) {
            echo "<br>$errno ($srrstr) </br>\n";
        }
        else {
            $msg=$reerrorstring."\n".$runid."\n".$vname;
            if (fwrite($fp,$msg)===FALSE) {
                echo "<br>can not send msg</br>";
                exit;
            }
            fclose($fp);
        }

echo $runid." has been sent to Rejudge.";
?>
