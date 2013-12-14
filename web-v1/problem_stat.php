<?php
include("header.php");
include("problem_data.php");
$pid=$_GET['pid'];
if ( isset($_GET['start']) ) $start = $_GET['start'];
else $start = "0";
$lang=$_GET['lang'];
$orderby=$_GET['orderby'];
	$query="select hide from problem where pid='$pid'";
	$result = mysql_query($query);
	list($hide)=@mysql_fetch_row($result);
	if (mysql_num_rows($result)!=1||$hide) {
		echo "<br><center><table width=98%>";
		echo "<span class='warn'>Problem Unavailable!</span>";
		echo "</td></table></center>";
	}
	else {
?>
<center>
<body>
<h3>Statistics Of Problem <a href="problem_show.php?pid=<?php echo $pid; ?>"><?php echo $pid; ?></a></h3>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF("open-flash-chart.swf", "my_chart", "100%", "260", "9.0.0", "expressInstall.swf", {"data-file":"problem_data_echo.php?pid=<?php echo $pid; ?>"} );
</script>
<table width=98%>
	<tr>
	<td width=40%>
	<div id="my_chart"></div>
	<table width=100% class=pstat>
		<tr>
		<td class=pstat>Total Submission:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid' class=pstat>".$ntot."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Accepted:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Accepted' class=pstat>".$nac."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Compile Error:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Compile+Error' class=pstat>".$nce."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Wrong Answer:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Wrong+Answer' class=pstat>".$nwa."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Presentation Error:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Presentation+Error' class=pstat>".$npe."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Runtime Error:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Runtime+Error' class=pstat>".$nre."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Time Limit Exceed:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Time+Limit+Exceed' class=pstat>".$ntle."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Memory Limit Exceed:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Memory+Limit+Exceed' class=pstat>".$nmle."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Output Limit Exceed:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Output+Limit+Exceed' class=pstat>".$nole."</a>";; ?></td>
		</tr>
		<tr>
		<td class=pstat>Restricted Function:</td><td class=pstat><?php echo "<a href='status.php?showpid=$pid&showres=Restricted+Function' class=pstat>".$nrf."</a>"; ?></td>
		</tr>
		<tr>
		<td class=pstat>Others:</td><td class=pstat><?php echo $not; ?></td>
		</tr>
	</table>
	</td>
	<td width=60%>
	<table width=100% class=pstat>
<?php
	$numq="select count(*) from (select * from status where result='Accepted' and pid=$pid group by username) status2";
	list($tot)=mysql_fetch_array(mysql_query($numq));
	$query="select count(*),runid,username,time_used,memory_used,language,length(source),time_submit from (select runid,username,time_used,memory_used,language,source,time_submit from status where result='Accepted' and pid=$pid order by time_used,memory_used,length(source),time_submit) status2 group by username order by time_used,memory_used,length(source) limit $start,$pstatuserperpage";
	echo "<caption>";
	$end=$start+$pstatuserperpage;
	if ($start!=0) {
		if ($start<$pstatuserperpage) echo "<a href='problem_stat.php?start=0&pid=$pid'> <strong>&lt;&lt;Previous</strong> </a>";
		else {
			$prev=$start-$pstatuserperpage;
			echo "<a href='problem_stat.php?start=$prev&pid=$pid'> <strong>&lt;&lt;Previous</strong> </a>";
		}
	}
	if ($end < $tot) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='problem_stat.php?start=$end&pid=$pid'><strong>Next&gt;&gt;</strong> </a>";
	}
	echo "</caption>";
?>
		<tr>
		<th class=pstat>Rank</th><th class=pstat>ACs</th><th class=pstat>Runid</th><th class=pstat>Username</th><th class=pstat>Time</th><th class=pstat>Memory</th><th class=pstat>Language</th><th class=pstat>Code Length</th><th class=pstat>Datetime</th>
<?php
	$rank=$start;
	$result=mysql_query($query);
	while ($row=mysql_fetch_row($result))
	{
		echo "<tr>";
		echo "<td class=pstatstand>".++$rank."</td>";
		echo "<td class=pstatstand><a class=pstat2 href='status.php?showpid=$pid&showres=Accepted&showname=$row[2]'>$row[0]</a></td>";
		if ($row[2]==$nowuser||db_user_isroot($nowuser)) echo "<td class=pstatstand><a class=pstat2 href='show_source.php?runid=$row[1]'>$row[1]</a></td>";
		else echo "<td class=pstatstand>$row[1]</td>";
		echo "<td class=pstatstand><a class=pstat href='userinfo.php?name=$row[2]'>$row[2]</a></td>";
		echo "<td class=pstatstand>$row[3] ms</td>";
		echo "<td class=pstatstand>$row[4] KB</td>";
		echo "<td class=pstatstand>".match_lang($row[5])."</td>";
		echo "<td class=pstatstand>$row[6] Bytes</td>";
		echo "<td class=pstatstand>$row[7]</td>";
		echo "</tr>";
	}
?>
		</tr>
	</table>
	</td>
	</tr>
</table>

</body>
</center>
<?php
}
include("footer.php");
?>
