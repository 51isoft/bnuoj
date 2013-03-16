<?php
    include_once("conn.php");
    $cid = convert_str($_GET['cid']);
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)&&db_contest_type($cid)!=99) {
        $query="select cid,title,description,isprivate,start_time,end_time,lock_board_time,hide_others,report,type,has_cha,challenge_start_time,challenge_end_time from contest where cid='$cid'";
        $result = mysql_query($query);
        if (mysql_num_rows($result)==0) echo "Error!";
        else {
            $res=array();
            list($res[cid],$res[title],$res[description],$res[isprivate],$res[start_time],$res[end_time],$res[lock_board_time],$res[hide_others],$res[report],$res[ctype],$res[has_cha],$res[challenge_start_time],$res[challenge_end_time])=mysql_fetch_row($result);
            echo json_encode($res);
        }
    }
    else {
        echo "Error!";
    }
?>
