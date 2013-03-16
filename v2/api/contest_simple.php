<?php
include_once("../conn.php");
$from=convert_str($_GET['from']);
if ($from=="") $from="0";
if (isset($_GET['virtual'])) {
    if ($_GET['virtual']==1) $sql="select * from contest where isvirtual=1 and type!=99 order by cid desc limit $from, 50";
    else $sql="select * from contest where isvirtual=0 and type!=99 order by cid desc limit $from, 50";
}
else $sql="select * from contest order by cid desc limit $from, 50";
$res=mysql_query($sql);
$ret=array();
while ($row=mysql_fetch_array($res)) {
    $cur=array();
    $cur['title']=$row['title'];
    $cur['url']=$ojbase_url."contest_show.php?cid=".$row['cid'];
    $cur['start']=$row['start_time'];
    $cur['end']=$row['end_time'];
    $cur['detail']=$row['description'];
    if ($row['isprivate']==1) $cur['access']="Private";
    else if ($row['isprivate']==0) $cur['access']="Public";    
    else if ($row['isprivate']==2) $cur['access']="Password";
    $cur['isvirtual']=$row['isvirtual'];
    $cur['manager']=$row['owner'];
    if ($row['type']==0) $cur['type']="ICPC format";
    else if ($row['type']==1) $cur['type']="CF format";
    if ($row['type']!=99) $ret[]=$cur;
}
echo json_encode($ret);
?>
