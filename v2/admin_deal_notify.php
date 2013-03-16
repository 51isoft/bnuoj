<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $sub = convert_str($_POST['sub']);
    $sql_up_con = "update config set substitle='$sub' where lable=1";
    $que_up_con = mysql_query($sql_up_con);
    if($que_up_con){
        echo "Change Success.";
    }
    else{
        echo "Change Failed.";
    }
}
?>
