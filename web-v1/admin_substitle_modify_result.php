<?php
include("header.php");
echo '<center>';
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
 $sub = $_POST['sub'];

	$sql_up_con = "update config set substitle='$sub' where lable=1";

	//echo "??".$sql_add_con;

	//$sql_up_con = change_in($sql_up_con);
	$que_up_con = mysql_query($sql_up_con);
	if($que_up_con){
		echo "<br>Substitle has been successfully updated.<br/>";
	}
	else{
    	echo "Failed.<br/>";
    }
}
echo '</center>';
include("footer.php");

?>
