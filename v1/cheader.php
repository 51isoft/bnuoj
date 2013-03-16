<html>
<head>
<?php include_once("conn.php");
include_once('latexrender/latex.php');?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
if ($pagetitle=="") $pagetitle="BNU Online Judge";
?>
<title><?php echo $pagetitle; ?></title>
<link rel=stylesheet href='bnuoj.css'>
<link rel=stylesheet href='css/flexigrid.pack.css'>
<link rel="shortcut icon" href="favicon.ico" />
<link type="text/css" rel="stylesheet" href="sh_style.css">
<script type="text/javascript" src="js/sh_main.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="js/jquery.marquee.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="media/js/pageselect.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="adjlist.js"></script>

</head>

<body onload="sh_highlightDocument();">
<center>
<table class="webtop" width="98%">
<tr>
<th width="60%"><img src='BNUOJ.gif'></th>
<th width="20%">
<?php
	if (!$_COOKIE["username"]||!$_COOKIE["password"]) {
		$nowuser="";
		$nowpass="";
		print "<form action='login.php' method=post>";
		print "<table width=100%>";
		print "<tr><td style='width:20%'>Username: </td><td><input type='text' name='username' style='height:25px;width:100％'></td></tr>";
		print "<tr><td style='width:20%'>Password: </td><td><input type='password' name='password' style='height:25px;width:100％'></td></tr>";
        print "<tr><td style='width:20%'>Cookie: </td><td>";
        print "<select size=1 name='cksave'>";
        print "<option value=0 selected>Never</option>";
        print "<option value=1>One Day</option>";
        print "<option value=7>One Week</option>";
        print "<option value=30>One Month</option>";
        print "<option value=365>One Year</option>";
        print "</select>";
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
		print "<tr><th><a href='userinfo.php?name=$nowuser'>".$nowuser."</a>&nbsp;&nbsp;<a href='update_userinfo.php?name=$nowuser'>Modify</a>"."</th></tr>";
		if (db_user_isroot($nowuser)) print "<tr><th><a href='admin_index.php'>Admin</a></th></tr>";
		print "<tr><th><a href='logout.php'>Logout</a></th></tr>";
		print "</table>";
	}
?>
</th>
</tr>
</table>
</center>
