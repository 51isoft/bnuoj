<?php
/*
 * Created on 2009-3-31
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include("header.php");
echo '<center>';
if (db_user_match($nowuser,$nowpass)) {
 $title = $_POST['title'];
 $cid =$_POST['cid'];
 $isprivate=0;
 $description =$_POST['description'];
 $lock_board_time=$_POST['lock_board_time'];
 $start_time=$_POST['start_time'];
 $end_time=$_POST['end_time'];
 $hide_others=$_POST['hide_others'];$n = $problemcontestadd;

for($i=0;$i<$n;$i++){
	$ccid[$i] = $_POST['cid'.$i];
	$pid[$i] = $_POST['pid'.$i];
	$lable[$i] = $_POST['lable'.$i];
}

$stt=strtotime($start_time);
$edt=strtotime($end_time);
$lbt=strtotime($lock_board_time);
$nt=time();

//echo "$title $start_time $end_time $pid[0] $stt $edt $lbt $nt ";
//echo $_POST['submit'];

if ($title==""||$start_time==""||$end_time==""||$pid[0]==""||$stt==0||$edt==0||$stt<$nt-10*60||$edt-$stt<30*60||$edt-$stt>5*24*60*60||($lbt!=0&&($lbt<$stt&&$lbt>$edt))) {
	echo "<span class=warn>Please fill the form correctly.</span>";
}
else {

	$sql_add_con = "insert into contest (title,cid,description,isprivate,lock_board_time,start_time,end_time,hide_others,owner,isvirtual) values ('$title'" .
			",'$cid','$description','$isprivate','$lock_board_time','$start_time','$end_time','$hide_others','$nowuser',1)";
	$sql_add_con = change_in($sql_add_con);
	$que_add_con = mysql_query($sql_add_con);
	//echo "<br/>".$sql_add_con."<br/>";
	if($que_add_con){
		echo "<br>Virtual Contest No.$cid Successfully Added.<br/>";
		for($i=0;$i<$n;$i++){
		if($pid[$i] == ""){
			continue;
		}
 //if($exist=="0"){
         $que=false;
		 if (db_problem_exist($pid[$i])&&!db_problem_hide($pid[$i])) {
            $sql = "insert into contest_problem (cid ,pid,lable) values('"."$ccid[$i]"."','"."$pid[$i]"."','"."$lable[$i]"."')";
		    $sql = change_in($sql);
		    $que = mysql_query($sql);
         }
		 if ($que) echo "Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."&nbsp;&nbsp;Successfully Added.<br/>";
		 else echo "Failed When Adding Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."<br/>";
// }
/* else{
	echo "Failed When Adding Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."&nbsp;&nbsp;Problem or Lable Already Exist.<br/>";
 }*/

	}
    $csql="select pid from contest_problem where cid=$cid";
    $cres=mysql_query($csql);
    $all=array();
    while ($crow=mysql_fetch_array($cres)) array_push($all,$crow[0]);
    sort($all);
    $tot="";
    foreach ($all as $num) {
       $tot= $tot.$num.",";
    }
//    echo "$tot\n<br>";
    $csql="update contest set allp='$tot' where cid=$cid";
    mysql_query($csql);

}
else{	echo "Failed.<br/>";
}



}

}

else {
	echo "<span class=warn>Please login first.</span>";
}
echo '</center>';
include("footer.php");

?>
