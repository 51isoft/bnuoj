<?php
  $pagetitle="Print";
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
<?php
  if (db_user_match($nowuser,$nowpass)) {
    $query="select username,nickname,school,email,register_time,last_login_time from user where username='$user'";
    $result=mysql_query($query);
    $arr = mysql_fetch_array($result);

?>
          <!-- insert the page content here -->
          <h1>Print your code</h1>
          <form method="post" method="" id="modform" class="form_settings">
            <div class="form_settings">
              <p><span>Username:</span><input type="text" name="username" value='<?php echo $nowuser; ?>' style='display:none' /><?php echo $nowuser; ?></p>
              <p><span>Content:</span><textarea name="content" style="height:540px"></textarea></p>
              <p style="padding-top: 15px">
                <span>&nbsp;</span><span id="modmsgbox" style="display:none; z-index:300;width:300px"></span><input class="submit" type="submit" name="name" value="Submit" />
              </p>
            </div>
          </form>
<?php
    } else {
?>
          <p>
            <div class="error"><b>Please Login!</b></div>
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
$("#print").addClass("tab_selected");
$("#modform").submit(function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#modmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
        $.post("print_check.php", $(this).serialize() ,function(data)
        {
          if($.trim(data)=='Success!')
          {
                $("#modmsgbox").fadeTo(100,0.1,function()
                {
                  $(this).html('打印成功，请等待工作人员递送。').addClass('normalmessageboxok').fadeTo(800,1, function() {
                    window.location.reload();
                  });
                });
          }
          else
          {
                $("#modmsgbox").fadeTo(100,0.1,function()
                {
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;
});
</script>
<?php
    include("end.php");
?>
