<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php include_once("conn.php"); 
include("fckeditor/fckeditor.php"); 
if ($pagetitle=="") $pagetitle="北师大OI版在线判题系统";
?>
<title><? echo $pagetitle; ?></title>
<link rel=stylesheet href='bnuoj.css'>
<link type="text/css" rel="stylesheet" href="sh_style.css">
<script type="text/javascript" src="js/sh_main.js"></script>
</head>
<body onload="sh_highlightDocument();">
<center>
<table class="webtop" width="98%">
<tr>
<th width="60%"><img src="BNUOJ.gif" /></th>
<th width="20%">
<?php
	if (!$_COOKIE["username"]||!$_COOKIE["password"]) {
		$nowuser="";
		$nowpass="";
		print "<form action='login.php' method=post>";
		print "<table width=100%>";
		print "<tr><td style='width:20%'>用户名：</td><td><input type='text' name='username' style='height:25px;width:100％'></td></tr>";
		print "<tr><td style='width:20%'>密码：</td><td><input type='password' name='password' style='height:25px;width:100％'></td></tr>";
		print "</table>";
		print "<table width=100%>";
		print "<tr><th><input name='login' type='submit' size=10 value='登陆'>&nbsp;&nbsp;<a href='register.php'>注册新用户</a></th><tr>";
		print "</table>";
		print "</form>";
	}
	else {
		$nowuser=$_COOKIE["username"];
		$nowpass=$_COOKIE["password"];
		print "<table width=100%>";
		print "<tr><th>".$nowuser."&nbsp;&nbsp;<a href='update_userinfo.php?name=$nowuser'>修改资料</a>"."</th></tr>";
		if (db_user_isroot($nowuser)) print "<tr><th><a href='admin_index.php'>管理后台</a></th></tr>";
		$unreadmail=intval(db_get_unread_mail_number($nowuser));
		if ($unreadmail>0) print "<tr><th><a href='mail_index.php'>收件箱(<font color=red>$unreadmail</font>)</a></th></tr>";
		else print "<tr><th><a href='mail_index.php'>收件箱(0)</a></th></tr>";
		print "<tr><th><a href='logout.php'>退出登陆</a></th></tr>";
		print "</table>";
	}
?>
</th>
</tr>
</table>
<?php include_once("menu.php"); ?>
