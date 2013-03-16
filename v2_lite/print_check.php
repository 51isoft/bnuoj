<?php
    include_once("conn.php");
    $username = convert_str($_POST['username']);
    $content = convert_str($_POST['content']);
    if ($nowuser==$username&&db_user_match($nowuser,$nowpass)) {
        $content = convert_str($_POST['content']);
        
        $sql_update="insert into print set username='$nowuser',content='$content',sent ='0'";
        $que_update=mysql_query($sql_update);
        
        if(!$que_update){
            echo "Failed.";die();
        }
        echo "Success!";
    }
    else {
        echo "Invalid Request!";
    }
?>
