<?php
/*
 * Created on 2009-3-31
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include("header.php");
echo '<center>';
$cid =$_GET['cid'];
$que="select isvirtual,owner,UNIX_TIMESTAMP(start_time) from contest where cid=$cid";
$row=mysql_fetch_array(mysql_query($que));
$nt=time();
if (db_user_match($nowuser,$nowpass)&&$row[0]==1&&$row[1]==$nowuser&&$row[2]>$nt) {
	$que="delete from contest where cid=$cid";
	$row=mysql_query($que);
	if (!$row) {
		echo "<span class=warn>Failed.</span>";
	}
	$que="delete from contest_problem where cid=$cid";
	$row=mysql_query($que);
	if (!$row) {
		echo "<span class=warn>Failed.</span>";
	}
}

else {
	echo "<span class=warn>You cannot delete this contest unless you are the owner and the contest has not started.</span>";
}
echo '</center>';
include("footer.php");

?>
