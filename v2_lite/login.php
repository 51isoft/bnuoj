<?php
    include("conn.php");
    $username=mysql_escape_string($_POST['username']);
    $password=sha1(md5($_POST['password']));
    if (!db_user_exist($username)) {
    	echo "No such user!";
    }
    else if (!db_user_match($username,$password)) {
    	echo "Password incorrect!";
    }
    else {
    	$exp=time()+$_POST['cksave']*24*60*60;
    	if ($_POST['cksave']==0) $exp=0;
    	setcookie('username',$username,$exp);
    	setcookie('password',$password,$exp);
    	db_change_last_login_time($username);
    	echo "Yes";
    }
?>
