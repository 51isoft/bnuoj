<?php
    include_once("conn.php");
	$cid = $_GET['cid'];
	$nowtime=time();
	$scr = "contest_standing_".$cid.".html";
	if( !file_exists($scr) ){
		$t = 0;
	}
	else{
		$t = filectime($scr);
	}


	$pastsec=$nowtime-$t;
	if ($pastsec<$srefresh)//60秒更新一次
       {
       Header("Location: contest_standing_".$cid.".html");
	}
else{
	ob_start(); //打开缓冲区


	include("cheader.php");
	echo "<center>";
	include("cmenu.php");
	echo "<table width=98% class='standing'>";
	list($locktu,$sttimeu,$fitimeu) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time) FROM contest WHERE cid = '$cid'"));
	$nowtime=time();
	if ($locktu==0) $locktu=$fitimeu+1;
	if ($nowtime<$sttimeu+$srefresh) {
		echo "<caption class='standing'>Not Started</caption>";
	}
	else {
		if ($nowtime>$fitimeu) {
			if ($nowtime>$fitimeu+300 && $pastsec>$srefresh && $t!=0){
			echo "<script>window.location ='contest_standing_$cid.html';</script>";
			exit;
			}
			echo "<caption class='standing'>Contest Finished</caption>";
		}
		else if ($nowtime>$locktu ) {
			if ($nowtime>$locktu && $pastsec>$srefresh && $t!=0){
			echo "<script>window.location ='contest_standing_$cid.html';</script>";
			exit;
			}
			echo "<caption class='standing'>Board Locked</caption>";
		}
		else echo "<caption class='standing'>Contest Running</caption>";
		$sql = "select count(*) from contest_problem where cid = '$cid'";
		$que = mysql_query($sql);
		$num = mysql_fetch_array($que);
		$n =$num['count(*)'] + 4;
		$ro = 100/$n;
		$sql = "select lable,cpid,pid from contest_problem where cid = '$cid'";
		$que = mysql_query($sql);
		$sql_st = "SELECT unix_timestamp(start_time) FROM contest WHERE cid = '$cid'";
		$q_st = mysql_query($sql_st);
		$res_st = mysql_fetch_array($q_st);
		echo "<tr>";
		echo "<th width='$ro%' class='standing'> Rank </th>";
		echo "<th width='$ro%' class='standing'> Nickname </th>";
		echo "<th width='$ro%' class='standing'> Accepts</th>";
		for($j = 0,$i =0 ; $i <$n-4 ;$i++,$j++) {
			$res = mysql_fetch_array($que);
			echo "<th width='$ro%' class='standing'> <a href='../contest_problem_show.php?cpid=$res[1]' class='standing'>$res[0]</a> </th>";
			$pid[$j] = $res[2];
		}
		echo "<th width='$ro%' class='standing'> Penalty</th>";
		echo "</tr>";
		list($startt,$endt,$lockt,$ctype)=@mysql_fetch_row(mysql_query("select start_time,end_time,lock_board_time,isprivate from contest where cid=$cid"));
		if ($ctype==true) $sql ="select username from contest_user where cid=$cid";
		else $sql ="select distinct username from status where contest_belong=$cid";
		$que = mysql_query($sql);
		$mark = 0;

//		echo microtime()."<br/>";

		while($rows = @mysql_fetch_array($que)) {
			$board[$mark][0] = $rows[0];
			$rri = "Accepted";
			for($i = 0 ; $i<$n-4 ;$i++){
				if($board[$mark][3*$i+5] == ""){
					$board[$mark][3*$i+5] = 10000000;
				}
				if ($lockt=='0000-00-00 00:00:00') $sql_ac ="select UNIX_TIMESTAMP(time_submit),runid from status where time_submit<='$endt' and time_submit>='$startt' and contest_belong=$cid and username='$rows[0]' and pid=$pid[$i] and result='$rri' and runid <=' ".$board[$mark][3*$i+5]."'";
				else $sql_ac = "select UNIX_TIMESTAMP(time_submit),runid from status where time_submit<='$lockt' and time_submit>='$startt' and contest_belong=$cid and username='$rows[0]' and pid=$pid[$i] and result='$rri' and runid <=' ".$board[$mark][3*$i+5]."'";
				$q_ac = mysql_query($sql_ac);
				$row_ac = mysql_fetch_array($q_ac);
				$board[$mark][3*$i+3] = $row_ac[0];
				if($row_ac[1] != ""){
					$board[$mark][3*$i+5] = $row_ac[1];
				}
				if ($lockt=='0000-00-00 00:00:00') $sql_nac ="select count(*) from status where time_submit<='$endt' and time_submit>='$startt' and contest_belong=$cid and username='$rows[0]' and pid=$pid[$i] and result!='$rri' and runid <= ' ".$board[$mark][3*$i+5]."'";
				else $sql_nac ="select count(*) from status where time_submit<='$lockt' and time_submit>='$startt' and contest_belong=$cid and username='$rows[0]' and pid=$pid[$i] and result!='$rri' and runid <= ' ".$board[$mark][3*$i+5]."'";
				$q_nac = mysql_query($sql_nac);
				$row_nac = mysql_fetch_array($q_nac);
				$board[$mark][3*$i+4] = $row_nac[0];
			}
			for($i = 0 ; $i<$n-4 ;$i++){
				if($board[$mark][3*$i+5]!= 10000000){
					$board[$mark][2]++;
					$board[$mark][1] += $board[$mark][3*$i+3]+20*60*$board[$mark][3*$i+4]-$res_st[0];
				}
			}
			$mark++;
		}

//		echo microtime()."<br/>";
		$flag_num = $mark;

		for($i  = 0;$i < $flag_num;$i++){
			for($j  = $i;$j < $flag_num;$j++){
				if(so_cmp($board[$i][2],$board[$j][2],$board[$i][1],$board[$j][1]) ){
					$temp = $board[$i];
					$board[$i] = $board[$j];
					$board[$j] = $temp;
				}
			}
		}


//	echo microtime()."<br/>";

		for($mark  = 0;$mark < $flag_num;$mark++) {

		$sql_nickname ="select nickname from user where username = '".$board[$mark][0]."'";
			$que_nickname = mysql_query($sql_nickname);
			$arr_nickname = mysql_fetch_array($que_nickname);
			if(strlen($arr_nickname[0])>=50){
				$arr_nickname[0] = substr($arr_nickname[0],0,50)."...";
			}
			else if(strlen($arr_nickname[0])==0){
				$arr_nickname[0] = "这家伙很懒，什么都没有留下";
			}

			echo "<tr>";
			echo "<td width='$ro%' class='standing'> ".($mark+1)." </td>";
		echo "<td width='$ro%' class='standing'><a href='../userinfo.php?name=".$board[$mark][0]."'>".$arr_nickname[0] ."</a></td>";
		//	echo "<td width='$ro%' class='standing'><a href='../userinfo.php?name=".$board[$mark][0]."'>".$board[$mark][0] ."</a></td>";
			if($board[$mark][2]==""){$board[$mark][2]= 0;}
			echo "<td width='$ro%' class='standing'>". $board[$mark][2]."</td>";
			for($i =0 ; $i <$n-4 ;$i++){
				if($board[$mark][3*$i+3] == 0) {
					if($board[$mark][3*$i+4]!=0)
					echo "<td width='$ro%' class='standing'>"."(".$board[$mark][3*$i+4].")"."</td>";
					else{
						echo "<td width='$ro%' class='standing'>"."<br/>"."</td>";
					}
				}
				else{
					echo "<td width='$ro%' class='standing'>".sc($board[$mark][3*$i+3],$res_st[0])."(".$board[$mark][3*$i+4].")"."</td>";
				}
			}
			echo "<td width='$ro%' class='standing'>".sc($board[$mark][1],0)."</td>";
			echo "</tr>";
		}
	}
//	echo microtime()."<br/>";
	echo "</table></center>";
	include("footer.php");

$content=ob_get_contents(); //得到缓冲区的内容
if (!function_exists("file_put_contents"))
{
function file_put_contents($fn,$fs)
   {
   $fp=fopen($fn,"w+");
   fputs($fp,$fs);
   fclose($fp);
}
}
file_put_contents("contest_standing_".$cid.".html",$content);
}
?>
