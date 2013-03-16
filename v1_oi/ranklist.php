<?php
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
    $pagetitle="排名第 ".$page." 页";
    include("header.php");
	$start=($page-1)*$userperpage;
	$sql = mysql_query("select username,nickname,total_ac,total_submit from ranklist limit $start,$userperpage");
	echo "<center><table width=98% class='rlist'><tr>";
	echo "<th width='10%' class='rlist'> 排名 </th>";
	echo "<th width='20%' class='rlist'> 用户名 </th>";
	echo "<th width='40%' class='rlist'> 昵称 </th>";
	echo "<th width='15%' class='rlist'> 做对数 </th>";
	echo "<th width='15%' class='rlist'> 提交数 </th>";
	echo "</tr>";

	$rank = ($page-1)*$userperpage+1;

	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<td class='rlist'> $rank </td>";
		echo "<td class='rlist'> <a href=userinfo.php?name=$row[0] class='list2_link'> $row[0] </a> </td>";
		//$row[1] = change_out($row[1]);
		echo "<td class='rlist'> $row[1] </td>";
		echo "<td class='rlist'> <a href=status.php?showname=$row[0]&showres=Accepted class='list2_link'>$row[2]</a> </td>";
		echo "<td class='rlist'> <a href=status.php?showname=$row[0] class='list2_link'>$row[3]</a> </td>";
		echo "</tr>";
		$rank++;
	}
	$sql = @mysql_query("select count(*) from user");
	$row = @mysql_fetch_array($sql);
	$sum = $row[0];
	echo "<caption class='rlist'>";
	for ($i = 1; $i <= ($sum+$userperpage-1)/$userperpage; $i++) {
		$si=($i-1)*$userperpage+1;
		$ti=$si+$userperpage-1;
		echo "<a href='ranklist.php?page=$i' class='list_link'>&nbsp;$si-$ti</a> ";
	}
	echo "</caption></table></center>";
	echo "</table>";
	include("footer.php");
?>
