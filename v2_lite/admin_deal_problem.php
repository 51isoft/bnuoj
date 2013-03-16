<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $title = convert_str($_POST['p_name']);
    $pid = convert_str($_POST['p_id']);
    $hide = convert_str($_POST['p_hide']);
//    echo $hide;
    $description = convert_str($_POST['description']);
    $input = convert_str($_POST['input']);
    $output = convert_str($_POST['output']);
    $sample_in = htmlspecialchars(convert_str($_POST['sample_in']));
    $sample_out = htmlspecialchars(convert_str($_POST['sample_out']));
    $hint = convert_str($_POST['hint']);
    $source = convert_str($_POST['source']);
    $author = convert_str($_POST['author']);
    $memory_limit = convert_str($_POST['memory_limit']);
    $time_limit = convert_str($_POST['time_limit']);
    $special_judge_status = convert_str($_POST['special_judge_status']);
    $case_time_limit = convert_str($_POST['case_time_limit']);
    $basic_solver_value = convert_str($_POST['basic_solver_value']);
    $noc = convert_str($_POST['noc']);
    $ignore_noc = convert_str($_POST['p_ignore_noc']);
    if($pid == ""){
        list($sql_pid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
        $pid = $sql_pid+1;
        $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,vname,vid,ignore_noc,author) values ('$title','$description','$input','$output','$sample_in','$sample_out','$hint','$source','$hide','$memory_limit','$time_limit','$special_judge_status','$case_time_limit','$basic_solver_value','$noc', 'BNU', '$pid', '$ignore_noc','$author')";
    }
    else{
        $sql_add_pro = "update problem set title='$title',description='$description',input='$input',output='$output',sample_in='$sample_in',sample_out='$sample_out',hint='$hint',source='$source',hide='$hide',memory_limit='$memory_limit',time_limit='$time_limit',special_judge_status='$special_judge_status',case_time_limit='$case_time_limit',basic_solver_value='$basic_solver_value',number_of_testcase='$noc',ignore_noc='$ignore_noc',author='$author' where pid='$pid'";
    }
    //$sql_add_pro = change_in($sql_add_pro);
    $que_in = mysql_query($sql_add_pro);
    if($que_in){
        echo "Success!";
        if ($pid=='') list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
        else $currpid=$pid;
        echo " ID: $currpid.";
    }
    else{
        echo "Failed.";
    }
}
?>
