<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $cid = convert_str($_GET['cid']);
    $share = convert_str($_GET['share']);
    if($cid == "" || $share==""){
        echo "Failed.";
    }
    else {
        $sql_r = "update status set isshared='$share' where contest_belong='$cid'";
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

