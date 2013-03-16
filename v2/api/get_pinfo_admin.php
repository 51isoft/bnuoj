<?php
include_once("../config.php");
include_once("../conn.php");
if (!db_user_match($nowuser,$nowpass)||!db_user_isroot($nowuser)) {
    echo "Error!";
    die();
}
$vname=convert_str($_GET['vname']);
$vid=convert_str($_GET['vid']);
if ($vname=="BNU") $row=mysql_fetch_array(mysql_query("select pid,title from problem where pid='$vid'"));
else $row=mysql_fetch_array(mysql_query("select pid,title from problem where vname='$vname' and vid='$vid'"));
if ($row==null) echo "Error!";
else echo json_encode($row);
?>
