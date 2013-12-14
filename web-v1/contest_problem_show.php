<?php
	include("conn.php");
//    include_once('latexrender/latex.php');
	$cpid = $_GET['cpid'];
	$query = "select contest_problem.cid,contest_problem.pid,contest_problem.lable,contest.isprivate from contest_problem,contest where contest_problem.cpid='$cpid' and contest.cid=contest_problem.cid";
	$result = mysql_query($query);
	$row=@mysql_fetch_row($result);
	$cid=$row[0];
	$query2="select unix_timestamp(end_time) from contest where cid='$cid'";
	$result2=mysql_query($query2);
	$row2=@mysql_fetch_row($result2);
	$nowtime=time();
	$fitimeu=$row2[0];
	if (mysql_num_rows($result)==0||!db_contest_started($row[0])) {
?>
<?php include_once("cheader.php"); ?>
<?php include_once("menu.php"); ?>
<?php
		echo "<br><center>";
		echo "<span class='warn'>Problem Unavailable!</span>";
		echo "</center>";
	}
	else if ($row[3]==TRUE&&(!db_user_in_contest($row[0],$nowuser)||!db_user_match($nowuser, $nowpass))) {
?>
<?php include_once("cheader.php"); ?>
<?php include_once("menu.php"); ?>
<?php
		echo "<br><center><table width=98%>";
		echo "<span class='warn'>Private contest, please login.</span>";
		echo "</td></table></center>";
	}	else {

		$pid=$row[1];$lable=$row[2];
?>
</table>
<?php
		$query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,special_judge_status,hide from problem where pid='$pid'";
		$result = mysql_query($query);
		list($title,$desc,$inp,$oup,$sin,$sout,$hint,$source,$tl,$ctl,$ml,$spj,$hide)=mysql_fetch_row($result);
        $pagetitle=$lable." - ".$title;
        include_once("cheader.php");
        include("cmenu.php");

//		$title = change_out($title);
//		$desc = change_out($desc);
//		$inp = change_out($inp);
//		$oup = change_out($oup);
//		$sin = change_out($sin);
//		$sout = change_out($sout);
//		$hint = change_out($hint);
		$source = change_out($source);

                $query = "select count(*) from status where contest_belong='$row[0]' and pid='$pid' and result='Accepted'";
                $result = mysql_query($query);
                list($tac) = @mysql_fetch_row($result);
                $query = "select count(*) from status where contest_belong='$row[0]' and pid='$pid'";
                $result = mysql_query($query);
                list($ts) = @mysql_fetch_row($result);

		echo "<center><table width=98%><td class='pcontent'>";
		echo "<center><h2>$lable: $title</h2><br>\n";
		echo "Time Limit: $tl ms &nbsp;&nbsp;&nbsp; Case Time Limit: $ctl ms &nbsp;&nbsp;&nbsp; Memory Limit: $ml KB<br>";
		if ($spj==false) echo "Submit: $ts &nbsp;&nbsp;&nbsp; Accepted: $tac <br></center>";
		else echo "Submit: $ts &nbsp;&nbsp;&nbsp; Accepted: $tac &nbsp;&nbsp;&nbsp; <font color=red><strong>Special Judge</strong></font><br></center>";
		echo "\n<h3>Description</h3><br>\n";
		echo latex_content($desc)."<br>\n";
		echo "<h3>Input</h3><br>\n";
		echo latex_content($inp)."<br>\n";
		echo "<h3>Output</h3><br>\n";
		echo latex_content($oup)."<br>\n";
		echo "<h3>Sample Input</h3><br>\n";
//		echo "<span class='data'>$sin</span><br>";
        echo "<pre>$sin</pre>\n";
		echo "<h3>Sample Output</h3><br>\n";
//		echo "<span class='data'>$sout</span><br>";
        echo "<pre>$sout</pre>\n";
		if (strlen($hint)>strlen('<p></p>')) {
			echo "<h3>Hint</h3><br>";
			echo latex_content($hint)."<br>\n";
		}
		//echo "<h3>Source</h3><br>";
		//echo "$source<br>";
		echo "<p align=center> <a href=contest_submit.php?lable=$lable&cid=$cid class='bottom_link'> Submit </a> ";
		if (db_contest_passed($cid)&&!$hide) echo "<a href=problem_show.php?pid=$pid class='bottom_link'> [PID:$pid] </a> ";
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
            echo "<a href=admin_problem_add.php?pid=$pid class='bottom_link'> [Edit] </a>";
        }
		echo "</p></td></table></center>";
	}
	include("footer.php");
?>
