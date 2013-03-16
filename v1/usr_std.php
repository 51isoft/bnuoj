<?php
    $cid=$_GET['cid'];
    $pagetitle="Standing Of Contest $cid";
	include("csheader.php");
    echo "<center>";
	include("cmenu.php");
	if (!db_contest_exist($cid)) {
		
	}
	/*else if (!db_contest_running($cid)) {

	}*/
	else {
		function covert_time($timestamp)
		{
			$rtime['sec']=$timestamp%60;
			$timestamp/=60;
			$rtime['min']=$timestamp%60;
			$timestamp/=60;
			$rtime['hour']=(int)$timestamp;
			$rtimes=$rtime['hour'].":".$rtime['min'].":".$rtime['sec'];
			return $rtimes;
		}
		$nowt=time();
		list($startt,$endt,$lockt)=mysql_fetch_row(mysql_query("select unix_timestamp(start_time),unix_timestamp(end_time),unix_timestamp(lock_board_time) from contest where cid=$cid"));
		if ($lockt<$startt||$nowt>$lockt) $lockt=$endt;
		$probque="select pid from contest_problem where cid=$cid order by lable asc";
		$probres=mysql_query($probque);
		$probnum=0;
		$standque="select * from (select distinct username from status where contest_belong=$cid and unix_timestamp(time_submit)<$lockt) stn";
		//echo $standque."<br>";
		while ($probrow=@mysql_fetch_array($probres)) {
			$probs[$probnum++]=$probrow[0];
			//echo $probrow[0]."<br>";
			$probnum2=$probnum*2;
			$probnum21=$probnum*2-1;
			$tstring=" left join (select username,min(unix_timestamp(time_submit)) timep$probnum from status where contest_belong=$cid and pid=$probrow[0] and result='Accepted' and unix_timestamp(time_submit)<$lockt group by username) st$probnum on stn.username=st$probnum.username left join (select username,count(*) penaltyp$probnum from status temp$probnum21 where contest_belong=$cid and unix_timestamp(time_submit)<$lockt and pid=$probrow[0] and result!='Accepted' and ( 0=any(select count(time_submit) from status temp$probnum2 where temp$probnum2.username=temp$probnum21.username and contest_belong=$cid and unix_timestamp(time_submit)<$lockt and pid=$probrow[0] and result='Accepted') or unix_timestamp(time_submit)<any(select min(unix_timestamp(time_submit)) from status temp$probnum2 where temp$probnum2.username=temp$probnum21.username and contest_belong=$cid and unix_timestamp(time_submit)<$lockt and pid=$probrow[0] and result='Accepted') ) group by username) pn$probnum on stn.username=pn$probnum.username";
			//echo $tstring."<br>";
			$standque=$standque.$tstring;
			//echo $standque."<br>";
		}
		echo str_replace("<","&lt;",$standque)."<br>";
		$standres=mysql_query($standque);
		$boardnum=0;
		while ($standrow=mysql_fetch_array($standres)) {
			$board[$boardnum]['username']=$standrow[0];
			$board[$boardnum]['totaltime']=0;
			$board[$boardnum]['totalac']=0;
			for ($i=2;$i<4*$probnum+1;$i+=4) {
				$id=($i-2)/4+1;
				if ($standrow[$i]!="") {
					$board[$boardnum]['totalac']++;
					$board[$boardnum]['timep'.$id]=$standrow[$i]-$startt;
				}
				else $board[$boardnum]['timep'.$id]=$standrow[$i];
				if ($standrow[$i+2]=="") $standrow[$i+2]=0;
				$board[$boardnum]['penp'.$id]=$standrow[$i+2];
				if ($standrow[$i]!="") $board[$boardnum]['totaltime']+=$standrow[$i]-$startt+$standrow[$i+2]*20*60;
			}
			$boardnum++;
		}
		for ($i=0;$i<$boardnum-1;$i++)
			for ($j=$i+1;$j<$boardnum;$j++)
				if ($board[$i]['totalac']<$board[$j]['totalac']||($board[$i]['totalac']==$board[$j]['totalac']&&$board[$i]['totaltime']>$board[$j]['totaltime'])) {
					$swapt=$board[$i];$board[$i]=$board[$j];$board[$j]=$swapt;
				}
		echo "<table width=98% class=standing>";
		if ($nowt>$endt) {
			echo "<caption class='standing'>Contest Finished</caption>";
		}
		else if ($nowt>$lockt) {
			echo "<caption class='standing'>Board Locked</caption>";
		}
		else echo "<caption class='standing'>Contest Running</caption>";
		echo "<th class=standing width=". 100/($probnum+4) ."%>Rank</th>";
		echo "<th class=standing width=". 100/($probnum+4) ."%>Nickname</th>";
		echo "<th class=standing width=". 100/($probnum+4) ."%>Accepts</th>";
		$probque="select lable,cpid from contest_problem where cid=$cid";
		$probres=mysql_query($probque);
		while ($probrow=@mysql_fetch_array($probres)) {
			echo "<th class=standing width=". 100/($probnum+4) ."%><a class=standing href=contest_problem_show.php?cpid=$probrow[1]>$probrow[0]</a></th>";
		}
		echo "<th class=standing width=". 100/($probnum+4) ."%>Penalty</th>";
		for ($i=0;$i<$boardnum;$i++) {
			echo "<tr>";
			$rank=$i+1;
			echo "<td class=standing width=". 100/($probnum+4) ."%>$rank</td>";
			list($board[$i]['nickname'])=mysql_fetch_array(mysql_query("select nickname from user where username='".$board[$i]['username']."'"));
			echo "<td class=standing width=". 100/($probnum+4) ."%><a href=userinfo.php?name=".$board[$i]['username'].">".$board[$i]['username']."</a></td>";
			echo "<td class=standing width=". 100/($probnum+4) ."%>".$board[$i]['totalac']."</td>";
			for ($j=1;$j<=$probnum;$j++) {
				if ($board[$i]['timep'.$j]==""&&$board[$i]['penp'.$j]!=0) echo "<td class=standing width=". 100/($probnum+4)."%>(-".$board[$i]['penp'.$j].")</td>";
				else if ($board[$i]['timep'.$j]==""&&$board[$i]['penp'.$j]==0) echo "<td class=standing width=". 100/($probnum+4)."%></td>";
				else {
					$showt=covert_time($board[$i]['timep'.$j]);
					echo "<td class=standing width=". 100/($probnum+4)."%>".$showt."(".$board[$i]['penp'.$j].")</td>";
				}
			}
			echo "<td class=standing width=". 100/($probnum+4)."%>".covert_time($board[$i]['totaltime'])."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	echo "</center>";
	include("footer.php");
?>
