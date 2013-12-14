<?php
 $cid = $_GET['cid'];
 include("cheader.php");
 include ("cmenu.php");
 $n=$_POST['num'];
echo "<center>";
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
 for($i = 1 ; $i<=$n;$i++){
 	$ccid[$i] = $_POST['ccid'.$i];
	$reply[$i]=$_POST['reply'.$i];
	$ispublic[$i]=$_POST['ispublic'.$i];
	$reply[$i]=change_in($reply[$i]);
	$sql_reply = "update contest_clarify set reply='".$reply[$i]."',ispublic='".$ispublic[$i]."' where ccid='".$ccid[$i]."'";
	$que_reply = mysql_query($sql_reply);
	if($que_reply){
		echo $ccid[$i]." Reply Success.<br/>";
	}
	else{
		echo $ccid[$i]." Reply Failed<br/>";
	}
 }


 ?>


 <?
}
echo "</center>";
 include("footer.php");
?>
