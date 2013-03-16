<?php $pagetitle="在线提交记录";include("header.php"); ?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function hide(id) {
	 tmp_u = id;
	 if (document.getElementById(tmp_u).style.display != "none") 
	 { 
		 document.getElementById(tmp_u).style.display = "none"; //document.getElementById(tmp_i).src='images/op.gif'; 
	 } else { 
		 document.getElementById(tmp_u).style.display = ""; //document.getElementById(tmp_i).src='images/om.gif'; 
	 } 
	}

	function getResult(runid, s) {	
		pos = s.indexOf(':');
		leftStr = s.substr(0, pos);
		rightStr = s.substr(pos+1);		
		leftStr = leftStr.replace("Accepted|", "<span class='ac'>通过 </span> ");
		leftStr = leftStr.replace("Unaccepted|", "<span class='wa'>未通过 </span>");

	    ret = "<TD onClick=hide('"+runid+"')><center><strong>"+leftStr + "分<div id="+runid+" style=\"display:none\">";
		// KB, MS


		rightStr = rightStr.replace(/_/g, "KB ");
		rightStr = rightStr.replace(/,/g, "MS <br>");
		rightStr = rightStr.replace(/\|/g, " ");
		rightStr = rightStr.replace(/AC/g, "*<span class='ac'>答案正确</span>");
		rightStr = rightStr.replace(/RE/g, "*<span class='re'>运行错误</span>");
		rightStr = rightStr.replace(/CE/g, "*<span class='ce'>编译错误</span>");
		rightStr = rightStr.replace(/WA/g, "*<span class='wa'>答案错误</span>");
		rightStr = rightStr.replace(/PE/g, "*<span class='pe'>格式错误</span>");
		rightStr = rightStr.replace(/TLE/g, "*<span class='tle'>运行超时</span>");
		rightStr = rightStr.replace(/MLE/g, "*<span class='mle'>内存超限</span>");
		rightStr = rightStr.replace(/OLE/g, "*<span class='ole'>输出超限</span>");
		rightStr = rightStr.replace(/RF/g,  "*<span class='rf'>函数受限</span>");
		for (i=0; i<40; ++i)
			rightStr = rightStr.replace("*", "测试点"+(i+1)+": ");
		ret += rightStr + "</center></strong></td>";
		//alert(ret);
		document.write(ret);
    }
