<?php
    $cid = $_GET['cid'];
    $pagetitle="Clarification of Contest ".$cid;
	include("cheader.php");
	echo "<center>";
	$nowtime=time();
	if ( isset($_GET['cid']) ) $cid = $_GET['cid'];
	else {
?>
<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>Home </a></th>
<th width="14%" class='menu'><a href='ranklist.php' class='menu'>Ranklist </a></th>
<th width="14%" class='menu'><a href='status.php' class='menu'>Status </a></th>
<th width="14%" class='menu'><a href='problem_list.php' class='menu'>Problem </a></th>
<th width="14%" class='menu'><a href='contest_list.php' class='menu'>Contest </a></th>
<th width="14%" class='menu'><a href='submit.php' class='menu'>Submit </a></th>
<th width="14%" class='menu'>Clarify </th>
</tr>
</table>
<?php
		echo "<script>alert('Please pick up a contest.');";
		echo "window.location ='contest_list.php';</script>";
	}
	$query="select title,description,isprivate,start_time,end_time,unix_timestamp(start_time),unix_timestamp(end_time) from contest where cid='$cid'";
	$result=mysql_query($query);
	if (mysql_num_rows($result)!=1) {
?>
<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>Home </a></th>
<th width="14%" class='menu'><a href='ranklist.php' class='menu'>Ranklist </a></th>
<th width="14%" class='menu'><a href='status.php' class='menu'>Status </a></th>
<th width="14%" class='menu'><a href='problem_list.php' class='menu'>Problem </a></th>
<th width="14%" class='menu'><a href='contest_list.php' class='menu'>Contest </a></th>
<th width="14%" class='menu'><a href='submit.php' class='menu'>Submit </a></th>
<th width="14%" class='menu'>Clarify </th>
</tr>
</table>
<?php
		echo "<script>alert('Invalid Contest!');";
		echo "window.location ='contest_list.php';</script>";
	}
	$row=mysql_fetch_array($result);
	if ($row[2]=="1"&&(!db_user_match($nowuser, $nowpass)||!db_user_in_contest($cid,$nowuser))) {
?>
<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>Home </a></th>
<th width="14%" class='menu'><a href='ranklist.php' class='menu'>Ranklist </a></th>
<th width="14%" class='menu'><a href='status.php' class='menu'>Status </a></th>
<th width="14%" class='menu'><a href='problem_list.php' class='menu'>Problem </a></th>
<th width="14%" class='menu'><a href='contest_list.php' class='menu'>Contest </a></th>
<th width="14%" class='menu'><a href='submit.php' class='menu'>Submit </a></th>
<th width="14%" class='menu'>Clarify </th>
</tr>
</table>
<?php
		echo "<p class='warn'>Private contest, please login first.</p>";
	}
	else {
?>
<?php include("cmenu.php"); ?>
<?php

		if (!db_user_match($nowuser, $nowpass)) echo "<span class='warn'>Please login first.</span>";
		else {
			$query="select question,reply,ispublic from contest_clarify where cid='$cid' and (username='$nowuser' or ispublic=1) order by ccid desc";
			$result=mysql_query($query);
			echo "<table width=60%>";
			while ($row=@mysql_fetch_row($result)) {
				if ($row[2]=='0') echo "<tr><th>Private Message</th></tr>";
				else  echo "<tr><th>Public Message</th></tr>";
				echo "<tr><td><pre class='discuss'>Q: $row[0]</pre></td></tr>";
				echo "<tr><td><pre class='discuss'>A: $row[1]</pre></td></tr>";
			}
			echo "</table>";
		}
		echo "<form id='question1' name='question1' method='post' action='contest_clarify_post.php?cid=$cid'><table>";
		echo '<th>Question:</th><tr><td colspan="5" class="question1">';
	        echo '<textarea name="question" cols=60 rows=10></textarea>';
        	echo '</td></tr><td><center><input type="submit" name="Submit" value="Post" ></center></td></table></form>';

	}
	echo "</center>";
include("footer.php");
?>
