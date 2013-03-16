<?php
    include_once("conn.php");
    $pid = convert_str($_GET['pid']);
    $user = convert_str($_GET['username']);
    $cid = convert_str($_GET['cid']);
    $cpassed=db_contest_passed($cid);
    if (!db_user_match($nowuser,$nowpass)||(!db_contest_challenging($cid)&&!$cpassed)) {
        echo "Permission Denied.";die();
    }
    $query="select runid,result,source from status where contest_belong='$cid' and pid='$pid' and username='$user' order by runid desc";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $rret="";
    $sql="select * from challenge where runid='".$row[0]."' order by cha_id asc";
    $res=mysql_query($sql);
    $chaed=false;
    while ($row=mysql_fetch_array($res)) {
        $ret="";
        if ($cpassed||$nowuser==$row['username']) $row['cha_result']="<a name='".$row['cha_id']."' class='showchadet'>".$row['cha_result']."</a>";
        $ret.=$row['username']." challenged at ".$row['cha_time'].", result: <b>".$row['cha_result']."</b>. ";
        if ($chaed==false&&strip_tags($row['cha_result'])=="Challenge Success") {
            $ret.="First successful challenge, points gained. ";
            $chaed=true;
        }
        $ret.="<br />\n";
        $rret=$ret.$rret;
    }
    echo $rret;
?>
