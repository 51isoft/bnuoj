<?php
include("header.php");
?>
<center>
<?php
str_pad("",10000);
$res=mysql_query("select pid from problem where vname='BNU'");
while ($row=mysql_fetch_array($res)) {
    list($total)=mysql_fetch_array(mysql_query("select count(distinct username) from status where pid=".$row[0]));
    list($ac)=mysql_fetch_array(mysql_query("select count(distinct username) from status where result='Accepted' and pid=".$row[0]));
    mysql_query("update problem set vacpnum=$ac, vtotalpnum=$total where pid=".$row[0]);
    echo "Updated PID: ".$row[0].". AC: $ac, Total: $total.<br />\n";
    flush();
}
?>
</center>
<br>
<?php
include("footer.php");
?>

