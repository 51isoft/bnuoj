<?php
    $pagetitle="Standard Contest List";
	include("header.php");
	$nowt = time();
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
	$start=($page-1)*$conperpage;
?>
<center>
<a href="contest_list.php"><font size="+2" color=red>[Standard Contest]</font></a> <a href="vcontest_list.php"><font size="+2">[Virtual Contest]</font></a>
<table class='clist' width=98%>
<tr>
<th width='5%' class='clist'> ID </th>
<th width='45%' class='clist'> Title </th>
<th width='15%' class='clist'> Start Time </th>
<th width='15%' class='clist'> End Time </th>
<th width='10%' class='clist'> Type </th>
<th width='10%' class='clist'> Status </th>
</tr>
<?php
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) <= $nowt and UNIX_TIMESTAMP( end_time ) >= $nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='crunning'>Running </span></th>";
		echo "</tr>\n";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) >$nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='cscheduled'>Scheduled </span></th>";
		echo "</tr>\n";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( end_time ) <= $nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='cpassed'>Passed </span></th>";
		echo "</tr>\n";
	}
	echo "</center></table>";
	include("footer.php");
?>
