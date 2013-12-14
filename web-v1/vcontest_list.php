<?php
    $pagetitle="Virtual Contest List";
	include("header.php");
	$nowt = time();
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
	if (isset($_GET['type'])) $type=$_GET['type'];
	else $type='running';
	$start=($page-1)*$conperpage;
?>
<center>
<a href="contest_list.php"><font size="+2">[Standard Contest]</font></a> <a href="vcontest_list.php"><font size="+2" color=red>[Virtual Contest]</font></a>
<table class='clist' width=98%>
<tr>
<td colspan=7><center>
<a href='vcontest_list.php?type=running'> <?php if ($type!='scheduled'&&$type!='passed') echo "<b>"; ?> Running<?php if ($type!='scheduled'&&$type!='passed') echo "</b>"; ?></a> | <a href='vcontest_list.php?type=scheduled'><?php if ($type=='scheduled') echo "<b>"; ?> Scheduled<?php if ($type=='scheduled') echo "</b>"; ?></a> | <a href='vcontest_list.php?type=passed'><?php if ($type=='passed') echo "<b>"; ?>Passed<?php if ($type=='passed') echo "</b>"; ?></a><br>
<a href='arrange_vcontest.php'>Arrange Your Own Contest</a>
</center></td>
</tr>
<?php
?>
<tr>
<th width='5%' class='clist'> ID </th>
<th width='40%' class='clist'> Title </th>
<th width='15%' class='clist'> Start Time </th>
<th width='15%' class='clist'> End Time </th>
<!--<th width='10%' class='clist'> Type </th>-->
<th width='10%' class='clist'> Status </th>
<th width='10%' class='clist'> Owner </th>
</tr>
<?php
	if ($type!='scheduled'&&$type!='passed') {
	$s = "SELECT cid,title,start_time,end_time,isprivate,owner FROM contest WHERE UNIX_TIMESTAMP( start_time ) <= $nowt and UNIX_TIMESTAMP( end_time ) >= $nowt and isvirtual=1 and type=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
//		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
//		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='crunning'>Running </span></th>";
		echo "<th> <a href='userinfo.php?name=$row[5]'>".$row['owner']." </a></th>";
		echo "</tr>\n";
	}
	echo "<caption class='rlist'>Running Virtual Contest List</caption>\n";
	}
	if ($type=='scheduled') {
	$s = "SELECT cid,title,start_time,end_time,isprivate,owner FROM contest WHERE UNIX_TIMESTAMP( start_time ) >$nowt and isvirtual=1 and type=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
//		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
//		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='cscheduled'>Scheduled </span></th>";
		echo "<th> <a href='userinfo.php?name=$row[5]'>".$row['owner']." </a></th>";
		echo "</tr>\n";
	}
	echo "<caption class='rlist'>Scheduled Virtual Contest List</caption>";
	}
	if ($type=='passed') {
	$s = "SELECT cid,title,start_time,end_time,isprivate,owner FROM contest WHERE UNIX_TIMESTAMP( end_time ) <= $nowt and isvirtual=1 and type=0 rder by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[0] </a> </th>";
		echo "<th><a href=contest_show.php?cid=$row[0]> $row[1] </a> </th>";
		echo "<th> $row[2] </th>";
		echo "<th> $row[3] </th>";
//		if ($row[4]=="0") echo "<th> <span class='cpublic'>Public</span></th>";
//		else echo "<th> <span class='cprivate'>Private</span></th>";
		echo "<th> <span class='cpassed'>Passed </span></th>";
		echo "<th> <a href='userinfo.php?name=$row[5]'>".$row['owner']." </a></th>";
		echo "</tr>\n";
	}
	echo "<caption class='rlist'>Passed Virtual Contest List</caption>";
	}
	echo "</center></table>";
	include("footer.php");
?>
