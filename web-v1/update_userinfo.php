<?php
include("header.php");
	$name = $_GET['name'];
	$query="select username,nickname,school,email,register_time,last_login_time from user where username='$name'";
	$result=mysql_query($query);
	$arr = mysql_fetch_array($result);
if ($nowuser==$name) {
?>
<center>
  <form name="form1" method="post" action="update_userinfo_result.php">
    <table width="40%">
      <tr>
        <td width="30%" class="reglist">Username:</td>
        <td width="70%" class="reglist"><input type="text" name="username" style="height:25px;width:150px" value="<?php echo $name;?>" readonly="readonly"></td>
      </tr>
       <tr>
        <td width="30%" class="reglist">Old Password:</td>
        <td width="70%" class="reglist"><input type="password" name="ol_password" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">Password:</td>
        <td width="70%" class="reglist"><input type="password" name="password" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">Repeat Password:</td>
        <td width="70%" class="reglist"><input type="password" name="repassword" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">Nickname:</td>
        <td width="70%" class="reglist"><input type="text" name="nickname" style="height:25px;width:100%" value="<?php $arr['nickname']=str_replace("\"","'",$arr['nickname']); $arr['nickname']=str_replace(">","&gt;",$arr['nickname']); echo $arr['nickname'];?>"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">School:</td>
        <td width="70%" class="reglist"><input type="text" name="school" style="height:25px;width:100%" value="<?php echo $arr['school'];?>"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">Email:</td>
        <td width="70%" class="reglist"><input type="text" name="email" style="height:25px;width:100%" value="<?php echo $arr['email'];?>"></td>
      </tr>
      <tr>
        <th colspan="2" class="reglist" scope="row"><input type="submit" name="Submit" value="Submit"></th>
      </tr>
    </table>
    </form>
</center>
<?php
}
include("footer.php");
?>
