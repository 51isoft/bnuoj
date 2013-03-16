<?php
	include_once("conn.php");
    $cid = convert_str($_POST['cid']);
    $pass = convert_str($_POST['password']);
    $query="select password from contest where cid='$cid'";
    $result = mysql_query($query);
    list($opass)=mysql_fetch_array($result);
    if ($opass==pwd($pass)) {
        setcookie("contest_pass_$cid",pwd($pass));
        echo "Right";
    }
    else echo "Wrong password.";
?>
