<?php
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
    $pagetitle="题目列表第 ".$page." 页";
    include("header.php");
	$start=($page-1)*$problemperpage;
	$sql = @mysql_query("select pid,title,total_ac,total_submit from problem where hide=0 order by pid limit $start,$problemperpage");
	echo "<center>";

echo "<form action='problem_search.php' method=get>";
echo "<table class='status'>";
echo "<tr><td class='status'><strong>查找:</strong></td><td class='status'>题号 ：</td><td class='status'><input type='text' style='width:80px;height:24px;font:14px' name='searchpid'></td><td class='status'>题目名称：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchpname'></td><td class='status'>来源：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchsource'></td>";
echo "";
echo "<td class='status'><input type='submit' size=10 value='Go'></td></tr>";
echo "</table>";
echo "</form>";

	echo "<table width=98% class='plist'><tr>";
	if ($nowuser!="") echo "<th width='15%' class='plist'> 做出标记 </th>";
	echo "<th width='15%' class='plist'> 题号 </th>";
	echo "<th width='40%' class='plist'> 题目名称 </th>";
	echo "<th width='15%' class='plist'> 做对次数 </th>";
	echo "<th width='15%' class='plist'> 提交总数 </th>";
	echo "</tr>";
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
        if ($nowuser!="") {
		    if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser' and result like 'Accepted%'"))>0) echo "<td class='plist'> √ </td>";
		    else if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser'"))>0) echo "<td class='plist'> ☓ </td>";
		    else echo "<td class='plist'></td>";
        }
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[0] </a> </td>";
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[1] </a> </td>";
		echo "<td class='plist'> <a href=status.php?showpid=$row[0]&showres=Accepted class='list2_link'>$row[2]</a> </td>";
		echo "<td class='plist'> <a href=status.php?showpid=$row[0] class='list2_link'>$row[3]</a> </td>";
		echo "</tr>";	
	}
	$sql = @mysql_query("select count(*) from problem where hide=0");
	$row = @mysql_fetch_array($sql);
	$sum = $row[0];
	echo "<caption class='plist'>PAGE:";
	for ($i = 1; $i <= ($sum+$problemperpage-1)/$problemperpage; $i++) {
		if ($i!=$page) echo "<a href='problem_list.php?page=$i' class='list_link'>&nbsp;$i</a> ";
		else echo "<a href='problem_list.php?page=$i' class='list_link'><font color=red>&nbsp;$i</font></a> ";
	}
	echo "</caption></table></center>";
	include("footer.php");
?>
