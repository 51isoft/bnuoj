<?php
/*
 * Created on 2009-3-31
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include("header.php");
echo '<center>';
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
 $title = $_POST['title'];
 $cid =$_POST['cid'];
 $isprivate=$_POST['isprivate'];
 $description =$_POST['description'];
 $lock_board_time=$_POST['lock_board_time'];
 $start_time=$_POST['start_time'];
 $end_time=$_POST['end_time'];
 $hide_others=$_POST['hide_others'];


$sql_add_con = "insert into contest (title,cid,description,isprivate,lock_board_time,start_time,end_time,hide_others) values ('$title'" .
		",'$cid','$description','$isprivate','$lock_board_time','$start_time','$end_time','$hide_others')";
$sql_add_con = change_in($sql_add_con);
$que_add_con = mysql_query($sql_add_con);
	//echo "<br/>".$sql_add_con."<br/>";
if($que_add_con){
	echo "<br>Contest No.$cid Successfully Added.<br/>";
	echo "<a href='admin_contest_problem_add.php?cid=$cid'>Add problems for it</a><br/>";
}
else{
	$sql_up_con = "update contest set title='$title',description='$description',isprivate='$isprivate',lock_board_time='$lock_board_time',start_time='$start_time',end_time='$end_time',hide_others='$hide_others' where cid='$cid'";

	//echo "??".$sql_add_con;

	$sql_up_con = change_in($sql_up_con);
	$que_up_con = mysql_query($sql_up_con);
	if($que_up_con){
		echo "<br>Contest No.$cid has been successfully updated.<br/>";
	}
	else{
	echo "Failed.<br/>";
	}
}
}
echo '</center>';
include("footer.php");

?>
