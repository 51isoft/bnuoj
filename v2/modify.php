<?php
  $pagetitle="Modify My Information";
  include_once("header.php");
  include_once("menu.php");
  $user=convert_str($_GET['username']);
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
<?php
  if ($user==$nowuser&&db_user_exist($user)) {
    $query="select username,nickname,school,email,register_time,last_login_time from user where username='$user'";
    $result=mysql_query($query);
    $arr = mysql_fetch_array($result);

?>
          <!-- insert the page content here -->
          <h1>Modify Information</h1>
          <form method="post" method="" id="modform" class="form_settings">
            <div class="form_settings">
              <p><span>Username:</span><input type="text" name="username" value='<?php echo $nowuser; ?>' style='display:none' /><?php echo $nowuser; ?></p>
              <p><span>Old Password:</span><input type="password" name="ol_password" /></p>
              <p><span>Password:</span><input type="password" name="password" /></p>
              <p><span>Repeat Password:</span><input type="password" name="repassword" /></p>
              <p><span>Nickname:</span><input type="text" name="nickname" value='<?php echo str_replace(">","&gt;",$arr['nickname']) ?>' /></p>
              <p><span>School:</span><input type="text" name="school" value="<?php echo $arr['school'];?>" /></p>
              <p><span>Email:</span><input type="text" name="email" value="<?php echo $arr['email'];?>" /></p>
              <p style="padding-top: 15px">
                <span>&nbsp;</span><span id="modmsgbox" style="display:none; z-index:300;"></span><input class="submit" type="submit" name="name" value="Submit" />
              </p>
            </div>
          </form>
<?php
    } else {
?>
          <p>
            <div class="error"><b>Invalid Request!</b></div>
          </p>
<?php
    }
?>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type='text/javascript'>
$("#modform").submit(function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#modmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
        $.post("modify_check.php", $(this).serialize() ,function(data)
        {
          if($.trim(data)=='Success!')
          {
                $("#modmsgbox").fadeTo(100,0.1,function()
                {
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(800,1, function() {
//                    $.cookie('username',null);
//                    $.cookie('password',null);
//                    $("#logindialog").dialog("open");
                    window.location.reload();
                  });
                });
          }
          else
          {
                $("#modmsgbox").fadeTo(100,0.1,function() //start fading the messagebox
                {
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;//not to post the  form physically
});
</script>
<?php
    include("end.php");
?>
