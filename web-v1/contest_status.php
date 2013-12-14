<?php include("cheader.php"); ?>
<?php
if ( isset($_GET['start']) ) $start = $_GET['start'];
else $start = "0";
if ( isset($_GET['cid']) ) $cid = $_GET['cid'];
else $cid = "0";
if ( isset($_GET['only']) ) $only = $_GET['only'];
else $only = "0";
$query2="select unix_timestamp(end_time),hide_others from contest where cid='$cid'";
$result2=mysql_query($query2);
$row2=@mysql_fetch_row($result2);
$nowtime=time();
$fitimeu=$row2[0];
if ($row2[1]!='0'&&!(db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser))) $only="1";
echo "<center>";
include("cmenu.php");
if ($only=="0") $query = "select count(runid) from status where contest_belong=$cid";
else $query = "select count(runid) from status where contest_belong=$cid and username='$nowuser'";
$sql = @mysql_query($query);
$row = @mysql_fetch_array($sql);
$end = $start+$numperrow;
if ($only=="0") echo "<a href='contest_status.php?start=0&cid=$cid&only=1'> <strong>Only View My Submits</strong> </a><br>";
else if ($row2[1]=='0') echo "<a href='contest_status.php?start=0&cid=$cid&only=0'> <strong>View All Submits</strong> </a><br>";
	else echo "<strong>In this contest, you can only view the submits of yourself.</strong><br>";
if ($start!=0) {
	if ($start<$numperrow) echo "<a href='contest_status.php?start=0&cid=$cid&only=$only'> <strong>&lt;&lt;Previous</strong> </a>";
	else {
		$prev=$start-$numperrow;
		echo "<a href='contest_status.php?start=$prev&cid=$cid&only=$only'> <strong>&lt;&lt;Previous</strong> </a>";
	}
}
if ($end < $row[0]) {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='contest_status.php?start=$end&cid=$cid&only=$only'><strong>Next&gt;&gt;</strong> </a>";
}
$query="select isroot from user where username='$nowuser'";
$result = @mysql_query($query);
list($isroot)=@mysql_fetch_row($result);
if ($only=="0") $query="select status.username,status.runid,contest_problem.lable,status.result,status.language,status.time_used,status.memory_used,status.time_submit,contest_problem.cpid from status,contest_problem where status.contest_belong='$cid' and contest_problem.cid=status.contest_belong and contest_problem.pid=status.pid order by runid desc limit $start,$numperrow";
else $query="select status.username,status.runid,contest_problem.lable,status.result,status.language,status.time_used,status.memory_used,status.time_submit,contest_problem.cpid from status,contest_problem where status.contest_belong='$cid' and status.username='$nowuser' and contest_problem.cid=status.contest_belong and contest_problem.pid=status.pid order by runid desc limit $start,$numperrow";
$result = @mysql_query($query);
echo "<table width=98%>";
echo "<tr>";
echo "<th width='11%'>User Name</th>";
echo "<th width='8%'>Run ID</th>";
echo "<th width='8%'>Problem</th>";
echo "<th width='14%'>Result</th>";
echo "<th width='11%'>Language</th>";
echo "<th width='11%'>Time Used</th>";
echo "<th width='11%'>Memory Used</th>";
echo "<th width='11%'>Code Length</th>";
echo "<th width='14%'>Time Submit</th>";
echo "</tr>";
list($locktu,$sttimeu,$fitimeu) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time) FROM contest WHERE cid = '$cid'"));
$nowtime=time();
while (list($uname,$runid,$lable,$res,$lang,$timeused,$memused,$timesubmit,$cpid)=@mysql_fetch_row($result)) {
	switch ($lang) {
		case "1":
			$lang="G++";
			break;
		case "2":
			$lang="GCC";
			break;
		case "3":
			$lang="JAVA";
			break;
		case "4":	
			$lang="Pascal";
			break;
		case "5":
			$lang="Python";
			break;
		case "6":
			$lang="C#";
			break;
		case "7":
			$lang="Fortran";
			break;
		case "8":
			$lang="Perl";
			break;
		case "9":
			$lang="Ruby";
			break;
		case "10":
			$lang="Ada";
			break;
		case "11":
			$lang="Standard ML";
			break;
	}
	echo "<tr>";
	echo "<td><center><a href=userinfo.php?name=$uname>$uname</a></center></td>";
	if ($isroot==TRUE||$nowuser==$uname) echo "<td><center><a href=show_source.php?runid=$runid&cid=$cid class='runid_link'>$runid</a></center></td>";
	else echo "<td><center>$runid</center></td>";
	echo "<td><center><a href=contest_problem_show.php?cpid=$cpid>$lable</a></center></td>";
	switch ($res) {
		case "Compile Error":
			echo "<td><center><strong><a href=show_ce_info.php?runid=$runid&cid=$cid class='ce'><span class='ce'>$res</span></a></strong></center></td>";
			break;
		case "Accepted":
			echo "<td><center><strong><span class='ac'>$res</span></strong></center></td>";
			break;
		case "Wrong Answer":
			echo "<td><center><strong><span class='wa'>$res</span></strong></center></td>";
			break;
		case "Runtime Error":
			echo "<td><center><strong><span class='re'>$res</span></strong></center></td>";
			break;
		case "Time Limit Exceed":
			echo "<td><center><strong><span class='tle'>$res</span></strong></center></td>";
			break;
		case "Memory Limit Exceed":
			echo "<td><center><strong><span class='mle'>$res</span></strong></center></td>";
			break;
		case "Output Limit Exceed":
			echo "<td><center><strong><span class='ole'>$res</span></strong></center></td>";
			break;
		case "Presentation Error":
			echo "<td><center><strong><span class='pe'>$res</span></strong></center></td>";
			break;
		case "Restricted Function":
			echo "<td><center><strong><span class='rf'>$res</span></strong></center></td>";
			break;
		default:
			echo "<td><center><strong>$res</strong></center></td>";
	}
	echo "<td><center>$lang</center></td>";
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||($uname==$nowuser&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$timeused ms</center></td>";
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||($uname==$nowuser&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$memused KB</center></td>";
	$query="select source from status where runid=$runid";
	$tempresult = @mysql_query($query);
	list($src)=@mysql_fetch_row($tempresult);
	$clength=strlen($src);
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||($uname==$nowuser&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$clength Byte</center></td>";
	echo "<td><center>$timesubmit</center></td>";
	echo "</tr>";
}
echo "</table>";
echo "</center>";
?>
<?php include("footer.php"); ?>
