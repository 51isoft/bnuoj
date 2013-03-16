<?php
    include_once("conn.php");
    $user = convert_str($_GET['username']);
    $cid = convert_str($_GET['cid']);
    $cpassed=db_contest_passed($cid);
    if (!db_user_match($nowuser,$nowpass)||(!db_contest_challenging($cid)&&!$cpassed)) {
        echo "Permission Denied.";die();
    }
    $sql = " SELECT * FROM `contest_problem` WHERE `cid` = ".$cid." order by cpid";
    $res = mysql_query($sql);
    $map = array();
    while ($row = mysql_fetch_array($res)) $map[$row["pid"]] =$row["lable"];
    $rret="";
    $sql="select * from challenge,status where challenge.username='".$user."' and cid='$cid' and challenge.runid=status.runid order by cha_id asc";
    $res=mysql_query($sql);
    $chaed=false;
    while ($row=mysql_fetch_array($res)) {
        //var_dump($row);die();
        $ret="";
        if ($cpassed||$nowuser==$row[1]) $row['cha_result']="<a name='".$row['cha_id']."' class='showchadet'>".$row['cha_result']."</a>";
        $ret.="<b>".$row[1]."</b> challenged <b>".$row[17]."</b>'s ".$map[$row['pid']]." at ".$row['cha_time'].", result: <b>".$row['cha_result']."</b>. ";
        if (strip_tags($row['cha_result'])=="Challenge Success") {
            if (!mysql_fetch_array(mysql_query("select * from challenge where cha_result='Challenge Success' and cha_id<".$row['cha_id']." and runid='".$row['runid']."' and cid='$cid'"))) $ret.="First successful challenge, points gained. ";
            else $ret.="But NOT first one. ";
        }
        $ret.="<br />\n";
        $rret=$ret.$rret;
    }
    echo $rret;
?>
