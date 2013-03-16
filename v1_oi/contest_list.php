<?php
	include("header.php");
	$nowt = time();
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
	$start=($page-1)*$conperpage;
?>
<center><table class='clist' width=98%>
<tr>
<th width='10%' class='clist'> 编号 </th>
<th width='35%' class='clist'> 名称 </th>
<th width='15%' class='clist'> 开始时间 </th>
<th width='15%' class='clist'> 结束时间 </th>
<th width='10%' class='clist'> 类型 </th>
<th width='15%' class='clist'> 状态 </th>
</tr>
<?php
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) <= $nowt and UNIX_TIMESTAMP( end_time ) >= $nowt order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>公开赛</span></th>";
		else echo "<th> <span class='cprivate'>内部赛</span></th>";
		echo "<th> <span class='crunning'>正在进行 </span></th>";
		echo "</tr>";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) >$nowt order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>公开赛</span></th>";
		else echo "<th> <span class='cprivate'>内部赛</span></th>";
		echo "<th> <span class='cscheduled'>未开始 </span></th>";
		echo "</tr>";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( end_time ) <= $nowt order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>公开赛</span></th>";
		else echo "<th> <span class='cprivate'>内部赛</span></th>";
		echo "<th> <span class='cpassed'>已结束 </span></th>";
		echo "</tr>";
	}
	echo "</center></table>";
	include("footer.php");
?>
