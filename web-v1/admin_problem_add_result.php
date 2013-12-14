<?php
 include("header.php");
echo "<center>";
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
$title = $_POST['p_name'];
$pid = $_POST['p_id'];
$hide = $_POST['hide'];
$description = $_POST['description'];
$input = $_POST['input'];
$output = $_POST['output'];
$sample_in = htmlspecialchars($_POST['sample_in']);
$sample_out = htmlspecialchars($_POST['sample_out']);
$hint = $_POST['hint'];
$source = $_POST['source'];

$memory_limit = $_POST['memory_limit'];
$time_limit = $_POST['time_limit'];
$special_judge_status = $_POST['special_judge_status'];
$case_time_limit = $_POST['case_time_limit'];
$basic_solver_value = $_POST['basic_solver_value'];
$ac_value = $_POST['ac_value'];

if($pid == ""){
    list($sql_pid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
    $pid = $sql_pid+1;
$sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,vname,vid) values ('$title','$description','$input','$output','$sample_in','$sample_out','$hint','$source','$hide','$memory_limit','$time_limit','$special_judge_status','$case_time_limit','$basic_solver_value','$ac_value', 'BNU', '$pid')";

}
 else{
 	$sql_add_pro = "update problem set title='$title',description='$description',input='$input',output='$output',sample_in='$sample_in',sample_out='$sample_out',hint='$hint',source='$source',hide='$hide',memory_limit='$memory_limit',time_limit='$time_limit',special_judge_status='$special_judge_status',case_time_limit='$case_time_limit',basic_solver_value='$basic_solver_value',number_of_testcase='$ac_value' where pid='$pid'";

 }
 //$sql_add_pro = change_in($sql_add_pro);
//echo $sql_add_pro;
$que_in = mysql_query($sql_add_pro);
if($que_in){
	echo "<h3>Success!";
    if ($pid=='') list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
    else $currpid=$pid;
    echo "<br>pid:$currpid</h3>";
}
else{
	echo "<h3>Failed.</h3>";
}

 ?>
<br><br>
<br>
</center>
<?php
} include("footer.php");
?>
