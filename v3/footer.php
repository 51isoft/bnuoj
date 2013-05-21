      </div><!--/row-->

      <hr>
      <footer id="footer">
        <p>
          Distributed under GPLv3. | <a href='https://github.com/51isoft/bnuoj' target="_blank">Project Homepage</a> | Developer: <a href="mailto:yichaonet#gmail.com">51isoft</a> | Current Style: <b id="stylename"></b>.
        </p>
        <form class="form-inline">
          <label class="select">
            Select Style: <select class="input-medium" id="selstyle">
              <option value="cerulean">Cerulean</option>
              <option value="cyborg">Cyborg</option>
              <option value="cosmo">Cosmo</option>
              <option value="amelia">Amelia</option>
              <option value="simplex">Simplex</option>
              <option value="spacelab">Spacelab</option>
              <option value="spruce">Spruce</option>
              <option value="superhero">Superhero</option>
              <option value="united">United</option>
              <option value="journal">Journal</option>
              <option value="readable">Readable</option>
              <option value="slate">Slate</option>
              <option value="darkening">Darkening</option>
              <option value="original">Original</option>
              <option value="metro">Metro</option>
              <option value="geo">Geo</option>
            </select>
          </label>
          <label class="checkbox"> <input type="checkbox" id="selwidth" <?= $_COOKIE[$config["cookie_prefix"]."fluid_width"]==true?"checked":"" ?> />Fluid Width?</label>
        </form>
      </footer>
    </div><!--/.fluid-container-->

<?php
if (!$current_user->is_valid()) {
?>
    <div id="logindialog" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Login</h3>
      </div>
      <form action="ajax/login.php" method="post" id="login_form" class="ajform form-horizontal">
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label" for="username">Username: </label>
            <div class="controls">
              <input type='text' name='username' id='username' placeholder="Username" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="password">Password: </label>
            <div class="controls">
              <input type='password' name='password' id='password' placeholder="Password" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="cksave">Cookie:</label>
            <div class="controls">
              <select name='cksave' id='cksave'>
                <option value='0' selected>Never</option>
                <option value='1'>One Day</option>
                <option value='7'>One Week</option>
                <option value='30'>One Month</option>
                <option value='365'>One Year</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <span id="msgbox" style="display:none"></span>
          <input name='login' class="btn btn-primary" type='submit' value='Login' />
          <a class='toregister btn' href="#">Register</a>
        </div>
      </form>
    </div>

    <div id="regdialog" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Register</h3>
      </div>
      <form method="post" action="ajax/register.php" id="reg_form" class="form-horizontal ajform">
        <div class="modal-body">
          <div class="control-group">
            <label  class="control-label" for="rusername">Username: </label>
            <div class="controls">
              <input type="text" name="username" id="rusername" placeholder="Username" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="rpassword">Password: </label>
            <div class="controls">
              <input type="password" name="password" id="rpassword" placeholder="Password" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rrpassword">Repeat Password: </label>
            <div class="controls">
              <input type="password" name="repassword" id="rrpassword" placeholder="Repeat Password" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rnickname">Nickname: </label>
            <div class="controls">
              <input type="text" name="nickname" id="rnickname" placeholder="Nickname" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rschool">School: </label>
            <div class="controls">
              <input type="text" name="school" id="rschool" placeholder="School" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="remail">Email: </label>
            <div class="controls">
              <input type="text" name="email" id="remail" placeholder="Email" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <span id="msgbox" style="display:none"></span>
          <input class="btn btn-primary" type="submit" name="name" value="Submit" />
        </div>
      </form>
    </div>
<?php
} else {
?>
    <div id="modifydialog" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Modify My Information</h3>
      </div>
      <form method="post" action="ajax/user_modify.php" id="modify_form" class="form-horizontal ajform">
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label" for="rusername">Username: </label>
            <div class="controls">
              <input type="text" name="username" id="rusername" placeholder="Username" value="<?=$current_user->get_val("username")?>" readonly />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="ropassword">Old Password: </label>
            <div class="controls">
              <input type="password" name="ol_password" id="ropassword" placeholder="Old Password" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rpassword">New Password: </label>
            <div class="controls">
              <input type="password" name="password" id="rpassword" placeholder="New Password" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rrpassword">Repeat Password: </label>
            <div class="controls">
              <input type="password" name="repassword" id="rrpassword" placeholder="Repeat Password" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rnickname">Nickname: </label>
            <div class="controls">
              <input type="text" name="nickname" id="rnickname" placeholder="Nickname" value="<?=$current_user->get_val("nickname")?>" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="rschool">School: </label>
            <div class="controls">
              <input type="text" name="school" id="rschool" placeholder="School" value="<?=$current_user->get_val("school")?>" />
            </div>
          </div>
          <div class="control-group">
            <label  class="control-label" for="remail">Email: </label>
            <div class="controls">
              <input type="text" name="email" id="remail" placeholder="Email" value="<?=$current_user->get_val("email")?>" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <span id="msgbox" style="display:none"></span>
          <input class="btn btn-primary" type="submit" name="name" value="Modify" />
        </div>
      </form>
    </div>
<?php
}
?>
    <div id="newsshowdialog" class="modal hide fade" style="display:none">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="ntitle">News Title</h3>
      </div>
      <div class="modal-body">
        <div style="margin-bottom:10px">by <b id="snauthor"></b> <span id="sntime"></span></div>
        <div id="sncontent"></div>
      </div>
<?php
if ($current_user->is_root()) {
?>
      <div class="modal-footer">
        <a class="newseditbutton btn btn-primary" name="" href="">Edit</a>
      </div>
<?php
}
?>
    </div>
<script src="js/end.js?<?=filemtime("js/end.js") ?>"></script>
  </body>
</html>
