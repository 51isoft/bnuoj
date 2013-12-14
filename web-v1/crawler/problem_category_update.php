<?php
include("header.php");

$sql=mysql_query("select * from problem_category");
while ($row=mysql_fetch_array($sql)) {
    $g=$row['catid'];
    $pid=$row['pid'];
    while (true) {
        list($g)=mysql_fetch_array(mysql_query("select parent from category where id=".$g));
        if ($g==-1) break;
        mysql_query("update problem_category set weight=0 where catid=$g and pid=$pid");
    }
}


include("footer.php");
?>
