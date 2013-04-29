<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    $lock = convert_str($_GET['lock']);
    if($cid == "" || $lock==""){
        echo "Failed.";
    }
    else {
	    $sql_r = "update contest set force_lock='$lock' where cid='$cid'";
        $que_r = mysql_query($sql_r);
        if ($que_r) {
            echo "Success.";
        }
        else echo "Failed.";
    }
}
else {
    echo "Not Admin.";
}
?>
