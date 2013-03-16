<?php
	include_once("conn.php");
	$runid = $_GET['runid'];
    $pagetitle="查看RUNID为".$runid."的源代码";
	$cid = $_GET['cid'];
	$query="select result,memory_used,time_used,username,source,language,pid,isshared from status where runid=$runid";
	$result = mysql_query($query);
	list($res,$mu,$tu,$uname,$sour,$lang,$pid,$isshared)=mysql_fetch_row($result);
	$sour=htmlspecialchars($sour);	
	$query="select isroot from user where username='$nowuser'";
	$result = mysql_query($query);
	list($isroot)=mysql_fetch_row($result);
	if ($nowuser!= ""&&$nowpass!=""&&db_user_match($nowuser,$nowpass)&&($isshared==TRUE||$nowuser==$uname||$isroot==TRUE)) {
		if ($cid==""||$cid=="0") include("header.php");
		else {
			include("cheader.php");
			include("cmenu.php");
		}
		echo "<center>";
		echo "<table width='98%'><th>";
		echo "<h2>源代码</h2><br>";
		// echo "Result: $res &nbsp;&nbsp;&nbsp; Memory Used: $mu KB &nbsp;&nbsp;&nbsp; Time Used: $tu ms <br>";
		switch ($lang) {
			case "1":
				$lang="G++";
				$shjs="cpp";
				break;
			case "2":
				$lang="GCC";
				$shjs="c";
				break;
			case "3":
				$lang="JAVA";
				$shjs="java";
				break;
			case "4":
				$lang="Pascal";
				$shjs="pascal";
				break;
			case "5":
				$lang="Python";
				$shjs="python";
				break;
		}
		echo "Language: $lang &nbsp;&nbsp;&nbsp; User Name: $uname &nbsp;&nbsp;&nbsp; Problem ID: $pid </th><tr><td>";
		echo '<script type="text/javascript" src="js/sh_'.$shjs.'.js"></script>';
		echo "<pre class='sh_$shjs'>";
		echo "<br/><br/><br/>$sour";
		echo "</pre>";
	}
	else {
		if ($cid==""||$cid=="0") include("header.php");
		else {
			include("cheader.php");
			include("cmenu.php");
		}
		echo "<center><table width='98%'><td>";
		echo "<br><center><h2>Permission denined. Please login first.</h2></center><br>";
	}
echo "</td></table></center>";
include("footer.php");
?>
