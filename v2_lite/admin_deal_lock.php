<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    $hide = convert_str($_GET['hide']);
    if($cid == "" || $hide==""){
        echo "Failed.";
    }
    else {
	    $sql_r = "update problem set hide='$hide' where pid=any(select pid from contest_problem where cid='$cid')";
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
