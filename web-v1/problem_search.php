<?php
	include("header.php");
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
	$pid=$_GET['searchpid'];
	$pname=$_GET['searchpname'];
	$source=$_GET['searchsource'];
	$vname=$_GET['searchvname'];
	$vid=$_GET['searchvid'];
	$start=($page-1)*$problemperpage;
	$query="select pid,title,total_ac,total_submit,source,isvirtual,vname,vid from problem where hide=0 ";
	if ($pid!="") $query=$query."and pid='$pid' ";
	if ($pname!="") $query=$query."and title like '%$pname%' ";
	if ($source!="") $query=$query."and source like '%$source%' ";
	if ($vname!="") $query=$query."and vname like '%$vname%' ";
	if ($vid!="") $query=$query."and vid='$vid' ";
	$sum = @mysql_num_rows(@mysql_query($query));
	$query=$query."order by pid limit $start,$problemperpage";
	$sql = @mysql_query($query);
	echo "<center>";

echo "<form action='problem_search.php' method=get>";
echo "<table class='status'>";
echo "<tr><td class='status'><strong>Search:</strong></td><td class='status'>Problem ID：</td><td class='status'><input type='text' style='width:80px;height:24px;font:14px' name='searchpid' value='$pid'></td><td class='status'>Problem Name：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchpname' value='$pname'></td><td class='status'>Source：</td><td class='status'><input type='text' style='width:200px;height:24px;font:14px' name='searchsource' value='$psource'></td><td class='status'>OJ Name:";
echo "<select size=1 name=searchvname style='font:14px' accesskey=l>
<option value='' selected>None</option>
<option value='PKU'>PKU</option>
<option value='CF'>CodeForces</option>
<option value='HDU'>HDU</option>
<option value='UVALive'>UVALive</option>
</select>";
echo "</td><td class='status'>Problem ID on it:<input type='text' style='width:80px;height:24px;font:14px' name='searchvid'></td>";
echo "<td class='status'><input type='submit' size=10 value='Go'></td></tr>";
echo "</table>";
echo "</form>";

	echo "<table width=98% class='plist'><tr>";
	if ($nowuser!="") echo "<th width=2%' class='plist'> Flag </th>";
	echo "<th width='6%' class='plist'> ID </th>";
	echo "<th width='50%' class='plist'> Title </th>";
	echo "<th width='30%' class='plist'> Source </th>";
	echo "<th width='6%' class='plist'> Ratio </th>";
	echo "<th width='6%' class='plist'> OJID </th>";
	echo "</tr>";
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
        if ($nowuser!="") {
		    if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser' and result='Accepted'"))>0) echo "<td class='plist'> √ </td>";
		    else if (mysql_num_rows(mysql_query("select * from status where pid=$row[0] and username='$nowuser'"))>0) echo "<td class='plist'> ☓ </td>";
		    else echo "<td class='plist'></td>";
        }
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[0] </a> </td>";
		echo "<td class='plist'> <a href=problem_show.php?pid=$row[0] class='list2_link'> $row[1] </a> </td>";
		echo "<td class='plist'> <a href=problem_search.php?searchsource=".str_replace(' ','+',$row[4])." class='list2_link'>$row[4]</a> </td>";
		echo "<td class='plist'> <a href=status.php?showpid=$row[0]&showres=Accepted class='list2_link'>$row[2]</a>/<a href=status.php?showpid=$row[0] class='list2_link'>$row[3]</a>  </td>";
        if ($row[5]=="0") $ojid="BNU".$row[0];
        else $ojid=$row[6].$row[7];
        echo "<td class='plist'> $ojid </td>";

		echo "</tr>";	
	}
	//$sql = @mysql_query("select count(*) from problem where hide=0");
	//$row = @mysql_fetch_array($sql);
	//$sum = $row[0];
	echo "<caption class='plist'>PAGE:";
//    echo $pname;
	for ($i = 1; $i <= ($sum+$problemperpage-1)/$problemperpage; $i++) {
		echo "<a href='problem_search.php?page=$i&searchpid=$pid&searchpname=$pname&searchvname=$vname&searchvid=$vid&searchsource=".str_replace(' ','+',$source)."' class='list_link'>&nbsp;$i</a> ";
	}
	echo "</caption></table></center>";
	include("footer.php");
?>
