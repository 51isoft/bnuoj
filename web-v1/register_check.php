<?php
	include("header.php");
	echo "<center>";
	if (strlen($_POST['username']) == 0) {
		echo "<span class='warn'>Invalid Request</span>";
	}
	else if (strlen($_POST['username']) < 3)
		echo "<span class='warn'>Username too short!</span>";
	else if (strlen($_POST['username']) > 255)
		echo "<span class='warn'>Username too long!</span>";
	else {
		$s = $_POST['username'];
		for ($i = 0; $i < strlen($s); $i++)
		if ( $s[$i] >= '0' && $s[$i] <= '9' || $s[$i] >= 'a' && $s[$i] <= 'z' || $s[$i] >= 'A' && $s[$i] <= 'Z'|| $s[i]=='-' || $s[i]=='_')
			continue;
		else break;
		if ($i != strlen($s) )
			echo "<span class='warn'>Invalid Username!</span>";
		else if ( db_user_exist($_POST['username']) )
			echo "<span class='warn'>Username Already Exists!</span>";
		else if ( strlen($_POST['password']) < 3)
			echo "<span class='warn'>Password too short!</span>";
		else if ( $_POST['password'] != $_POST['repassword'] )
			echo "<span class='warn'>Password doesn't match!</span>";
		else {
			$row[0] = $_POST['username'];
			$row[1] = $_POST['password'];
			if ($_POST['nickname']=="") $row[2] = $_POST['username'];
			else $row[2] = $_POST['nickname'];
			$row[3] = $_POST['school'];
			$row[4] = $_POST['email'];
			if ( db_user_insert($row) )
				echo "<span class='note'>Register Success!</span>";
			else
				echo "<span class='warn'>Register Failed.</span>";
		}
	}
	echo "<br><a href='index.php' class='bottom_link'>Back to homepage</a>";
	echo "</center>";
	include("footer.php");
?>

