<?php
include("conn.php");
//var_dump($_GET);
//exit;
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    if($cid != ""){
        $sql_r = "update status set result='Rejudging' where contest_belong='$cid'";
    }
    else {
        echo "No Contest ID.";die();
    }
    $que_r = mysql_query($sql_r);
    if($que_r){
        $host="localhost";
        $sql="select runid from status where result='Rejudging' and contest_belong='$cid'";
        $res=mysql_query($sql);
        //if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
        $port=$vserver_port;
        while ($row=mysql_fetch_array($res)) {
            $fp = fsockopen($host,$port,$errno, $errstr);
            if (!$fp) {
                echo "Judge Server Down.\n";die();
            }
            else {
                list($vname)=@mysql_fetch_array(mysql_query("select vname from problem,status where runid='".$row['runid']."' and problem.pid=status.pid"));
                $msg=$pretest_string."\n".$row['runid']."\n".$vname."\n";
                if (fwrite($fp,$msg)===FALSE) {
                    echo "Judge Server Down.\n";
                    exit;
                }
                fclose($fp);
            }
        }
        echo "Success.\n";
    }
}
?>
