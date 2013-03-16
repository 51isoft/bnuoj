<?php
    include_once("conn.php");
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
        $pid = convert_str($_GET['pid']);
        $query="select pid,title,description,input,output,sample_in,sample_out,number_of_testcase,special_judge_status,time_limit,case_time_limit,memory_limit,hint,source,hide,ignore_noc,author from problem where pid='$pid'";
        $result = mysql_query($query);
        if (mysql_num_rows($result)==0) echo "Error!";
        else {
            $res=array();
            list($res[pid],$res[title],$res[desc],$res[inp],$res[oup],$res[sinp],$res[sout],$res[noc],$res[spj],$res[tl],$res[ctl],$res[ml],$res[hint],$res[source],$res[p_hide],$res[p_ignore_noc],$res[author])=mysql_fetch_row($result);
            echo json_encode($res);
        }
    }
    else {
        echo "Error!";
    }
?>
