<?php include("header.php");?>
<center>
  <form name="form1" method="post" action="register_check.php">
    <table width="40%">
      <tr>
        <td width="30%" class="reglist">用户名:</td>
        <td width="70%" class="reglist"><input type="text" name="username" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">密码:</td>
        <td width="70%" class="reglist"><input type="password" name="password" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">重复密码:</td>
        <td width="70%" class="reglist"><input type="password" name="repassword" style="height:25px;width:150px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">昵称:</td>
        <td width="70%" class="reglist"><input type="text" name="nickname" style="height:25px;width:100%"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">学校:</td>
        <td width="70%" class="reglist"><input type="text" name="school" style="height:25px;width:250px"></td>
      </tr>
      <tr>
        <td width="30%" class="reglist">邮箱:</td>
        <td width="70%" class="reglist"><input type="text" name="email" style="height:25px;width:250px"></td>
      </tr>
      <tr>
        <th colspan="2" class="reglist" scope="row"><input type="submit" name="Submit" value="注册"></th>
      </tr>
    </table>
    </form>
</center>
<?php include("footer.php");?>
