<?php
	$lastpage=$_SERVER['HTTP_REFERER'];
	include("conn.php");
	$username=mysql_escape_string($_POST['username']);
	$password=sha1(md5($_POST['password']));
//    echo $password;
    if (!db_user_exist($username)) {
		echo "<script language='javascript'>";
		echo "alert('用户不存在!');";
		echo "history.back(1);";
		echo "</script>";
	}
	else if (!db_user_match($username,$password)) {
		echo "<script language='javascript'>";
		echo "alert('密码错误!');";
		echo "history.back(1);";
		echo "</script>";
	}
	else {
		setcookie('username',$username);
		setcookie('password',$password);
		db_change_last_login_time($username);
		echo "<script language='javascript'>";
		echo "window.location='$lastpage';";
		echo "</script>";
	}
?>