//-->
</SCRIPT>
<?php
echo "<center>";
if ( isset($_GET['start']) ) $start = $_GET['start'];
else $start = "0";
if ( isset($_GET['showname']) ) $showname = $_GET['showname'];
else $showname = "";
if ( isset($_GET['showpid']) ) $showpid = $_GET['showpid'];
else $showpid = "";
if ( isset($_GET['showres']) ) $showres = $_GET['showres'];
else $showres = "";
if ( isset($_GET['showlang']) ) $showlang = $_GET['showlang'];
else $showlang = "";
/*if ( isset($_GET['only']) ) $only = $_GET['only'];
else $only = "0";
if ($only=="0") $query = "select count(runid) from status where contest_belong=0";
else $query = "select count(runid) from status where contest_belong=0 and username='$nowuser'";*/
$query = "select count(runid) from status where (contest_belong=0 or contest_belong=any(select cid from contest where end_time<now()))  ";
if ($showname!="") $query=$query."and username='$showname' ";
if ($showpid!="") $query=$query."and pid='$showpid' ";
if ($showres!="") $query=$query."and result like '$showres%' ";
if ($showlang!="") $query=$query."and language='$showlang' ";
$sql = @mysql_query($query);
$row = mysql_fetch_array($sql);
$end = $start+$numperrow;
if ($showname=="") $showname2=$nowuser; else $showname2=$showname;
/*if ($only=="0") echo "<a href='status.php?start=0&only=1'> <strong>Only View My Submits</strong> </a><br>";
else echo "<a href='status.php?start=0&only=0'> <strong>View All Submits</strong> </a><br>";*/
echo "<form action='status.php' method=get>";
echo "<table class='status'>";
echo "<tr><td class='status'><strong>查找:</strong></td><td class='status'>用户名</td><td class='status'><input type='text' style='width:100px;height:24px;font:14px' name='showname' value='$showname2'></td><td class='status'>题号：</td><td class='status'><input type='text' style='width:100px;height:24px;font:14px' name='showpid' value='$showpid'></td><td class='status'>运行结果：</td><td class='status'>";
echo "<select size=1 name=showres style='font:14px' accesskey=l>
<option value='' selected>All</option>
<option value='Accepted'>完全正确</option>
<option value='Unaccepted'>不完全正确</option>
<option value='Compile Error'>编译错误</option>
</select>";
echo "</td><td class='status'>语言：</td><td class='status'>";
echo "<select size=1 name=showlang style='font:14px' accesskey=l>
<option value='' selected>All</option>
<option value=1>G++</option>
<option value=2>GCC</option>
<option value=3>Java</option>
<option value=4>Pascal</option>
<option value=5>Python</option>
<!--<option value=6>C#</option>-->
<!--<option value=7>Fortran</option>-->
<!--<option value=8>Perl</option>-->
<!--<option value=9>Ruby</option>-->
<!--<option value=10>Ada</option>-->
<!--<option value=11>SML</option>-->
</select>";
echo "<td class='status'><input type='submit' size=10 value='Show'></td></tr>";
echo "</table>";
echo "</form>";
if ($start!=0) {
	if ($start<$numperrow) echo "<a href='status.php?showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang&start=0'> <strong>&lt;&lt;Previous</strong> </a>";
	else {
		$prev=$start-$numperrow;
		echo "<a href='status.php?start=$prev&showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang'> <strong>&lt;&lt;Previous</strong> </a>";
	}
}
if ($end < $row[0]) {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='status.php?start=$end&showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang'><strong>Next&gt;&gt;</strong> </a>";
}
$query="select isroot from user where username='$nowuser'";
$result = @mysql_query($query);
list($isroot)=@mysql_fetch_row($result);
$query="select username,runid,pid,result,language,time_used,memory_used,time_submit,isshared from status where (contest_belong=0 or contest_belong=any(select cid from contest where end_time<now())) ";
/*if ($only=="0") $query="select username,runid,pid,result,language,time_used,memory_used,time_submit from status where contest_belong=0 order by runid desc limit $start,$numperrow";
else $query="select username,runid,pid,result,language,time_used,memory_used,time_submit from status where contest_belong=0 and username='$nowuser' order by runid desc limit $start,$numperrow";*/
if ($showname!="") $query=$query."and username='$showname' ";
if ($showpid!="") $query=$query."and pid='$showpid' ";
if ($showres!="") $query=$query."and result like '$showres%' ";
if ($showlang!="") $query=$query."and language='$showlang' ";
$query=$query."order by runid desc limit $start,$numperrow";
$result = @mysql_query($query);
echo "<table width=98%>";
echo "<tr>";
echo "<th width='8%'>用户名</th>";
echo "<th width='8%'>运行编号</th>";
echo "<th width='8%'>题目号</th>";
echo "<th width='8%'>题目总分</th>";
echo "<th width='35%'>得分</th>";
echo "<th width='10%'>语言</th>";
//echo "<th width='11%'>Time Used</th>";
//echo "<th width='11%'>Memory Used</th>";
echo "<th width='8%'>代码长度</th>";
echo "<th width='15%'>提交时间</th>";
echo "</tr>";
while (list($uname,$runid,$pid,$res,$lang,$timeused,$memused,$timesubmit,$isshared)=@mysql_fetch_row($result)) {
	$que="select number_of_testcase from problem where pid='$pid'";
	list($noc)=mysql_fetch_array(mysql_query($que));
	$lang=match_lang($lang);
	echo "<tr>";
	echo "<td><center><a href=userinfo.php?name=$uname>$uname</a></center></td>";
	if ($isshared==TRUE||$isroot==TRUE||$nowuser==$uname) echo "<td><center><a href=show_source.php?runid=$runid class='runid_link'>$runid</a></center></td>";
	else echo "<td><center>$runid</center></td>";
	echo "<td><center><a href=problem_show.php?pid=$pid>$pid</a></center></td>";
    echo "<td><center> ".($noc*10)." </center></td>";
	switch ($res) {
		case "Compile Error":
			echo "<td><center><strong><a href=show_ce_info.php?runid=$runid class='ce'><span class='ce'>编译错误</span></a></strong></center></td>";
			break;
        case "Problem Disabled (No Data)":
            echo "<td><center><strong>异常错误（没有数据）</strong></center></td>";
            break;
        case "Problem Disabled (No SPJ)":
            echo "<td><center><strong>异常错误（没有SPJ）</strong></center></td>";
            break;
        case "Compile Error":
            echo "<td><center><strong><a href=show_ce_info.php?runid=$runid class='ce'><span class='ce'>编译错误</span></a></strong></center></td>";
            break;
		case "Accepted":
			echo "<td><center><strong><span class='ac'>$res</span></strong></center></td>";
			break;
		case "Wrong Answer":
			echo "<td><center><strong><span class='wa'>$res</span></strong></center></td>";
			break;
		case "Runtime Error":
			echo "<td><center><strong><span class='re'>$res</span></strong></center></td>";
			break;
		case "Time Limit Exceed":
			echo "<td><center><strong><span class='tle'>$res</span></strong></center></td>";
			break;
		case "Memory Limit Exceed":
			echo "<td><center><strong><span class='mle'>$res</span></strong></center></td>";
			break;
		case "Output Limit Exceed":
			echo "<td><center><strong><span class='ole'>$res</span></strong></center></td>";
			break;
		case "Presentation Error":
			echo "<td><center><strong><span class='pe'>$res</span></strong></center></td>";
			break;
		case "Restricted Function":
			echo "<td><center><strong><span class='rf'>函数受限</span></strong></center></td>";
			break;
		case "Judging":
			echo "<td><center><strong>正在判题</strong></center></td>";
			break;
		case "Waiting":
			echo "<td><center><strong>等待判题</strong></center></td>";
			break;
		default:
			echo "<SCRIPT LANGUAGE=\"JavaScript\">getResult(\"$runid\", \"$res\");</SCRIPT>";
	}
	echo "<td><center>$lang</center></td>";
	// echo "<td><center>$timeused ms</center></td>";
	// echo "<td><center>$memused KB</center></td>";
	$query="select length(source) from status where runid=$runid";
	$tempresult=mysql_query($query);
	list($clength)=@mysql_fetch_row($tempresult);
	echo "<td><center>$clength Bytes</center></td>";
	echo "<td><center>$timesubmit</center></td>";
	echo "</tr>";
}
echo "</table>";
echo "</center>";
?>
<?php include("footer.php"); ?>
