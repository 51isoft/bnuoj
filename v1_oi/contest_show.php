<?php
	include("cheader.php");
	echo "<center>";
	$nowtime=time();
	if ( isset($_GET['cid']) ) $cid = $_GET['cid'];
	else {
?>
<?php include_once("menu.php"); ?>
<?php
		echo "<script>alert('Please pick up a contest.');";
		echo "window.location ='contest_list.php';</script>";
	}
	$query="select title,description,isprivate,start_time,end_time,unix_timestamp(start_time),unix_timestamp(end_time) from contest where cid='$cid'";
	$result=mysql_query($query);
	if (mysql_num_rows($result)!=1) {
?>
<?php include_once("menu.php"); ?>
<?php
		echo "<script>alert('Invalid Contest!');";
		echo "window.location ='contest_list.php';</script>";
	}
	$row=mysql_fetch_array($result);
	$fitimeu=$row[6];
	if ($row[2]=="1"&&(!db_user_match($nowuser, $nowpass)||!db_user_in_contest($cid,$nowuser))) {
?>
<?php include_once("cmenu.php"); ?>
<?php
		echo "<p class='warn'>Private contest, please login first.</p>";
	}
	else {
?>
<?php include("cmenu.php"); ?>
<?php
		echo "<p class='ctitle'>$row[0]</p>";
		echo "<p class='ctime'>Start Time: $row[3] &nbsp;&nbsp;&nbsp;&nbsp; End Time: $row[4] &nbsp;&nbsp;&nbsp;&nbsp; Status: ";
		if ($nowtime<$row[5]) {
			echo "<span class='cscheduled'>Not Started</span></p>";
			$diff = $row[5]-$nowtime;
			$diffhour  = (int)($diff/3600);
			$diffminute = (int)(($diff-$diffhour*3600)/60);
			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
			echo "<p class='ctime'>Current Time: ".date("Y-m-d H:i:s")." &nbsp;&nbsp;&nbsp;&nbsp; Countdown: $diffhour:$diffminute:$diffsecond</p>";
		}
		else if ($nowtime>$row[6]) {
			echo "<span class='cpassed'>Finished</span></p>";
		}
		else {
			$diff = $row[6]-$nowtime;
			$diffhour  = (int)($diff/3600);
			$diffminute = (int)(($diff-$diffhour*3600)/60);
			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
			echo "<span class='crunning'>Running</span></p>";
			echo "<p class='ctime'>Current Time: ".date("Y-m-d H:i:s")." &nbsp;&nbsp;&nbsp;&nbsp; Countdown: $diffhour:$diffminute:$diffsecond</p>";
		}
		if ($nowtime>=$row[5]) {
			echo "<table class='cshow' width=80%>";
			echo "<caption class='cshow'>$row[1]</caption>";
			echo "<tr>";
			echo "<th width='20%' class='cshow'> ID </th>";
			echo "<th width='50%' class='cshow'> Title </th>";
			echo "<th width='15%' class='cshow'> Flag </th>";
			echo "<th width='15%' class='cshow'> AC/Submit </th>";
			echo "</tr>";
			$cha = "SELECT pid,lable,cpid FROM contest_problem WHERE cid = '$cid'";
			$que = mysql_query($cha);
			while (  $go = mysql_fetch_array($que) ) {
				$cha2 ="SELECT title FROM problem WHERE pid = '$go[0]'";
				$que2 = mysql_query($cha2);
				list($title) = mysql_fetch_row($que2);
				$query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]'";
				$result = mysql_query($query);
				list($submitsum) = mysql_fetch_row($result);
				$query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and result='Accepted'";
				$result = mysql_query($query);
				list($acsum) = mysql_fetch_row($result);
                $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and result='Accepted' and username='$nowuser'";
                $result = mysql_query($query);
                list($userac) = mysql_fetch_row($result);
                $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and username='$nowuser'";
                $result = mysql_query($query);
                list($usersubmit) = mysql_fetch_row($result);
                $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and username='$nowuser' group by result";
                if ($userac>0) $flag='√';
                else if ($usersubmit>0) $flag='☓';
                else $flag='';
				echo "<tr>";
				echo "<td class='cshow'><a href=contest_problem_show.php?cpid=$go[2] class='list_link'>",$go[1],"</a></td>";
				echo "<td class='cshow'><a href=contest_problem_show.php?cpid=$go[2] class='list_link'>",$title," </a></td>";
				echo "<td class='cshow'> $flag </td>";
				echo "<td class='cshow'> $acsum/$submitsum </td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
	echo "</center>";
	include("footer.php");
?>
