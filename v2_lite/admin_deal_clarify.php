<?php
//var_dump($_POST);
//exit;
include_once("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
 	$ccid=convert_str($_POST['ccid']);
	$reply=convert_str($_POST['answer']);
	$ispublic=convert_str($_POST["ispublic".$ccid]);
	$reply=change_in($reply);
    $sql_reply = "update contest_clarify set reply='".$reply."',ispublic='".$ispublic."' where ccid='".$ccid."'";
//    echo $sql_reply;
	$que_reply = mysql_query($sql_reply);
	if($que_reply){
		echo $ccid." Reply Success.";
	}
	else{
		echo $ccid." Reply Failed.";
	}
}
else {
    echo "Invalid Request!";
}
?>
