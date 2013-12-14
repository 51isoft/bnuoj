<?php
	$lastpage=$_SERVER['HTTP_REFERER'];
	include("conn.php");
	$username=mysql_escape_string($_POST['username']);
	$password=sha1(md5($_POST['password']));
//    echo $password;
    if (!db_user_exist($username)) {
		echo "<script language='javascript'>";
		echo "alert('No such user!');";
		echo "history.back(1);";
		echo "</script>";
	}
	else if (!db_user_match($username,$password)) {
		echo "<script language='javascript'>";
		echo "alert('Password incorrect!');";
		echo "history.back(1);";
		echo "</script>";
	}
	else {
        $exp=time()+$_POST['cksave']*24*60*60;
        if ($_POST['cksave']==0) $exp=0;
		setcookie('username',$username,$exp);
		setcookie('password',$password,$exp);
		db_change_last_login_time($username);
		echo "<script language='javascript'>";
		echo "window.location='$lastpage';";
		echo "</script>";
	}
?>
