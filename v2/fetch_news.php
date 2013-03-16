<?php
    include_once("conn.php");
    $newsid = convert_str($_GET['nnid']);
    $query="select newsid,title,content,time_added,author from news where newsid='$newsid'";
    $result = mysql_query($query);
    if (mysql_num_rows($result)==0) echo "Error!";
    else {
        $res=array();
        list($res[newsid],$res[ntitle],$res[ncontent],$res[time_added],$res[author])=mysql_fetch_row($result);
        echo json_encode($res);
    }
?>
