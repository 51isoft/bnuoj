<?php
	include("conn.php");
//    include_once('latexrender/latex.php');
    $pid = $_GET['pid'];
	$querypage="select count(*) from problem where pid<$pid and hide=0";
	list($ppage)=mysql_fetch_array(mysql_query($querypage));
	$ppage=intval($ppage/$problemperpage)+1;
	$query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,total_submit,total_ac,special_judge_status,hide,vid,vname from problem where pid='$pid'";
	$result = mysql_query($query);
	list($title,$desc,$inp,$oup,$sin,$sout,$hint,$source,$tl,$ctl,$ml,$ts,$tac,$spj,$hide,$vid,$vname)=@mysql_fetch_row($result);
    if (!$hide) $pagetitle="BNUOJ ".$pid." - ".$title;
    else $pagetitle="Problem Unavailable";
    include("header.php");
	if (mysql_num_rows($result)!=1||($hide&&!db_user_isroot($nowuser))) {
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
		echo "<center><h2>$title</h2><br>\n";
        if ($hide) echo "<strong>This problem is hidden.</strong><br>";
		echo "Time Limit: $tl ms &nbsp;&nbsp;&nbsp; Case Time Limit: $ctl ms &nbsp;&nbsp;&nbsp; Memory Limit: $ml KB<br>";
		if ($spj==false) echo "Submit: $ts &nbsp;&nbsp;&nbsp; Accepted: $tac <br></center>";
		else echo "Submit: $ts &nbsp;&nbsp;&nbsp; Accepted: $tac &nbsp;&nbsp;&nbsp; <font color=red><strong>Special Judge</strong></font><br></center>";
		if (db_problem_isvirtual($pid)) {
            echo "<center><font color=red><b>This problem will be judged on $vname. Original ID: ";
            if ($vname=="PKU")  echo "<a href='http://acm.pku.edu.cn/JudgeOnline/problem?id=$vid' target='_blank'>$vid</a>";
            if ($vname=="OpenJudge")  echo "<a href='http://poj.openjudge.cn/practice/$vid' target='_blank'>$vid</a>";
            if ($vname=="CodeForces")  {
                $ov=$vid;
                $v1=$vid[strlen($vid)-1];
                $vid[strlen($vid)-1]='/';
                echo "<a href='http://codeforces.com/problemset/problem/$vid$v1' target='_blank'>$ov</a>";
            }
            if ($vname=="HDU")  echo "<a href='http://acm.hdu.edu.cn/showproblem.php?pid=$vid' target='_blank'>$vid</a>";
            if ($vname=="LightOJ")  echo "<a href='http://www.lightoj.com/volume_showproblem.php?problem=$vid' target='_blank'>$vid</a>";
            if ($vname=="Ural")  echo "<a href='http://acm.timus.ru/problem.aspx?num=$vid' target='_blank'>$vid</a>";
            if ($vname=="SPOJ")  echo "<a href='http://www.spoj.pl/problems/$vid/' target='_blank'>$vid</a>";
            if ($vname=="SGU")  echo "<a href='http://acm.sgu.ru/problem.php?contest=0&problem=$vid' target='_blank'>$vid</a>";
            if ($vname=="UESTC")  echo "<a href='http://acm.uestc.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
            if ($vname=="FZU")  echo "<a href='http://acm.fzu.edu.cn/problem.php?pid=$vid' target='_blank'>$vid</a>";
            if ($vname=="NBUT")  echo "<a href='http://cdn.ac.nbutoj.com/Problem/view.xhtml?id=$vid' target='_blank'>$vid</a>";
            if ($vname=="WHU")  echo "<a href='http://acm.whu.edu.cn/land/problem/detail?problem_id=$vid' target='_blank'>$vid</a>";
            if ($vname=="SYSU")  echo "<a href='http://soj.me/$vid' target='_blank'>$vid</a>";
            if ($vname=="SCU")  echo "<a href='http://cstest.scu.edu.cn/soj/problem.action?id=$vid' target='_blank'>$vid</a>";
            if ($vname=="HUST")  echo "<a href='http://acm.hust.edu.cn/problem.php?id=$vid' target='_blank'>$vid</a>";
            if ($vname=="UVALive")  {
                $tvid=intval($vid)-1999;
                if (intval($vid)>5722) $tvid+=10;
                echo "<a href='http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&page=show_problem&problem=".$tvid."' target='_blank'>$vid</a>";
            }
            if ($vname=="UVA")  {
                list($url)=mysql_fetch_array(mysql_query("select url from vurl where voj='UVA' and vid='$vid'"));
                echo "<a href='$url' target='_blank'>$vid</a>";
            }
            echo ".</b></font></center><br>";
        }
        echo "\n<p align=right><a href=problem_show.php?pid=".(intval($pid)-1)." class=bottom_link>[Prev]</a><a href=problem_show.php?pid=".(intval($pid)+1)." class=bottom_link>[Next]</a></p>";
		echo "\n<h3>Description</h3><br>\n";
		echo latex_content($desc)."<br>\n";
		echo "<h3>Input</h3><br>\n";
		echo latex_content($inp)."<br>\n";
		echo "<h3>Output</h3><br>\n";
		echo latex_content($oup)."<br>\n";
		echo "<h3>Sample Input</h3>\n";
	//	echo "<span class='data'>$sin</span><br>";
        echo "<pre>$sin</pre>\n";
		echo "<h3>Sample Output</h3>\n";
	//	echo "<span class='data'>$sout</span><br>";
        echo "<pre>$sout</pre>\n";
		if (strlen($hint)>strlen('<p></p>')) {
			echo "<h3>Hint</h3><br>";
			echo latex_content($hint)."<br>\n";
		}
		echo "<h3>Source</h3>";
		echo "<a href='problem_list.php?search=".str_replace(' ','+',$srcc)."' class='bottom_link'><pre>$source</pre></a>\n";
		echo "<p align=center> <a href=submit.php?pid=$pid class='bottom_link'> Submit </a> <a href=problem_stat.php?pid=$pid class='bottom_link'> Statistics </a> <a href=discuss.php?pid=$pid class='bottom_link'> Discuss </a>";
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
            echo "<a href=admin_problem_add.php?pid=$pid class='bottom_link'> [Edit] </a>"; 
        }
		echo "</p></td></table></center>";
	}
	include("footer.php");
?>
