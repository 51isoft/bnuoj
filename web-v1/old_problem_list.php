<?php

    if ( isset($_GET['page']) ) $page = $_GET['page'];
        else $page = 1;
    $pagetitle="Problem List Page ".$page;
    include("header.php");
	$start=($page-1)*$problemperpage;
	$sql = @mysql_query("select pid,title,total_ac,total_submit,isvirtual,vname,vid from problem where hide=0 order by pid limit $start,$problemperpage");
	echo "<center>";

echo "<form action='problem_search.php' method=get>";
echo "<table class='status'>";
echo "<tr><td class='status'><strong>Search:</strong></td><td class='status'>Problem ID：</td><td class='status'><input type='text' style='width:80px;height:24px;font:14px' name='searchpid'></td><td class='status'>Problem Name：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchpname'></td><td class='status'>Source：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchsource'></td><td class='status'>OJ Name:";
echo "<select size=1 name=searchvname style='font:14px' accesskey=l>
<option value='' selected>None</option>
<option value='PKU'>PKU</option>
</select>";
echo "</td><td class='status'>Problem ID on it:<input type='text' style='width:80px;height:24px;font:14px' name='searchvid'></td>";
echo "<td class='status'><input type='submit' size=10 value='Go'></td></tr>";
echo "</table>";
echo "</form>";

	echo "<table width=98% class='plist'>\n<tr>";
	if ($nowuser!="") echo "<th width='5%' class='plist'> Flag </th>";
	echo "<th width='15%' class='plist'> Problem ID </th>";
	echo "<th width='50%' class='plist'> Title </th>";
	echo "<th width='15%' class='plist'> Accepted </th>";
	echo "<th width='15%' class='plist'> Total </th>";
    echo "<th width='15%' class='plist'> OJID </th>";
	echo "</tr>\n";
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
        if ($nowuser!="") {
		    if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser' and result='Accepted'"))>0) echo "<td class='plist'> √ </td>";
		    else if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser'"))>0) echo "<td class='plist'> ☓ </td>";
		    else echo "<td class='plist'></td>";
        }
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[0] </a> </td>";
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[1] </a> </td>";
		echo "<td class='plist'> <a href=status.php?showpid=$row[0]&showres=Accepted class='list2_link'>$row[2]</a> </td>";
		echo "<td class='plist'> <a href=status.php?showpid=$row[0] class='list2_link'>$row[3]</a> </td>";
        if ($row[4]=="0") $ojid="BNU".$row[0];
        else $ojid=$row[5].$row[6];
        echo "<td class='plist'> $ojid </td>";
		echo "</tr>\n";	
	}
	$sql = @mysql_query("select count(*) from problem where hide=0");
	$row = @mysql_fetch_array($sql);
	$sum = $row[0];
	echo "<caption class='plist'>PAGE:";
	for ($i = 1; $i <= ($sum+$problemperpage-1)/$problemperpage; $i++) {
		if ($i!=$page) echo "<a href='problem_list.php?page=$i' class='list_link'>&nbsp;$i</a> ";
		else echo "<a href='problem_list.php?page=$i' class='list_link'><font color=red>&nbsp;$i</font></a> ";
	}
	echo "</caption></table>";
	echo "<table width=98% class=plist><caption class='plist'>PAGE:";
    for ($i = 1; $i <= ($sum+$problemperpage-1)/$problemperpage; $i++) {
        if ($i!=$page) echo "<a href='problem_list.php?page=$i' class='list_link'>&nbsp;$i</a> ";
        else echo "<a href='problem_list.php?page=$i' class='list_link'><font color=red>&nbsp;$i</font></a> ";
    }
    echo "</caption>\n</table>";
	echo "</center>";
	include("footer.php");
?>
