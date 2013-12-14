<?php
include("header.php");
echo '<center>';
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
 $report = $_POST['report'];
$cid = $_POST['cid'];

	$sql_up_con = "update contest set report='$report' where cid=$cid";

	//echo "??".$sql_add_con;

	//$sql_up_con = change_in($sql_up_con);
	$que_up_con = mysql_query($sql_up_con);
	if($que_up_con){
		echo "<br>Report has been successfully updated.<br/>";
	}
	else{
    	echo "Failed.<br/>";
    }
}
echo '</center>';
include("footer.php");

?>
