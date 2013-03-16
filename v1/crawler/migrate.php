<?php include("header.php"); ?>
<center>
<?php
$sql="select * from problem where isvirtual=0 and vname not like 'BNU'";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    $sql2="insert into problem set";
    foreach ($row as $key => $value) {
        if ($key=="pid"||$key=="vid"||is_numeric($key)) continue;
        if ($key=="vname") $sql2.=" `$key`='BNU',";
        else $sql2.=" `$key`='".addslashes($value)."',";
    }
    $sql2=substr($sql2,0,-1);
    //echo $sql2."<br>";
    mysql_query($sql2);
    $sql2="select max(pid) from problem";
    list($newpid)=mysql_fetch_array(mysql_query($sql2));
    $sql2="update status set pid='$newpid' where pid='".$row['pid']."'";
    mysql_query($sql2);
    $sql2="update contest_problem set pid='$newpid' where pid='".$row['pid']."'";
    mysql_query($sql2);
    $sql2="update problem set vid='$newpid' where pid='$newpid'";
    mysql_query($sql2);
    $sql2="update problem set isvirtual='1' where pid='".$row['pid']."'";
    mysql_query($sql2);
}
?>
</center>
<br>
<?php include("footer.php"); ?>

