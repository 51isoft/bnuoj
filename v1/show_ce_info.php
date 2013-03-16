<?php
	$runid = $_GET['runid'];
	$cid = $_GET['cid'];
    $pagetitle="Show Compile Info of ".$runid;
	echo "<center>";
	if ($cid==""||$cid=="0") include("header.php");
	else {
		include("cheader.php");
		include("cmenu.php");
	}
	echo "<br><table width=98%>";
	$query="select ce_info from status where runid=$runid";
	$result = mysql_query($query);
	list($ceinfo)=mysql_fetch_row($result);
	$ceinfo=htmlspecialchars($ceinfo);
	echo "<td class='ceinfo'><pre>$ceinfo</pre></td>";
	echo "</table></center>";
	include("footer.php");
?>
