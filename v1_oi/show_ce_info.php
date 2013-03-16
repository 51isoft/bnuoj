<?php
	$runid = $_GET['runid'];
	$cid = $_GET['cid'];
    $pagetitle="查看RUNID为".$runid."的编译信息";
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
	$ceinfo=nl2br(htmlspecialchars($ceinfo));
	echo "<td class='ceinfo'>$ceinfo</td>";
	echo "</table></center>";
	include("footer.php");
?>
