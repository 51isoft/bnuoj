<?php
/*
 * Created on 2009-4-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once("conn.php");
$username = $_POST['username'];
if ($nowuser==$username) {
 $ops = $_POST['ol_password'];
 $ps = $_POST['password'];
 $rps = $_POST['repassword'];
 $nickname = $_POST['nickname'];
 $school = $_POST['school'];
 $email = $_POST['email'];
$flag = 0 ;
if($ps != $rps){ ?> <script type="text/javascript">
			alert("Password Not Match!");
			window.location ='update_userinfo.php?name=<?php echo $username;?>';
		</script>
		<?php }

else {
	$query="select password from user where username='$username'";
	$result=mysql_query($query);
	$arr = mysql_fetch_array($result);

	if(sha1(md5($ops)) != $arr[0]){
		?> <script type="text/javascript">
			alert("Wrong Old Password");
			window.location ='update_userinfo.php?name=<?php echo $username;?>';
		</script>
		<?php
	}
	else{

		if ($ps==""){
			$ps=sha1(md5($ops));
			$flag = 1;
		}
		else if (strlen($ps)<3) {
			?>
			<script type="text/javascript">
			alert("Password Too Short!");
			window.location ='update_userinfo.php?name=<?php echo $username;?>';
			</script>
			<?php
		}
		else{
		$ps = sha1(md5($ps));
		$flag = 1;
		}
	}
}
if (strlen($ps)>=3)setcookie("password",$ps);
include("header.php");
echo "<center>";
if($flag == 1) {
	$sql_update="update user set password='$ps',email='$email',school ='$school',nickname='$nickname' where username='$username'";
	$sql_update = change_in($sql_update);
	$que_update=mysql_query($sql_update);
}
if($que_update == 0 ||$que_update == ""){
echo "<h3>Failed.</h3>";
}
else{
echo "<h3>Success.</h3>";
}
}
else echo "<h3>Failed.</h3>";
echo "</center>";
include("footer.php");
?>
