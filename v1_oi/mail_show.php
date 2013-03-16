<?php
	include_once("conn.php");
	$mailid = $_GET['mailid'];
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)||!db_mail_user_match($nowuser,$mailid)){
		include("header.php");
		echo "<p class='warn'>请先登陆.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else{
        $query="select sender,title,content,mail_time from mail where mailid='$mailid'";
        $res=mysql_query($query);
        list($sender,$title,$content,$mailtime)=@mysql_fetch_array($res);
        $pagetitle=$title;
		include("header.php");
?>

<center>

<table width=80% class=mail>

<?php

//echo "<tr><th class=mail><pre>$title</pre></th></tr>";
echo "<caption class=mail><pre>".change_in($title)."</pre></caption>";
echo "<tr><td class=mail>发送者: <a href='userinfo.php?name=$sender'>$sender</a></td></tr>";
echo "<tr><td class=mail>时间: $mailtime</td></tr>";
echo "<tr><td class=mail><pre>".change_in($content)."</pre></td></tr>";
$query="update mail set status=true where mailid='$mailid'";
$res=mysql_query($query);
?>

</table>
<a href='mail_index.php' class='bottom_link'>[返回收件箱]</a><a href='mail_send.php?reply=<?php echo $mailid; ?>' class='bottom_link'>[回复此邮件]</a>
</center>

<?php
}
include("footer.php");
?>
