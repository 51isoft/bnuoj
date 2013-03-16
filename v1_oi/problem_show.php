<?php
	include("conn.php");
	$pid = $_GET['pid'];
	$querypage="select count(*) from problem where pid<$pid and hide=0";
	list($ppage)=mysql_fetch_array(mysql_query($querypage));
	$ppage=intval($ppage/$problemperpage)+1;
	$query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,total_submit,total_ac,special_judge_status,hide,vid,vname from problem where pid='$pid'";
	$result = mysql_query($query);
	list($title,$desc,$inp,$oup,$sin,$sout,$hint,$source,$ctl,$tl,$ml,$ts,$tac,$spj,$hide,$vid,$vname)=@mysql_fetch_row($result);
    if (!$hide) $pagetitle="北师大OJ ".$pid." - ".$title;
    else $pagetitle="题目不存在";
    include("header.php");
	if (mysql_num_rows($result)!=1||$hide) {
		echo "<br><center><table width=98%>";
		echo "<span class='warn'>Problem Unavailable!</span>";
		echo "</td></table></center>";
	}
	else {
//		$title = change_out($title);
//		$desc = change_out($desc);
//		$inp = change_out($inp);
//		$oup = change_out($oup);
//		$sin = change_out($sin);
//		$sout = change_out($sout);
//		$hint = change_out($hint);
		$srcc = $source;
//		$source = change_out($source);
		echo "<center><table width=98%><td class='pcontent'>";
		echo "<center><h2>$title</h2><br>";
		echo "时间限制: $tl ms &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 内存限制: $ml KB<br>";
		if ($spj==false) echo "提交数: $ts &nbsp;&nbsp;&nbsp; 通过数: $tac <br></center>";
		else echo "提交数: $ts &nbsp;&nbsp;&nbsp; 通过数: $tac &nbsp;&nbsp;&nbsp; <font color=red><strong>Special Judge</strong></font><br></center>";
		if (db_problem_isvirtual($pid)) echo "<center><font color=red><b>This problem will be judged on $vname. Original ID: <a href='http://acm.pku.edu.cn/JudgeOnline/problem?id=$vid' target='_blank'>$vid</a>.</b></font></center><br>";
		echo "<h3>题目描术</h3><br>";
		echo "$desc<br>";
		echo "<h3>输入</h3><br>";
		echo "$inp<br>";
		echo "<h3>输出</h3><br>";
		echo "$oup<br>";
		echo "<h3>输入样例</h3><br>";
		//echo "<span class='data'>$sin</span><br>";
        echo "<pre>$sin</pre>";
		echo "<h3>输出样例</h3><br>";
		//echo "<span class='data'>$sout</span><br>";
        echo "<pre>$sout</pre>";
        if (strlen($hint)>strlen('<p></p>')) {
			echo "<h3>提示</h3><br>";
			echo "$hint<br>";
		}
		echo "<h3>来源</h3><br>";
		echo "<a href='problem_search.php?searchsource=".str_replace(' ','+',$srcc)."' class='bottom_link'>$source</a>";
		echo "<p align=center> <a href=submit.php?pid=$pid class='bottom_link'> 提交 </a> <a href=problem_stat.php?pid=$pid class='bottom_link'> 统计信息 </a> </p>";
		echo "</td></table></center>";
	}
	include("footer.php");
?>

