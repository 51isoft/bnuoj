<?php
include("conn.php");
//var_dump($_GET);
//exit;
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    if($cid == ""||!db_contest_has_cha($cid)){
        echo "Invalid Request.\n";
        exit;
    }
    else {
        $sql_r = "update status set result='Testing' where contest_belong='$cid' and result like 'Pretest Passed'";
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
            $msg=$test_all_string."\n".$cid."\n";
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
