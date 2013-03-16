<?php
    include_once("conn.php");
    $username = convert_str($_POST['username']);
    if ($nowuser==$username) {
        $ops = addslashes($_POST['ol_password']);
        $ps = addslashes($_POST['password']);
        $rps = addslashes($_POST['repassword']);
        $nickname = convert_str($_POST['nickname']);
        $school = convert_str($_POST['school']);
        $email = convert_str($_POST['email']);
        $ops=sha1(md5($ops));
        $flag = 0 ; 
        if($ps != $rps) {
            echo "Retype does not match!";
            die();
        }
        if(!db_user_match($username,$ops)) {
            echo "Wrong password!";
            die();
        }
		if ($ps=="") {
			$ps=$ops;
		}
		else if (strlen($ps)<3) {
            echo "Password too short!";
            die();
		}
		else{
    		$ps = sha1(md5($ps));
		}
	    $sql_update="update user set password='$ps',email='$email',school ='$school',nickname='$nickname' where username='$username'";
    	$sql_update = change_in($sql_update);
    	$que_update=mysql_query($sql_update);
        if(!$que_update){
            echo "Failed.";die();
        }
        echo "Success!";
        setcookie("password",$ps);
    }
    else {
        echo "Invalid Request!";
    }
?>
