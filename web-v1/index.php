<?php include("header.php"); ?>
<br><br>
<p class="welc">Welcome to BNU Online Judge!</p>
<center>
<?php
	$sql="select cid,title from contest where start_time<now() and end_time>now() and isvirtual=0 and type=0 order by cid desc";
	$res=mysql_query($sql);
	while (list($cid,$title)=mysql_fetch_array($res)) {
		echo "<strong><a href='contest_show.php?cid=$cid'>".$title."</a> is <i><font color=red>running...</font></i></strong><br><br>";
	}
	$sql="select cid,title,start_time from contest where start_time>now() and isvirtual=0 and type=0 order by start_time";
	$res=mysql_query($sql);
	while (list($cid,$title,$sttime)=mysql_fetch_array($res)) {
		echo "<strong><a href='contest_show.php?cid=$cid'>".$title."</a> is <i><font color=blue>scheduled</font></i> at ".$sttime.".</strong><br><br>";
	}
    	$sql="select cid,title from contest where end_time+7200>now() and end_time<now() and isvirtual=0 and type=0 order by end_time";
        $res=mysql_query($sql);
        while (list($cid,$title)=mysql_fetch_array($res)) {
		echo "<strong><a href='contest_show.php?cid=$cid'>".$title."</a> has just <i><font color=green>passed</font></i>.</strong><br><br>";
	}
	$sql="select cid,title from contest where start_time<now() and end_time>now() and isvirtual=1 and type=0 order by cid desc limit 0,1";
	$res=mysql_query($sql);
	while (list($cid,$title)=mysql_fetch_array($res)) {
		echo "<strong>Virtual Contest: <a href='contest_show.php?cid=$cid'>".$title."</a> is <i><font color=red>running...</font></i></strong><br><br>";
	}
	$sql="select cid,title,start_time from contest where start_time>now() and isvirtual=1 and type=0 order by start_time limit 0,1";
	$res=mysql_query($sql);
	while (list($cid,$title,$sttime)=mysql_fetch_array($res)) {
		echo "<strong>Virtual Contest: <a href='contest_show.php?cid=$cid'>".$title."</a> is <i><font color=blue>scheduled</font></i> at ".$sttime.".</strong><br><br>";
	}
    	$sql="select cid,title from contest where end_time+7200>now() and end_time<now() and isvirtual=1 and type=0 order by end_time limit 0,1";
        $res=mysql_query($sql);
        while (list($cid,$title)=mysql_fetch_array($res)) {
		echo "<strong>Virtual Contest: <a href='contest_show.php?cid=$cid'>".$title."</a> has just <i><font color=green>passed</font></i>.</strong><br><br>";
	}

?>
<a href='guide.htm'><strong>If you don't know how to start, click here.</strong></a>
<br><br>
<strong>If you have any problem with our OJ, see </strong><a style="color:red" href='faq.php'><strong>Frequently Asked Questions.</strong></a>
<br><br>
<strong><a style="color:blue" href='notice.php'><font size='5'>如何避免由于编译器差别带来的错误</font></a></strong>
<br><br>
<!--
<strong><a href='http://acm.cist.bnu.edu.cn/camp/'><font color=red>Beijing Normal University ICPC Team Home Page</font></a></strong>
<br><br>-->
<font color="purple"><strong>Our OJ is still under construction, any suggestion is welcome.</font></strong>
</center>
<br>
<?php include("footer.php"); ?>
