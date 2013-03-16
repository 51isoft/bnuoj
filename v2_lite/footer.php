    <div id="footer">Distributed Under GPLv3. | Author: <a href="mailto:yichaonet#gmail.com">51isoft</a> | Original design by <a href="http://www.dcarter.co.uk" target='_blank'>dcarter</a>, modified by <a href="mailto:yichaonet#gmail.com">51isoft</a>.</div>
  </div>

  <div id="logindialog" class="topdialog" title="Login" style="display:none">
    <form action="" method="post" id="login_form">
      <table width="100%">
        <tr>
          <th>Username: </th>
          <td><input type='text' name='username' id='username' /></td>
        </tr>
        <tr>
          <th>Password: </th>
          <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
          <th>Cookie:</th>
          <td>
            <select size='1' name='cksave' id='cksave'>
              <option value='0' selected= 'selected'>Never</option>
              <option value='1'>One Day</option>
              <option value='7'>One Week</option>
              <option value='30'>One Month</option>
              <option value='365'>One Year</option>
            </select>
          </td>
        </tr>
      </table>
      <div class="center">
        <input name='login' type='submit' value='Login' />
        <span id="loginmsgbox" style="display:none; z-index:600;"></span>
        <a class='toregister' href="javascript:void(0)">Register</a>
      </div>
    </form>
  </div>
  <div id="regdialog" class="topdialog" title="Register" style="display:none">
    <form method="post" action="" id="regform" class="form_settings">
      <div class="form_settings">
        <p><span>Username:</span><input type="text" name="username" /></p>
        <p><span>Password:</span><input type="password" name="password" /></p>
        <p><span>Repeat Password:</span><input type="password" name="repassword" /></p>
        <p><span>Nickname:</span><input type="text" name="nickname" /></p>
        <p><span>School:</span><input type="text" name="school" /></p>
        <p><span>Email:</span><input type="text" name="email" /></p>
        <p style="padding-top: 15px">
          <span>&nbsp;</span><span id="regmsgbox" style="display:none; z-index:300;"></span><input class="submit" type="submit" name="name" value="Submit" />
        </p>
      </div>
    </form>
  </div>
  <div id="newsshowdialog" class="topdialog" title="News Title" style="display:none">
    <div class="center">
      <h2 style="margin:0" id="sntitle"></h2> by <b id="snauthor"></b> <span id="sntime"></span>
    </div>
    <hr>
    <div class="content-wrapper ui-corner-all" style="margin:20px 0" id="sncontent">
    </div>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
      <a class="newseditbutton" name="" href="">Edit</a>
<?php
    }
?>
  </div>
  <script type="text/javascript">
    $(function() {
      $( "input:submit, a", "#logindialog" ).button();
      $( "input:submit, a", "#regdialog" ).button();
    });
  </script>
  <script type="text/javascript" src="js/register.js?<?php echo filemtime("js/register.js"); ?>"></script>

