<?php
  $pagetitle="Register";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("common_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <h1>Register</h1>
          <form method="post" method="" id="regform" class="form_settings">
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
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/register.js"></script>
<script type="text/javascript">
$("#register").addClass("tab_selected");
</script>
<?php
    include("end.php");
?>
