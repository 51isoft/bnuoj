<head>
<?php include_once("conn.php"); ?>
<title>北师大OI版在线判题系统</title>
<link rel=stylesheet href='bnuoj.css'>
<link type="text/css" rel="stylesheet" href="sh_style.css">
<script type="text/javascript" src="js/sh_main.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body onload="sh_highlightDocument();">
<center>
<table class="webtop" width="98%">
<tr>
<th width="60%"><h1>北师大OI版在线判题系统</h1></th>
<th width="20%">
<?php
	if (!$_COOKIE["username"]||!$_COOKIE["password"]) {
		$nowuser="";
		$nowpass="";
		print "<form action='login.php' method=post>";
		print "<table width=100%>";
		print "<tr><td style='width:20%'>Username：</td><td><input type='text' name='username' style='height:25px;width:100％'></td></tr>";
		print "<tr><td style='width:20%'>Password：</td><td><input type='password' name='password' style='height:25px;width:100％'></td></tr>";
		print "</table>";
		print "<table width=100%>";
		print "<tr><th><input name='login' type='submit' size=10 value='Login'>&nbsp;&nbsp;<a href='register.php'>Register</a></th><tr>";
		print "</table>";
		print "</form>";
	}
	else {
		$nowuser=$_COOKIE["username"];
		$nowpass=$_COOKIE["password"];
		print "<table width=100%>";
		print "<tr><th>".$nowuser."&nbsp;&nbsp;<a href='update_userinfo.php?name=$nowuser'>Modify</a>"."</th></tr>";
		if (db_user_isroot($nowuser)) print "<tr><th><a href='admin_index.php'>Admin</a></th></tr>";
		print "<tr><th><a href='logout.php'>Logout</a></th></tr>";
		print "</table>";
	}
?>
</th>
</tr>
</table>
</center>
