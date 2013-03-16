<?php
	include_once("conn.php");
	ob_implicit_flush();
	$uname=$_POST['user_id'];
	$content= $_POST['content'];
	$flag=$_POST['submit'];
	if ($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)) {
		setcookie("username","");
		setcookie("password","");
		$flag=false;
	}
	include("header.php");
	if ($flag=='Submit') {
		$flag=TRUE;
	}
	else {
		$flag=FALSE;
	}
	$conp = mysql_connect("219.224.30.66","printer","bnucpcprinter");
	mysql_query('SET NAMES "utf8"',$conp);
	$sql = mysql_select_db("contest_print",$conp);
	if ($flag) {
		$flag=FALSE;
		echo "<br>Printing...</br>";
		$query="insert into info set teamname='$uname',code='$content',fetched=0 ";
		$result = mysql_query($query,$conp);
		?><script type="text/javascript">
			alert("You request has been recieved, please wait.");
		</script><?php
		echo "<script>window.location ='print.php';</script>";
	}
	else{
		//but can not work with refresh
		echo "<br><center><h1>Invalid Submitting! Please check your submit.</h1></center><br>";
	}
	//sql result
	include("footer.php");
?>
