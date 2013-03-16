<?php
	include_once("conn.php");
	$runid = $_GET['runid'];
    $pagetitle="Show the source of ".$runid;
	$cid = $_GET['cid'];
	$query="select result,memory_used,time_used,username,source,language,pid,isshared from status where runid=$runid";
	$result = mysql_query($query);
	list($res,$mu,$tu,$uname,$sour,$lang,$rpid,$isshared)=mysql_fetch_row($result);
    if ($cid!="") {
        $que="select lable from contest_problem where cid=$cid and pid=$rpid";
        list($pid)=mysql_fetch_array(mysql_query($que));
    }
    else $pid=$rpid;
	$sour=htmlspecialchars($sour);
	if ($nowuser!= ""&&$nowpass!=""&&db_user_match($nowuser,$nowpass)&&($isshared==TRUE||strcasecmp($nowuser,$uname)==0||db_user_iscodeviewer($nowuser))) {
		if ($cid==""||$cid=="0") include("header.php");
		else {
			include("cheader.php");
			include("cmenu.php");
		}
		echo "<center>";
		echo "<table width='98%'><th>";
		echo "<h2>Show Source</h2><br>";
		echo "Result: $res &nbsp;&nbsp;&nbsp; Memory Used: $mu KB &nbsp;&nbsp;&nbsp; Time Used: $tu ms <br>\n";
        $shjs=match_shjs($lang);
        $lang=match_lang($lang);
		echo "Language: $lang &nbsp;&nbsp;&nbsp; User Name: $uname &nbsp;&nbsp;&nbsp; Problem ID: $pid <br>\n";
        if ($isshared) echo "<br><b>This code is shared.</b><br>\n";
		echo '</th><tr><td><script type="text/javascript" src="js/sh_'.$shjs.'.js"></script>';
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
