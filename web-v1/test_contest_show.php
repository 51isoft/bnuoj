<?php
    $cid = $_GET['cid'];
    //$pagetitle = "Contest ".$cid;
	include("conn.php");
	//echo "<center>";
	$nowtime=time();
	if ( isset($_GET['cid']) ) $cid = $_GET['cid'];
	else {
?>
<?php include("cheader.php");
echo "<center>";
include_once("menu.php"); ?>
<?php
		echo "<script>alert('Please pick up a contest.');";
		echo "window.location ='contest_list.php';</script>";
	}
	$query="select title,description,isprivate,start_time,end_time,unix_timestamp(start_time),unix_timestamp(end_time),isvirtual,owner,report from contest where cid='$cid'";
	$result=mysql_query($query);
	if (mysql_num_rows($result)!=1) {
?>
<?
include("cheader.php");
echo "<center>";
include_once("menu.php"); ?>
<?php
		echo "<script>alert('Invalid Contest!');";
		echo "window.location ='contest_list.php';</script>";
	}
	$row=mysql_fetch_array($result);
    $pagetitle=strip_tags($row[0]);
    include("cheader.php");
    echo "<center>";
	$fitimeu=$row[6];
	if ($row[2]=="1"&&(!db_user_match($nowuser, $nowpass)||!db_user_in_contest($cid,$nowuser))) {
?>
<?php
include_once("cmenu.php"); ?>
<?php
		echo "<p class='warn'>Private contest, please login first.</p>";
	}
	else {
?>
<?php
include("cmenu.php"); ?>


<?php
		echo "<p class='ctitle'>$row[0]</p>";
		echo "<p class='ctime'>Start Time: $row[3] &nbsp;&nbsp;&nbsp;&nbsp; End Time: $row[4] &nbsp;&nbsp;&nbsp;&nbsp; Status: ";
?>
<script type="text/javascript">

var currenttime = '<? print date("Y-m-d H:i:s",time()); ?>' //PHP method of getting server date

var serverdate=new Date(currenttime);
var cnt=<? 
if ($nowtime<$row[5]) {
    echo $row[5]-$nowtime;
}
else if ($nowtime>$row[6]) {
    echo $nowtime-$row[6];
}
else echo $row[6]-$nowtime;
?>;
var stp=<?
if ($nowtime<$row[5]) {
    echo "-1";
}
else if ($nowtime>$row[6]) {
    echo "1";
}
else echo "-1";
?>;

function padlength(what){
    var output=(what.toString().length==1)? "0"+what : what;
    return output;
}

function displaytime(){
    serverdate.setSeconds(serverdate.getSeconds()+1);
    var datestring=serverdate.getFullYear()+"-"+padlength(serverdate.getMonth()+1)+"-"+padlength(serverdate.getDate());
    var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
    document.getElementById("servertime").innerHTML=datestring+" "+timestring;
}

function displaycountdown(){
    cnt+=stp;
    if (cnt<0) cnt=0;
    var dh=Math.floor(cnt/3600);
    var dm=Math.floor((cnt-dh*3600)/60);
    var ds=cnt-dh*3600-dm*60;
    var timestring=dh+":"+dm+":"+ds;
    document.getElementById("counttime").innerHTML=timestring;
}

window.onload=function(){
    setInterval("displaytime()", 1000);
    setInterval("displaycountdown()", 1000);
}

</script>
<?
		if ($nowtime<$row[5]) {
			echo "<span class='cscheduled'>Not Started</span></p>";
			$diff = $row[5]-$nowtime;
			$diffhour  = (int)($diff/3600);
			$diffminute = (int)(($diff-$diffhour*3600)/60);
			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
			echo "<p class='ctime'>Current Time: <span id=\"servertime\">".date("Y-m-d H:i:s")."</span> &nbsp;&nbsp;&nbsp;&nbsp; Countdown: <span id=counttime>$diffhour:$diffminute:$diffsecond</span></p>";
			if (db_user_match($nowuser,$nowpass)&&$row['isvirtual']==1&&($row['owner']==$nowuser||db_user_isroot($nowuser))) echo "<a href=delete_vcontest.php?cid=$cid class='bottom_link'> [Delete] </a>"; 
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
			echo "<p class='ctime'>Current Time: <span id=\"servertime\">".date("Y-m-d H:i:s")."</span> &nbsp;&nbsp;&nbsp;&nbsp; Countdown: <span id=counttime>$diffhour:$diffminute:$diffsecond</span></p>";
		}
		if ($nowtime>=$row[5]||(db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser))) {
            if ($nowtime<$row[5]) echo "<center><b>List Now Visible to Admin Only!! Be careful!</b></center>";
			echo "<table class='cshow' width=80%>";
			echo "<caption class='cshow'>".change_out($row[1])."</caption>";
			echo "<tr>";
			echo "<th width='20%' class='cshow'> ID </th>";
			echo "<th width='50%' class='cshow'> Title </th>";
			echo "<th width='15%' class='cshow'> Flag </th>";
			echo "<th width='15%' class='cshow'> Ratio </th>";
			echo "<th width='15%' class='cshow'> Ratio(User) </th>";
			echo "</tr>";
			$cha = "SELECT pid,lable,cpid FROM contest_problem WHERE cid = '$cid' order by lable asc";
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

				$query = "select count(distinct username) from status where contest_belong='$cid' and pid='$go[0]'";
				$result = mysql_query($query);
				list($submitsumuser) = mysql_fetch_row($result);
				$query = "select count(distinct username) from status where contest_belong='$cid' and pid='$go[0]' and result='Accepted'";
				$result = mysql_query($query);
				list($acsumuser) = mysql_fetch_row($result);

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
				echo "<td class='cshow'> $acsumuser/$submitsumuser </td>";
				echo "</tr>";
			}
			echo "</table>\n";
			if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
				echo "<a href=admin_contest_report_modify.php?cid=$cid class='bottom_link'> [Edit Report] </a>\n";
			}
			if ($nowtime>$row[6]&&strlen($row['report'])>20) echo "<table width=60%><tr><th>Contest Report</th></tr><tr><td>".$row['report']."</td></tr></table>\n";
		}
	}
	echo "</center>";
	include("footer.php");
?>
