<?php
	include("conn.php");
    $_POST['password']=addslashes($_POST['password']);
    $_POST['repassword']=addslashes($_POST['repassword']);
    $_POST['nickname']=convert_str($_POST['nickname']);
    $_POST['school']=convert_str($_POST['school']);
    $_POST['email']=convert_str($_POST['email']);

	if (strlen($_POST['username']) == 0) {
		echo "Empty Username!";
	}
	else if (strlen($_POST['username']) < 3)
		echo "Username too short!";
	else if (strlen($_POST['username']) > 64)
		echo "Username too long!";
	else {
		$s = convert_str($_POST['username']);
		for ($i = 0; $i < strlen($s); $i++)
		if ( $s[$i] >= '0' && $s[$i] <= '9' || $s[$i] >= 'a' && $s[$i] <= 'z' || $s[$i] >= 'A' && $s[$i] <= 'Z'|| $s[i]=='-' || $s[i]=='_')
			continue;
		else break;
		if ($i != strlen($s) )
			echo "Invalid Username!";
		else if ( db_user_exist($_POST['username']) )
			echo "Username Already Exists!";
		else if ( strlen($_POST['password']) < 3)
			echo "Password too short!";
		else if ( $_POST['password'] != $_POST['repassword'] )
			echo "Password doesn't match!";
		else {
			$row[0] = $_POST['username'];
			$row[1] = $_POST['password'];
			if ($_POST['nickname']=="") $row[2] = $_POST['username'];
			else $row[2] = $_POST['nickname'];
			$row[3] = $_POST['school'];
			$row[4] = $_POST['email'];
			if ( db_user_insert($row) )
				echo "Success!";
			else
				echo "Register Failed.";
		}
	}
?>

