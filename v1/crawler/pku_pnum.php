<?php
include("header.php");
?>
<center>
<?php
for ($i=1000;$i<4011;$i++) {
    $pid=$i;
    $str=file_get_contents("http://poj.org/problemstatus?problem_id=".$i);
    $pos1=strpos($str,",'status?problem_id=");
    $pos2=$pos1-1;
    while ($str[$pos2]!=',') $pos2--;
    $acnum=substr($str,$pos2+1,$pos1-$pos2-1);
    $pos1=$pos2;
    $pos2=$pos1-1;
    while ($str[$pos2]!=',') $pos2--;
    $totnum=substr($str,$pos2+1,$pos1-$pos2-1);
    //echo "$pid $acnum $totnum<br>";
    mysql_query("update problem set vacpnum='$acnum', vtotalpnum='$totnum' where vname='PKU' and vid='$pid'");
}
?>
</center>
<br>
<?php
include("footer.php");
?>

