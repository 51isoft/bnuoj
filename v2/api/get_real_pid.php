<?php
include_once("../conn.php");
$vname=convert_str($_GET['vname']);
$vid=convert_str($_GET['vid']);
$row=mysql_fetch_array(mysql_query("select pid from problem where vname='$vname' and vid='$vid'"));
if ($row==null) echo "Error!";
else echo $row[0];
?>
