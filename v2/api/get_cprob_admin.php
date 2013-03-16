<?php
include_once("../config.php");
include_once("../conn.php");
if (!db_user_match($nowuser,$nowpass)||!db_user_isroot($nowuser)) {
    $ret['result']=0;
    echo json_encode($ret);
    die();
}
$type=convert_str($_GET['type']);
$value=convert_str($_GET['value']);
$out=convert_str($_GET['out']);
if ($type=="cid") $res=mysql_query("select pid from contest_problem where cid='$value' order by lable");
else $res=mysql_query("select pid from problem where source='$value' order by pid");
$i=0;
$ret=array();
$ret['result']=1;
while ($row=mysql_fetch_array($res)) {
    $ret[$out."pid".$i]=$row[0];
    $i++;
    $ret['result']=0;
}
echo json_encode($ret);
?>
